<?php

namespace NewProject\App\Models;

use FluentSupport\Framework\Support\Str;
use NewProject\App\Models\Model;

class User extends Model
{
    protected $table = 'nihar_users';

    protected $fillable = [
        'first_name',
        'last_name',
        'avatar',
        'slug',
        'email',
        'phone',
        'status'
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug("$model->first_name $model->last_name");
        });
    }

    public function getLinkAttribute()
    {
        return "/users/$this->slug";
    }

    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }
    protected $appends = ['link', 'full_name'];


    public function assigned()
    {
        $model = __NAMESPACE__ . '\Task';
        return $this->hasMany(
            $model,
            'assign_to',
            'id'
        );
    }

    public function assigned_by()
    {
        $model = __NAMESPACE__ . '\Task';
        return $this->hasMany(
            $model,
            'assign_by',
            'id'
        );
    }

    public function projects()
    {
        $model = __NAMESPACE__ . '\Project';
        return $this->belongsToMany(
            $model,
            'nihar_user_project',
            'user_id',
            'project_id',
        )->withTimestamps();
    }
}
