<?php

namespace NewProject\App\Http\Controllers;

use NewProject\App\Models\Task;
use NewProject\Framework\Request\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {

        $tasks = Task::orderBy('id', 'DESC');
        if ($request->has('dropdown') && $request->dropdown) {
            return [
                'tasks' => $tasks->get()
            ];
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $tasks = $tasks->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "'%$search%");
            });
        }

        if ($request->has('project') && $request->project != '') {
            $project = $request->project;
            $tasks = $tasks->where('project_id', "'%$project%");
        }
        if ($request->has('to') && $request->to != '') {
            $to = $request->to;
            $tasks = $tasks->where('assign_to', "'%$to%");
        }
        if ($request->has('by') && $request->by != '') {
            $by = $request->by;
            $tasks = $tasks->where('assign_by', "'%$by%");
        }

        $tasks = $tasks->with(['project', 'to'])->paginate();
        return [
            'tasks' => $tasks
        ];
    }

    public function save(Request $request)
    {
        $this->validate($request->all(), [
            'name' => 'required',
            'project' => 'required',
            'user' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['project_id'] = $request->project;
        $data['assign_to'] = $request->user;
        $data['assign_by'] = 1;
        $data = wp_unslash($data);
        $task = Task::create($data);

        return [
            'message' => __('Task has been successfully created', 'fluent-crod'),
            'task' => $task
        ];
    }

    public function get($id)
    {
        $task = Task::findOrFail($id);
        return [
            'task' => $task
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->validate($data, [
            'name' => 'required',
            'project' => 'required',
            'user' => 'required',
        ]);

        $task = Task::findOrFail($id);
        $task->fill([
            'name' => $data['name'],
            'project_id' => $data['project'],
            'assign_to' => $data['user'],
            'assign_by' => 1,
            'status' => $data['status'],
        ]);
        $task->save();

        return [
            'message' => __('Task has been updated', 'fluent-crod'),
            'task' => Task::find($id)
        ];
    }

    public function destroy($id)
    {
        Task::where('id', $id)
            ->delete();

        return [
            'message' => __('Task has been deleted', 'fluent-crod')
        ];
    }
}
