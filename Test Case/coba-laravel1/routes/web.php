<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\DashboardCategoriesController;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\DashboardPostController;
use App\Http\Controllers\DashboardProjectController;
use App\Http\Controllers\DashboardUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home',[
        "title"=>"Home",
        "active"=>"home"
    ]);
});
Route::get('/about', function () {
    return view('about',[
        "title"=>"About",
        'active'=>'about',
        "nama"=> "Dicky Kurniawan",
        "email" => "kiddi106@yahoo.com",
        "gambar" => "dicky.jpeg"
    ]);
});


Route::get('/post', [PostController::class,'index']);
// halaman single post
Route::get('post/{post:slug}', [PostController::class,'show']);
// halaman Categories
Route::get('/categories',function(){
    return view('categories',[
        'title'=> 'post-categories',
        'active'=>'categories',
        'categories'=> Category::all()
    ]);
});
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/logout', [LoginController::class, 'logout']);


// halaman Category
Route::get('/register', [RegisterController::class, 'index'])->middleware('is_admin');
Route::post('/register', [RegisterController::class, 'store']);
Route::resource('/dashboard/users', DashboardUserController::class);


Route::get('/dashboard', function(){
    return view('dashboard.index');
})->middleware('auth');

Route::resource('/dashboard/posts', DashboardPostController::class)->middleware('auth');
Route::resource('/dashboard/project', DashboardProjectController::class)->middleware('auth');
// menonaktifkan show
Route::resource('/dashboard/categories', AdminCategoryController::class)->except('show');




// Route::get('/categories/{category:slug}', function(Category $category){
    //     return view('post',[
//         'title'=> "Post By Category : $category->name",
//         'active'=>'categories',
//         // penggunaan lazy
//         'posts'=> $category->posts->load('category','user')
        
//     ]);

// });

// Route::get('/authors/{author:username}',function(User $author){
//     return view('post',[
//         'title'=> "Post By Author : $author->name",
//         'active'=>'categories',
//         'posts'=> $author->post->load('category','user')
//     ]);
// });




