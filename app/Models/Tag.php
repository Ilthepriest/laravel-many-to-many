<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{

    protected $fillable = ['name', 'slug'];

    // un tag appartiene a molti post
    public function posts(): BelongsToMany{
        return $this->belongsToMany(Post::class);
    }
}
