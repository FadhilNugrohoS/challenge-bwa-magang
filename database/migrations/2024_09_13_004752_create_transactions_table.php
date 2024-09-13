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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->CascadeOnDelete();
            $table->string('phone_number');
            $table->string('transaction_trx');
            $table->foreignId('holiday_package_id')->constrained()->CascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->CascadeOnDelete();
            $table->unsignedBigInteger('total_amount');
            $table->unsignedBigInteger('duration');
            $table->date('transaction_date');
            $table->boolean('is_paid')->default(false);
            $table->softDeletes();
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
