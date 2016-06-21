<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

class TaskController extends Controller
{
    public function index()
    {
    	$notifications = Auth::user()->tasks()->select('id', 'name', 'priority', 'description', 'due AS date', 'created_at AS date_created')->where('completed', 0)->where('due', date('Y-m-d'))->orderBy('created_at', 'DESC')->take(10)->get();

    	$tasks = Auth::user()->tasks()->select('id', 'name', 'priority', 'description', 'due AS date', 'created_at AS date_created', 'completed')->orderBy('due')->get();

    	$page = 'tasks';

        return view('tasks.index', compact('notifications', 'tasks', 'page'));
    }
}
