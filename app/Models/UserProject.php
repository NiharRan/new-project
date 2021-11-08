<?php

namespace NewProject\App\Models;

use FluentSupport\Framework\Support\Str;
use NewProject\App\Models\Model;

class UserProject extends Model
{
    protected $table = 'nihar_user_project';

    protected $fillable = [
        'user_id',
        'project_id',
    ];
}
