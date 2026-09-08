<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('date');
            $table->string('status')->default('DRAFT'); // e.g. DRAFT, SENT, APPROVED
            $table->enum('paid_status', ['UNPAID', 'PAID', 'PARTIALLY_PAID'])->default('UNPAID');
            $table->string('invoice_number')->unique();
            $table->decimal('amount_due', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payables');
    }
};