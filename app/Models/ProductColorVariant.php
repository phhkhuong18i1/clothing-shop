<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductColorVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'color_hex', 'image_path', 'sort_order', 'show_on_home'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'show_on_home' => 'boolean'];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) return null;

        return Str::startsWith($this->image_path, ['https://', 'http://'])
            ? $this->image_path
            : Storage::disk('public')->url($this->image_path);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
