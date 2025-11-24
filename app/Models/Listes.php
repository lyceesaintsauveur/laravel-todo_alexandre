<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Listes extends Model
{
    public $timestamps = false;
    protected $fillable = ['libelle'];

    public function todos(): HasMany
    {
        return $this->hasMany(Todos::class);
    }
}