<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     
     const SUPERADMIN = 0;
     const ADMIN = 1;
     const USERS = 2;
     const SHOPS = 3;
          
    protected $guarded = [];

    protected $table ="users";

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'user_mobile'; // Use user_mobile instead of default 'id'
    }

    /**
     * Get the column name for the "username" used for authentication.
     *
     * @return string
     */
    public function username()
    {
        return 'user_mobile';
    } 
        

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    
    public static function getUserId()
    {
        if(auth()->check()){
            if (Auth::user()->int_role_id == User::USERS) {
                $vendorId = Auth::user()->id;
            } elseif (Auth::user()->int_role_id == User::SHOPS) {
                $vendorId = Auth::user()->parent_id;
            } else {
                $vendorId = Auth::user()->id;
            }
            return $vendorId;
        }else{
            return null;
        }
    }
    public static function getUserIdApi($userId)
    {
        $user = User::select('id','role_id','parent_id')->find($userId);
        if ($user) {
            if ($user->parent_id == NULL && $user->role_id == User::USERS) {
                $vendorId = $userId;
            } elseif ($user->parent_id != NULL && $user->role_id == User::SHOPS) {
                $vendorId = $user->parent_id;
            } else {
                $vendorId = $userId;
            }
            return $vendorId;
        } else {
            return false;
        }
    }
    
    
    public function isSuperAdmin()
    {
        if (Auth::user()->role_id == User::SUPERADMIN) {
            return true;
        }
    }

    public function isAdmin()
    {
        if (Auth::user()->role_id == User::ADMIN) {
            return true;
        }
    }
	
	public function isUser()
    {
        if (Auth::user()->role_id == User::USERS) {
            return true;
        }
    }
	
	public function isShops()
    {
        if (Auth::user()->role_id == User::SHOPS) {
            return true;
        }
    }
  
    public static function getUserName($id)
    {
        $user = User::select('user_name')->find($id);
        if ($user) {
            $username = $user->user_name;
        } else {
            $username = "User were not found.!";
        }
        return $username;
    }
  
    public static function getUserVendorId($id)
    {
        $user = User::select('id', 'role_id', 'user_name', 'email', 'status','parent_id')->find($id);
        if ($user->role_id == User::USERS or $user->role_id == User::SHOPS) {
            $vendorId = $user->id;
        } else {
            $vendorId = $user->id;
        }
        return $vendorId;
    }
		
	public static function totalUserCount()
	{
		return self::count();
	}

	public static function expiredUserCount()
	{
		return self::where('subscription_end_date','<',date('Y-m-d'))->count();
	}

	public static function activeUserCount()
	{
		return self::where('subscription_end_date','>=',date('Y-m-d'))->count();
	}
	   
    
    
}
