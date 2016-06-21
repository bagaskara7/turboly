<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use DB;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
    	$notifications = Auth::user()->tasks()->select('id', 'name', 'priority', 'description', 'due AS date', 'created_at AS date_created')->where('completed', 0)->where('due', date('Y-m-d'))->orderBy('created_at', 'DESC')->take(10)->get();

    	$today_tasks = Auth::user()->tasks()->select('id', 'name', 'priority', 'description', 'due AS date', 'created_at AS date_created')->where('completed', 0)->where('due', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();

    	$chart = Auth::user()->tasks()->select(DB::raw('count(*) AS total'), DB::raw('DATE(completed_at) AS completed_at'))->where('completed', 1)->groupBy(DB::raw('DATE(completed_at)'))->orderBy('completed_at')->take(10)->get();

    	$page = 'dashboard';

    	return view('dashboard', compact('notifications', 'today_tasks', 'chart', 'page'));
    }
}
