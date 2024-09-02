<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the designs for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function designs(): HasMany
    {
        return $this->hasMany(Design::class, 'category_id', 'id');
    }
}
