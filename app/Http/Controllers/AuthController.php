<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Mail\SendOtpMail;
use App\Models\Otp;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $userData = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($userData);

        $otp = rand(1000, 9999);

        $userData->otp()->updateOrCreate(
            ['user_id' => $userData->id],
            [
                'code' => $otp,
                'expires_at' => now()->addMinutes(5)
            ]
        );

        session(['user_id'], $userData->id);
        Mail::to($userData->email)->send(new SendOtpMail($userData->name, $otp));

        return redirect()->route('auth.otp');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->is_suspended) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('auth.login')->with("error", "Tidak dapat login!, Akun telah ditangguhkan silahkan hubungi admin.");
            }

            if(!$user->is_email_verified && $user->role == UserRole::Admin) {
                return redirect()->route("auth.otp")->with("error", "Email anda belum terverifikasi, Harap verifikasi dulu");
            }

            if ($user->role == UserRole::Admin || $user->role == UserRole::Superadmin) {
                return redirect()->route("dashboard");
            } elseif ($user->role == UserRole::Customer) {
                return redirect()->route("home");
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route("auth.login");
            }
        }

        return back()->withErrors([
            'email' => 'Invalid Credentials'
        ])->onlyInput('email');
    }

    public function updatePassword(Request $request) {
        $request->validate([
            "current_password" => ['required', 'string'],
            'new_password' => ["required", "string", "min:8", "confirmed"]
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if(!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password lama yang Anda masukkan salah.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with("success", "Password anda berhasil di ubah.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    public function otpVerified(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|digits:4',
        ]);

        $otpData = Otp::where('user_id', $request->user_id)
            ->where('code', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpData) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa');
        }

        $user = User::find($request->user_id);
        $user->is_email_verified = true;
        $user->save();

        $otpData->delete();

        if ($user->role === UserRole::Admin) {
            return redirect()->intended(route("dashboard"));
        } else if ($user->role === UserRole::Customer) {
            return redirect()->intended(route("home"))->with('message', 'Akun berhasil di verifikasi');;
        } else {
            return redirect()->intended(route("home"))->with('message', 'Akun berhasil di verifikasi');;
        }
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->is_email_verified) {
            switch ($user->role) {
                case UserRole::Admin:
                    return redirect()->route('dashboard')->with('error', 'Akun Anda sudah diverifikasi.');
                    break;
                case UserRole::Customer:
                    return redirect()->route('home')->with('error', 'Akun Anda sudah diverifikasi.');
                    break;
                default:
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('auth.login');
            }
        }

        $existingOtp = Otp::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingOtp) {
            return redirect()->route('auth.otp')->with('error', 'OTP sudah dikirim. Silakan tunggu beberapa menit sebelum meminta ulang.');
        }

        $otp = rand(1000, 9999);

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code' => $otp,
                'expires_at' => now()->addMinutes(5)
            ]
        );

        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return redirect()->route('auth.otp')->with('success', 'Kode OTP baru telah dikirim.');
    }
}
