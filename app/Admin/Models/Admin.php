<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\SmsAuth;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Notifications\Notifiable;

class Admin extends Administrator
{
    use HasApiTokens, Notifiable;
    
    protected $fillable = ['username', 'password', 'name', 'avatar', 'email', 'phone'];
    
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
