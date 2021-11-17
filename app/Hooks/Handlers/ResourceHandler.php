<?php

namespace NewProject\App\Hooks\Handlers;

use NewProject\App\Models\User;

class ResourceHandler
{
    /**
     * @param $columns || list of columns need to send from server
     * @param $filters || list of filters need to apply on this action
     */
    public function getUserList($columns = [], $filters = [])
    {
        $data = User::orderBy('id', "DESC");
        if (count($columns) > 0) {
            $data = $data->select($columns);
        }
        $data = $data->get();

        if (count($filters) > 0) {
            foreach ($filters as $filter) {
                $data = apply_filters($filter, $data);
            }
        }
        return $data;
    }
}
