<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Shetabit\Visitor\Traits\Visitor;
use Webkul\Marketplace\Contracts\Seller as SellerContract;
use Webkul\Marketplace\Mail\ResetPasswordNotification;

class Seller extends Authenticatable implements SellerContract
{
    use HasApiTokens, HasFactory, Notifiable, Visitor;

    protected $table = 'marketplace_sellers';

    protected $casts = [
        'allowed_product_types' => 'array',
    ];

    protected $guarded = [
        '_token',
        'logo',
        'banner',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get logo image url.
     */
    public function logo_url()
    {
        if (! $this->logo) {
            return;
        }

        return asset('storage/'.$this->logo);
    }

    /**
     * Get logo image url.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_url();
    }

    /**
     * Get banner image url.
     */
    public function banner_url()
    {
        if (! $this->banner) {
            return;
        }

        return asset('storage/'.$this->banner);
    }

    /**
     * Get banner image url.
     */
    public function getBannerUrlAttribute()
    {
        return $this->banner_url();
    }

    /**
     * Get the order products record associated with the seller.
     */
    public function products()
    {
        return $this->hasMany(ProductProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the order reviews record associated with the seller.
     */
    public function reviews()
    {
        return $this->hasMany(ReviewProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the order orders record associated with the seller.
     */
    public function orders()
    {
        return $this->hasMany(OrderProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the order orders record associated with the seller.
     */
    public function categories()
    {
        return $this->hasOne(SellerCategoryProxy::modelClass(), 'seller_id');
    }
}
