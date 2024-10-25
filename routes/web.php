<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Middleware\EnsureUserIsAuthor;
use App\Http\Requests\StoreBlogRequest;
use App\Mail\BlogPosted;
use App\Models\Blog;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;


// mail testing preview
// Route::get('/test', function() {
//     gives the preview just, does not send the mail
//     return new BlogPosted();

//     Mail::to('ishwarashid933@gmail.com')->send(new BlogPosted());
//     return 'done';
// });



Route::get('/test', function () {
    dispatch(function () {
        logger('hello from the queue');
    });
    return 'done';
});

// auth
Route::get('/', function () {
    return view('auth.index');
});

// blog
Route::controller(BlogController::class)->middleware(['auth'])->group(function () {
    Route::get('/blogs', 'index');
    Route::post('/blogs', 'store');
    Route::get('/blogs/create', 'create');
    Route::get('/blogs/{id}', 'show');
    Route::get('user/blogs', 'userBlogs');

    Route::middleware(EnsureUserIsAuthor::class)->group(function () {
        Route::get('/blogs/{id}/edit', 'edit');
        Route::patch('/blogs/{id}', 'update');
        Route::delete('/blogs/{id}', 'destroy');
    });
});

// register
Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});

// login
Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create');
    Route::post('/login', 'store')->name('login');
    Route::post('/logout', 'destroy');
});


// Route::get('test', function () {
//     $result = cloudinary()->uploadApi()->destroy('blog-uploads/n8f8mgrkdlbizjlwwavc',  ['invalidate' => true]);
//     dd($result['result']);
// });
