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
        'user_id',
        'slug',
        'status'
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
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

    public function user()
    {
        $model = __NAMESPACE__ . '\User';
        return $this->belongsTo(
            $model,
            'user_id',
            'id'
        );
    }
}
