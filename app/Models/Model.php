<?php

namespace NewProject\App\Models;

use NewProject\Framework\Database\Orm\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = ['id', 'ID'];
}