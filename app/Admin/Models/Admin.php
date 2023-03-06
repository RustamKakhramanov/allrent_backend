<?php

namespace App\Admin\Models;

use App\Traits\SmsAuth;
use App\Models\Company\Company;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Auth\Database\Administrator;

class Admin extends Administrator
{
    use HasApiTokens, Notifiable;
    
    protected $fillable = ['username', 'password', 'name', 'avatar', 'email', 'phone'];
    
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'user_id', 'company_id', 'company_members');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_members', 'user_id');
    }
}
