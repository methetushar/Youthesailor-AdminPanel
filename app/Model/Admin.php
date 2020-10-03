<?php

namespace App\Model;

use App\Model\Menu\Permission;
use App\Model\Menu\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard  = 'admin';

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($admin) {
    //         $admin->password = Hash::make($admin->password);
    //     });
    // }

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role_id',
        'profile',
        'mobile',
        'address',
        'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeGetAdmins()
    {
        return Admin::where('status', 'a')->orderBy('id', 'asc')->get();
    }
    public function rolename()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class)->with('permission')->withDefault();
    }
    public function permisson()
    {
        return $this->belongsTo(Permission::class, 'role_id', 'role_id')->withDefault();
    }
    public function getProfileAttribute($value)
    {
        if (!empty($value)) {
            return url('/') . "/public/storage/" . $value;
        }
        return null;
    }
    public function setPasswordAttribute($val)
    {
        $this->attributes['password'] = Hash::make($val);
    }
}
