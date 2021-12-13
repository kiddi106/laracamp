<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
class PostController extends Controller
{
    public function index(){
        $title='';
        if(request('category')){
            $category = Category::firstWhere('slug', request('category'));
            $title = ' in ' .$category->name;
        }
        if(request('user')){
            $user = User::firstWhere('username', request('user'));
            $title = ' by ' .$user->name;
        }
        
        return view('post',[
            "title"=> "All Project" . $title,
            "active"=> "post",
            // "posts"=> Post::all()
            "posts"=> Post::latest()->filter(request(['search','category','user']))->paginate(7)->withQueryString()
            
        ]);
    }
    // Contoh Route Model Binding
    public function show(Post $post){
        return view('item',[
            "title"=>"Single Post",
            "active"=>"post",
            "post"=>$post
    
        ]);
    }
}
