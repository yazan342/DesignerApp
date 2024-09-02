<?php

namespace App\Models;

use App\DesignStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Design extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'designer_id',
        'category_id',
        'prepare_duration',
        'image',
        'price',
    ];


    protected $casts = [
        'status' => DesignStatusEnum::class,
    ];


    protected $appends = ['is_reviewed', 'average_rate', 'user_rate'];

    protected $hidden = ['reviews'];



    public function getIsReviewedAttribute(): bool
    {
        $userId = Auth::id();

        return $this->reviews()->where('user_id', $userId)->exists();
    }


    public function getAverageRateAttribute(): float
    {


        $totalRating = $this->reviews->sum('rate');
        $ratingCount = $this->reviews->count();

        return $ratingCount > 0 ? round($totalRating / $ratingCount, 1) : 0.0;
    }



    /**
     * Get the authenticated user's rating for the design.
     *
     * @return float|null
     */
    public function getUserRateAttribute(): ?float
    {
        $userId = Auth::id();

        if ($userId) {
            $userReview = $this->reviews()->where('user_id', $userId)->first();
            return $userReview ? (float) $userReview->rate : 0;
        }

        return 0;
    }



    /**
     * The sizes that belong to the Design
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'design_sizes', 'design_id', 'size_id');
    }

    /**
     * The colors that belong to the Design
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'design_colors', 'design_id', 'color_id');
    }

    /**
     * Get the category that owns the Design
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }


    /**
     * Get all of the reviews for the Design
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'design_id', 'id');
    }


    /**
     * Get all of the orders for the Design
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'design_id', 'id');
    }

/**
     * Get the designer that owns the Design
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'designer_id', 'id');
    }



}
