<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Treatment extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    public $translatable = ['name'];

    public function category_parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault();
    }

    public function category_child(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'child_category_id')->withDefault([
            'name' => '-'
        ]);
    }
}
