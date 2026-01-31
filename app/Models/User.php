<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Contracts\Auth\MustVerifyEmail;   

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;

    const ADMIN_ROLE = 'admin';
    const EDITOR_ROLE = 'editor';
    const USER_ROLE = 'user';

    // Metoda za određivanje da li je korisnik admin
    public function isAdmin()
    {
        return $this->role === self::ADMIN_ROLE;
    }

    // Metoda za određivanje da li je korisnik editor
    public function isEditor()
    {
        return $this->role === self::EDITOR_ROLE;
    }

    // Metoda za određivanje da li je korisnik regularni korisnik
    public function isUser()
    {
        return $this->role === self::USER_ROLE;
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

     public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Ako želiš da dodaš vezu sa recenzijama (reviews)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
