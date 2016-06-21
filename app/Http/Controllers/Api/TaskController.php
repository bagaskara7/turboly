<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Task;

use Auth;
use Validator;

class TaskController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Task API Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the CRUD operations of a user's tasks. User
    | can add a new task, update existing task, view existing tasks and also
    | able to delete a task.
    |
    */

    public function index()
    {
    	//
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'priority' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()->all()
            ], 400);
        }

        $date = date('Y-m-d', strtotime($request->get('date')));

        $task = new Task;

        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->priority = $request->get('priority');
        $task->due = $date;
        $task->completed = 0;

        if (Auth::user()->tasks()->save($task)) {
            return response()->json([
                'error' => false,
                'data' => $task,
            ], 201);
        } else {
            return response()->json([
                'error' => 'unknown_error',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'priority' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()->all()
            ], 400);
        }

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'resource_not_found'
            ], 404);
        }

        $date = date('Y-m-d', strtotime($request->get('date')));

        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->priority = $request->get('priority');
        $task->due = $date;

        if (Auth::user()->tasks()->save($task)) {
            return response()->json([
                'error' => false,
                'data' => $task,
            ], 201);
        } else {
            return response()->json([
                'error' => 'unknown_error',
            ], 500);
        }
    }

    public function markAsCompleted($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'resource_not_found'
            ], 404);
        }

        $task->completed = 1;
        $task->completed_at = date('Y-m-d H:i:s');

        if ($task->save()) {
            return response()->json([
                'error' => false,
            ]);
        } else {
            return response()->json([
                'error' => 'unknown_error',
            ], 500);
        }
    }

    public function markAsUncompleted($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'resource_not_found'
            ], 404);
        }

        $task->completed = 0;
        $task->completed_at = null;

        if ($task->save()) {
            return response()->json([
                'error' => false,
            ]);
        } else {
            return response()->json([
                'error' => 'unknown_error',
            ], 500);
        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'resource_not_found'
            ], 404);
        }

        if ($task->delete()) {
            return response()->json([
                'error' => false,
            ]);
        } else {
            return response()->json([
                'error' => true,
            ]);
        }
    }
}
