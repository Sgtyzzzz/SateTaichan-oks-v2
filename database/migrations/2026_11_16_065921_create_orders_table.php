<?php

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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_code')->unique();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('customer_name');
        $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
        $table->enum('order_type', ['dine_in', 'take_away', 'offline'])->default('dine_in');
        $table->integer('subtotal');
        $table->integer('service_fee')->default(2000);
        $table->integer('total_price');
        $table->string('payment_method')->nullable(); // GoPay, OVO, Dana, BCA VA, Tunai
        $table->enum('payment_status', ['unpaid', 'pending', 'paid'])->default('unpaid');
        $table->enum('order_status', ['menunggu', 'diproses', 'selesai', 'batal'])->default('menunggu');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
