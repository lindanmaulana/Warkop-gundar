<?php

use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->string('midtrans_order_id')->unique();
            $table->string('payment_type'); 
            $table->dateTime('transaction_time');
            $table->enum('transaction_status', array_column(TransactionStatus::cases(), 'value'))->default(TransactionStatus::Pending);
            $table->string('fraud_status')->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->string('currency', 10)->default('IDR');
            $table->json('raw_response')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
