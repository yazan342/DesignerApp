<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'size',
    ];


    /**
     * The designs that belong to the Size
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(Design::class, 'design_sizes', 'size_id', 'design_id');
    }
}
