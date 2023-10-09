<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    public $translatable = ['name'];

    public static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'category_id');
    }

    public function cases(): HasMany
    {
        return $this->hasMany(Cases::class, 'category_id');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }
}
