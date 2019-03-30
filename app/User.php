<?php

namespace Turing;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Turing\Models\ShoppingCart;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;


    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected $table = 'customer';

    /**
     * @inheritdoc
     */
    protected $primaryKey = 'customer_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function shoppingCarts()
    {
        return $this->hasMany(ShoppingCart::class, 'customer_id', 'customer_id');
    }

    public function delete()
    {
        $this->shoppingCarts()->delete();
        parent::delete();
    }

}
