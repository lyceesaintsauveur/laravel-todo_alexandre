<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todos extends Model
{
    use SoftDeletes;

    protected $fillable = ['texte', 'termine', 'important', 'listes_id', 'user_id', 'date_fin'];

    protected $casts = [
        'date_fin' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter todos for a given user id.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter todos for the currently authenticated user.
     */
    public function scopeForAuth($query)
    {
        return $query->where('user_id', auth()->id());
    }

    // Optionnel mais recommandé si tu veux accéder à deleted_at comme un objet Carbon
    // protected $dates = ['deleted_at'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categories::class, 'categories_todos', 'todos_id', 'categories_id');
    }

    public function listes(): BelongsTo
    {
        return $this->belongsTo(Listes::class)->withDefault();
    }
}
