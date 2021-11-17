<?php

namespace NewProject\App\Models;

use NewProject\App\Models\Model;
use NewProject\Framework\Support\Str;

class Task extends Model
{
    protected $table = 'nihar_tasks';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'project_id',
        'assign_to',
        'assign_by',
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
        return "/tasks/$this->slug";
    }

    public function project()
    {
        $model = __NAMESPACE__ . '\Project';
        return $this->belongsTo(
            $model,
            'project_id',
            'id'
        );
    }

    public function to()
    {
        $model = __NAMESPACE__ . '\User';
        return $this->belongsTo(
            $model,
            'assign_to',
            'id'
        );
    }
    public function by()
    {
        $model = __NAMESPACE__ . '\User';
        return $this->belongsTo(
            $model,
            'assign_by',
            'id'
        );
    }
}
