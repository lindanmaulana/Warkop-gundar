<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\Otp;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $userData = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
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

            return redirect()->intended(route("dashboard"));
        }

        return back()->withErrors([
            'email' => 'Invalid Credentials'
        ])->onlyInput('email');
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
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa']);
        }

        $user = User::find($request->user_id);
        $user->is_email_verified = true;
        $user->save();

        $otpData->delete();

        return redirect()->route('dashboard')->with('message', 'Akun berhasil di verifikasi');
    }
}
