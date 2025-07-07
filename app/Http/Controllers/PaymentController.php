<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $payments = Payment::all();

        return view('dashboard.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.payment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->merge([
            'is_active' => $request->has('is_active') ? $request->input('is_active') : 0
        ]);

        $request->validate([
            'name' => 'required|string',
            'qr_code_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'required|boolean'
        ]);

        $imagePath = null;

        if ($request->hasFile('qr_code_url')) {
            $image = $request->file('qr_code_url');

            $fileName = 'payment_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            $imagePath = $image->storeAs('payments', $fileName, 'public');
        }

        $payment = new Payment();
        $payment->name = $request->name;
        $payment->qr_code_url = $imagePath;
        $payment->is_active = $request->is_active;
        $payment->save();

        return redirect()->route('dashboard.payments')->with('message', 'Tipe Pembayaran berhasil di buat.');
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
    public function edit(Payment $payment)
    {
        return view('dashboard.payment.update', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? $request->input('is_active') : 0
        ]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Tambahkan max length
            'qr_code_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'required|boolean'
        ]);

        $imagePath = $payment->qr_code_url;

        if ($request->hasFile('qr_code_url')) {
            if ($payment->qr_code_url && Storage::disk('public')->exists($payment->qr_code_url)) {
                Storage::disk('public')->delete($payment->qr_code_url);
            }

            $image = $request->file('qr_code_url');
            $fileName = 'payment_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('payments', $fileName, 'public');
        } elseif ($request->boolean('delete_qr_code_url')) {
            if ($payment->qr_code_url && Storage::disk('public')->exists($payment->qr_code_url)) {
                Storage::disk('public')->delete($payment->qr_code_url);
            }
            $imagePath = null;
        }


        $payment->name = $validatedData['name'];
        $payment->qr_code_url = $imagePath;
        $payment->is_active = $validatedData['is_active'];
        $payment->save();

        return redirect()->route('dashboard.payments')->with('message', 'Tipe Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        if ($payment->qr_code_url && Storage::disk('public')->exists($payment->qr_code_url)) {
            Storage::disk('public')->delete($payment->qr_code_url);
        }

        $payment->delete();

        return redirect()->route('dashboard.payments')->with('message', 'Tipe Pembayaran berhasil dihapus.');
    }
}
