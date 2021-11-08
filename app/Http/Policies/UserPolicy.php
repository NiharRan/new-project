<?php

namespace NewProject\App\Http\Policies;

use NewProject\Framework\Request\Request;
use NewProject\Framework\Foundation\Policy;

class UserPolicy extends Policy
{
    /**
     * Check user permission for any method
     * @param  NewProject\Framework\Request\Request $request
     * @return Boolean
     */
    public function verifyRequest(Request $request)
    {
        return current_user_can('manage_options');
    }

    /**
     * Check user permission for any method
     * @param  NewProject\Framework\Request\Request $request
     * @return Boolean
     */
    public function create(Request $request)
    {
        return current_user_can('manage_options');
    }
}
