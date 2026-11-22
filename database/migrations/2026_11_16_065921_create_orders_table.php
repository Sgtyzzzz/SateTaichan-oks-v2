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
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('kasir_id')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
        $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in');
        $table->decimal('total_price', 12, 2);
        $table->enum('status', ['pending', 'confirmed', 'cooking', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'expired'])->default('unpaid');
        $table->string('payment_method')->nullable();
        $table->string('snap_token')->nullable();
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
