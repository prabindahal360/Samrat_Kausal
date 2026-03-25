<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->foreignId('customer_ref_id')->constrained('customers')->cascadeOnDelete();
            $table->string('stock_code')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->dateTime('invoice_date')->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
