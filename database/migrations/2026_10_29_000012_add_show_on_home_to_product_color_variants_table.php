<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_color_variants', function (Blueprint $table): void {
            $table->boolean('show_on_home')->default(true)->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('product_color_variants', function (Blueprint $table): void {
            $table->dropColumn('show_on_home');
        });
    }
};
