<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('type', ['sale', 'exchange'])->default('sale')->after('status');
            $table->foreignId('original_sale_id')->nullable()->after('type')->constrained('sales')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['original_sale_id']);
            $table->dropColumn(['type', 'original_sale_id']);
        });
    }
};