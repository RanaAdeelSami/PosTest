<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('purchase_item_id')->nullable()->constrained()->nullOnDelete();

            $table->string('batch_number');              // BATCH-YYYYMMDD-XXXX
            $table->decimal('unit_cost', 12, 2);          // is batch ka cost
            $table->integer('qty_in');                    // kitna aaya
            $table->integer('qty_remaining');             // kitna bacha
            $table->date('received_date');
            $table->string('supplier_invoice_no')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['product_id', 'qty_remaining']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};