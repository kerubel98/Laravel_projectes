<?php

use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Models\Job;
use Laravel\Socialite\Facades\Socialite;


Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/task', function () {
    $tasks = Task::all();

    return view('tasks', ['tasks' => $tasks]);
});
Route::get('/task/{id}', function ($id) {
    $task = Task::find($id);
    return view('task', ['task' => $task]);
});

Route::get('/jobs', function ()  {
    return view('jobs', ['jobs'=>Job::all()]);
});

Route::get('/jobs/{id}', function ($id)  {
        $jobs = Job::find($id);
        return view('job', ['job'=>$jobs]);
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');
