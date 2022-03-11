<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $guarded = [];

    public function media(): BelongsTo
    {
        return $this->belongsTo('App\Models\Media');
    }
}
