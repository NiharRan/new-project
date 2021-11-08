<?php

namespace NewProject\App\Models;

use NewProject\App\Models\Model;
use NewProject\Framework\Support\Str;

class Project extends Model
{
    protected $table = 'nihar_projects';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function getLinkAttribute()
    {
        return "projects/$this->slug";
    }

    protected $appends = ['link'];


    public function tasks()
    {
        $model = __NAMESPACE__ . '\Task';
        return $this->hasMany(
            $model,
            'project_id',
            'id'
        );
    }

    public function users()
    {
        $model = __NAMESPACE__ . '\User';
        return $this->belongsToMany(
            $model,
            'nihar_user_project',
            'user_id',
            'project_id',
        )->withTimestamps();
    }
}
