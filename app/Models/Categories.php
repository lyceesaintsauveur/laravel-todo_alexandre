<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categories extends Model
{
    protected $fillable = ['libelle'];

    public function todos(): BelongsToMany
    {
        return $this->belongsToMany(Todos::class, 'categories_todos', 'categories_id', 'todos_id');
    }
}