<?php

namespace NewProject\App\Http\Controllers;

use NewProject\App\Models\Task;
use NewProject\App\Models\User;
use NewProject\Framework\Request\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::orderBy('id', 'DESC');
        if ($request->has('dropdown') && $request->dropdown) {
            return [
                'users' => $users->get()
            ];
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $users = $users->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "'%$search%")
                    ->orWhere('last_name', 'LIKE', "'$search'");
            });
        }

        $users = $users->paginate();
        foreach ($users as $user) {
            $user->total_tasks = $user->tasks()->count();
            $user->total_projects = $user->projects()->count();
        }
        return [
            'users' => $users
        ];
    }

    public function save(Request $request)
    {
        $this->validate($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

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

    public function get($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->tasks = Task::where('user_id', $id)
                ->orderBy('id', 'DESC')
                ->paginate();
        }
        return [
            'user' => $user
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->validate($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

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
