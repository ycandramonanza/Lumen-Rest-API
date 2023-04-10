<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;


class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Uuids;
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'user_id', 'name', 'email', 'password','api_token'
    ]; 
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password','api_token'
    ];

    // Mutators
    public function setPasswordAttribute(string $input): void
    {
        $this->attributes['password'] = Hash::make($input);
        
    }
}
