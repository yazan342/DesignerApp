<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'hex',
    ];


    /**
     * The designs that belong to the Color
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(Design::class, 'design_colors', 'color_id', 'design_id');
    }
}
