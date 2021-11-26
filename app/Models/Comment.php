<?php

namespace NewProject\App\Models;

use NewProject\App\Models\Model;
use NewProject\Framework\Support\Str;

class Comment extends Model
{
    protected $table = 'nihar_task_activities';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'task_id',
        'parent_id',
        'user_id',
        'message',
        'status'
    ];

    public function task()
    {
        $model = __NAMESPACE__ . '\Task';
        return $this->belongsTo(
            $model,
            'task_id',
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
    public function replies()
    {
        $model = __NAMESPACE__ . '\Comment';
        return $this->hasMany(
            $model,
            'parant_id',
            'id'
        );
    }
}
