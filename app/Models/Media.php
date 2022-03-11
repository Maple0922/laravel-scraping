<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    protected $table = 'medias';

    public function articles(): HasMany
    {
        return $this->hasMany("App\Models\Article");
    }
}
