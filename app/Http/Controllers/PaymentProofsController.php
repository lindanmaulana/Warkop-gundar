<?php

namespace App\Http\Controllers;

use App\Models\PaymentProofs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentProofsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $request->merge([
            'verified' => $request->has('is_active') ? $request->input('is_active') : 0
        ]);

        $request->validate([
            'order_id' => 'required|string|exists:orders,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'verified' => 'required|boolean'
        ]);

        $imagePath = null;

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');

            $fileName = 'payment_proofs_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            $imagePath = $image->storeAs('payments_proofs', $fileName, 'public');
        }

        $paymentProofs = new PaymentProofs();
        $paymentProofs->order_id = $request->order_id;
        $paymentProofs->image_url = $imagePath;
        $paymentProofs->verified = $request->verified;
        $paymentProofs->save();

        return redirect()->route('home.order')->with('success', 'Bukti Pembayaran berhasil di kirim.');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
