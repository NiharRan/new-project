<?php

namespace NewProject\App\Http\Controllers;

use NewProject\App\Models\Project;
use NewProject\App\Models\Task;
use NewProject\Framework\Request\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {

        $projects = Project::orderBy('id', 'DESC');
        if ($request->has('dropdown') && $request->dropdown) {
            return [
                'projects' => $projects->get()
            ];
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $projects = $projects->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "'%$search%");
            });
        }

        $projects = $projects->with(['users'])->paginate();
        foreach ($projects as $project) {
            $project->total_tasks = $project->tasks()->count();
        }
        return [
            'projects' => $projects
        ];
    }

    public function save(Request $request)
    {
        $this->validate($request->all(), [
            'name' => 'required'
        ]);

        $data['name'] = $request->name;
        $data = wp_unslash($data);
        $project = Project::create($data);

        if ($project && count($request->users) > 0) {
            $project->users()->sync($request->users);
        }

        return [
            'message' => __('Project has been successfully created', 'fluent-crod'),
            'project' => $project
        ];
    }

    public function get($slug)
    {
        $project = Project::where('slug', $slug)->first();
        if ($project) {
            $project->tasks = Task::with(['to', 'by'])->where('project_id', $project->id)
                ->orderBy('id', 'DESC')
                ->paginate();
        }
        return [
            'project' => $project
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->validate($data, [
            'name' => 'required',
            'users' => 'required|array'
        ]);

        $project = Project::findOrFail($id);
        $project->fill([
            'name' => $data['name'],
            'status' => $data['status'],
        ]);
        $project->save();

        if (count($request->users) > 0) {
            $project->users()->sync($request->users);
        }

        return [
            'message' => __('Project has been updated', 'fluent-crod'),
            'project' => Project::find($id)
        ];
    }

    public function destroy($id)
    {
        Project::where('id', $id)
            ->delete();

        return [
            'message' => __('Project has been deleted', 'fluent-crod')
        ];
    }

    public function users($id)
    {
        $users = Project::find($id)->users()->get();
        return [
            'users' => $users
        ];
    }
}
