<?php

namespace NewProject\App\Http\Controllers;

use NewProject\App\Models\User;
use NewProject\App\Rules\UserRule;
use NewProject\App\Traits\ValidatorTrait;
use NewProject\Framework\Request\Request;

class UserController extends Controller
{
    use ValidatorTrait;

    public function index(Request $request)
    {
        $default_fields = explode(',', $request->default_fields);
        if ($request->has('fields')) {
            $fields = explode(',', $request->fields);
            $default_fields = array_merge($default_fields, $fields);
        }

        $users = User::orderBy('id', 'DESC');
        if ($request->has('dropdown') && $request->dropdown) {
            $users = $users->get();
            $users = apply_filters('new-project/filter_user_data', $users);
            return [
                'users' => $users
            ];
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $users = $users->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "'%$search%")
                    ->orWhere('last_name', 'LIKE', "'$search'");
            });
        }

        $users = $users->select($default_fields)->paginate();
        foreach ($users as $key => $user) {
            $users[$key]->total_assigned_tasks = $user->assigned()->count();
            $users[$key]->total_assigned_by_tasks = $user->assigned_by()->count();
            $users[$key]->total_projects = $user->projects()->count();
        }

        $users = apply_filters('new-project/filter_user_data', $users);

        return [
            'users' => $users
        ];
    }

    public function save(Request $request)
    {
        $rules = UserRule::$rules;
        $messages = UserRule::$messages;
        $this->validateData($request->all(), $rules, $messages);

        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data = wp_unslash($data);
        $user = User::create($data);

        return [
            'message' => __('user has been successfully created', 'fluent-crod'),
            'user' => $user
        ];
    }

    public function get($slug)
    {
        $user = User::with(['projects'])->where('slug', $slug)->first();
        if ($user) {
            foreach ($user->projects as $key => $project) {
                $user->projects[$key]->total_tasks = $user->assigned()
                    ->where('project_id', $project->id)
                    ->count();
            }
        }
        return [
            'user' => $user
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $rules = UserRule::$rules;
        $messages = UserRule::$messages;
        $this->validateData($data, $rules, $messages);


        $user = User::findOrFail($id);
        $user->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'status' => $data['status'],
        ]);
        $user->save();

        return [
            'message' => __('User has been updated', 'fluent-crod'),
            'user' => User::find($id)
        ];
    }

    public function destroy($id)
    {
        User::where('id', $id)
            ->delete();

        return [
            'message' => __('User has been deleted', 'fluent-crod')
        ];
    }
}
