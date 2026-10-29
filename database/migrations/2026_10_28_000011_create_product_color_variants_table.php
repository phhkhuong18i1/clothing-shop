<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_color_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['product_id', 'sort_order']);
        });

        DB::table('products')->select(['id', 'colors'])->orderBy('id')->get()->each(function ($product): void {
            $colors = json_decode($product->colors ?? '[]', true);
            if (! is_array($colors)) return;

            foreach (array_values($colors) as $index => $name) {
                if (! is_string($name) || trim($name) === '') continue;
                DB::table('product_color_variants')->insert([
                    'product_id' => $product->id,
                    'name' => mb_substr(trim($name), 0, 80),
                    'image_path' => null,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_color_variants');
    }
};
