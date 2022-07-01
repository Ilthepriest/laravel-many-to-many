<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'slug', 'cover_image', 'category_id', 'user_id'];

    //logica generazione slug  
    public static function generateSlug($title){
        return Str::slug($title, '-');            
    }

    //un post appartiene da una categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    //un post appartiene a molti tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    //un post appartiene ad un utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

