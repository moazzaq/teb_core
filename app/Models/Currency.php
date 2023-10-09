<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Currency extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    public $translatable = ['name'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
