<?php

namespace NewProject\App\Http\Controllers;

use NewProject\App\Models\Comment;
use NewProject\App\Models\Task;
use NewProject\App\Rules\CommentRule;
use NewProject\App\Rules\TaskRule;
use NewProject\App\Traits\ValidatorTrait;
use NewProject\Framework\Request\Request;

class CommentController extends Controller
{
    use ValidatorTrait;
    public function index(Request $request)
    {

        $comments = Comment::orderBy('id', 'DESC');
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $comments = $comments->where(function ($query) use ($search) {
                $query->where('message', 'LIKE', "'%$search%");
            });
        }

        if ($request->has('task') && $request->task != '') {
            $task = $request->task;
            $comments = $comments->where('task_id', $task);
        }
        if ($request->has('user') && $request->user != '') {
            $user = $request->user;
            $comments = $comments->where('user_id', $user);
        }

        $comments = $comments->with(['user', 'task'])->paginate();

        foreach ($comments as $key => $comment) {
            if ($comment->parent_id == 0) {
                $comments[$key]->total_replies = 0;
            } else {
                $comments[$key]->total_replies = $comment->replies->count();
            }
        }

        $comments = apply_filters('new-project/comments', $comments);

        return [
            'comments' => $comments
        ];
    }

    public function save(Request $request)
    {
        $rules = CommentRule::$rules;
        $messages = CommentRule::$messages;
        $this->validateData($request->all(), $rules, $messages);

        $data['message'] = $request->message;
        $data['task_id'] = $request->task;
        $data['parent_id'] = $request->parent;
        $data['user_id'] = $request->user;
        $data = wp_unslash($data);
        $comment = Comment::create($data);
        return [
            'message' => __('Comment has been successfully created', 'fluent-crod'),
            'comment' => $comment
        ];
    }

    public function get($id)
    {
        $task = Task::with(['replies', 'user', 'task'])->findOrFail($id);
        return [
            'task' => $task
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $rules = TaskRule::$rules;
        $messages = TaskRule::$messages;
        $this->validateData($data, $rules, $messages);

        $task = Task::findOrFail($id);
        $task->fill([
            'name' => $data['name'],
            'project_id' => $data['project'],
            'assign_to' => $data['user'],
            'assign_by' => 1,
            'status' => $data['status'],
        ]);
        $task->save();
        do_action('new-project/after_task_created', $task);

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
