<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    public function create()
    {
        //
    }

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string'
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

    public function getAllUser(Request $request) {
        $queryPage = $request->query('page');
        $queryLimit = $request->query('limit');

        $page = max(1, (int)$queryPage);
        $limit = max(1, (int)$queryLimit);

        if($limit > 20) $limit = 5;

        $users = User::paginate($limit);
        $users->getCollection()->makeHidden('email', 'password', 'remember_token');

        return response()->json([
            'message' => "Data user berhasil di ambil",
            'data' => $users
        ]);
    }
}
