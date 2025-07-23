<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('/dashboard/user/index', compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->is_suspended) {
            return redirect()->back()->with('error', "Status Akun tidak aktif tidak bisa diubah.");
        }

        return view('dashboard/user/update', compact('user'));
    }

    public function suspendAccount(Request $request, User $user)
    {

        $validatedData = $request->validate([
            'is_suspended' => "boolean"
        ]);

        $user->update([
            "is_suspended" => $validatedData['is_suspended']
        ]);

        return redirect()->route('dashboard.users', ['page' => 1, 'limit' => 5])->with("success", "Status akun berhasil di ubah.");
    }

    /**
     * Update the specified resource in storage.
     */

    public function updateBySuperadmin(Request $request, User $user)
    {
        $userLogin = Auth::user();

        if ($userLogin->role != UserRole::Superadmin) return redirect()->route("dashboard.users", ['page' => 1, 'limit' => 5])->with("error", "Unauthorized");

        $validatedData = $request->validate([
            'role' => ['required', new Enum(UserRole::class)]
        ]);

        if($validatedData['role'] == UserRole::Superadmin->value) return redirect()->route("dashboard.users", ['page' => 1, 'limit' => 5])->with("error", "Unauthorized");

        $user->update($validatedData);

        return redirect()->route("dashboard.users", ["page" => 1, "limit" => 5])->with("success", "Akun berhasil di ubah.");
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $user->update($validatedData);

        return redirect()->route('dashboard.setting')->with('success', 'Update profile berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getAllUser(Request $request)
    {
        $queryKeyword = $request->query("keyword");
        $queryPage = $request->query('page');
        $queryLimit = $request->query('limit');

        $page = max(1, (int)$queryPage);
        $limit = max(1, (int)$queryLimit);

        if ($limit > 20) $limit = 5;

        $users = User::when($queryKeyword, function ($query) use ($queryKeyword) {
            $query->where("name", "like", "%{$queryKeyword}%");
        })
            ->paginate($limit);

        $users->getCollection()->makeHidden('password', 'remember_token');

        return response()->json([
            'message' => "Data user berhasil di ambil",
            'data' => $users
        ]);
    }
}
