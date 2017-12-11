<?php
namespace App\Http\Controllers;
namespace App\Http\Controllers\Post;

use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Session;

class PostsController extends Controller
{
#-------------------------------- Display All Data With Pagination ------------------------------#   
    public function index(){
        $posts = Post::orderBy('created','desc')->get();
        $posts = DB::table('posts')->paginate(3);
        return view('posts.index', ['posts' => $posts]);
    }
#-------------------------------- Display Single data Details  ----------------------------------#  
    public function details($id){
        $post = Post::find($id);
        return view('posts.details', ['post' => $post]);
    }
#-------------------------------- Display Add Form ----------------------------------------------#   
    public function add(){
        return view('posts.add');
    }
#-------------------------------- Insert Data With File Upload ----------------------------------#  
    public function insert(Request $request){
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
			'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
		$fileName="";
		if($request->hasFile ('image')){
			$file = $request->file ('image');
			$ext = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
			$file->move('uploads', $file->getClientOriginalName()); 
		}
		$data = ['title' => $request->title,
				'content' => $request->content,
				'file' => $fileName];
        Post::insert($data);  	
        Session::flash('success_msg', 'Post added successfully!');
        return redirect()->route('posts.index');
    }  
 #-------------------------------- Display edit Form -------------------------------------------#
    public function edit($id){
        $post = Post::find($id);
        return view('posts.edit', ['post' => $post]);
    }
 #-------------------------------- Update Data -------------------------------------------------#  
    public function update($id, Request $request){
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $fileName="";
        if($request->hasFile ('image')){
            $file = $request->file ('image');
            $ext = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
            $file->move('uploads', $file->getClientOriginalName()); 
        }
        $data = ['title' => $request->title,
                'content' => $request->content,
                'file' => $fileName];
        DB::table('posts')->where('id', $id)->update($data);
        Session::flash('success_msg', 'Post updated successfully!');
        return redirect()->route('posts.index');
    } 
 #-------------------------------- Delete ------------------------------------------------------#
    public function delete($id){
        Post::find($id)->delete();
        Session::flash('success_msg', 'Post deleted successfully!');
        return redirect()->route('posts.index');
    }
 #-------------------------------- Search With Pagination --------------------------------------#
	 public function search(){	    
		$q = Input::get ( 'q' );
		$posts = DB::table('posts')
            ->WHERE ('title', 'LIKE', '%' . $q . '%')->orWhere ('content', 'LIKE', '%' . $q . '%')
            ->paginate(3); 
        /*$posts = DB::table('users')
            ->JOIN('posts', 'users.id', '=', 'posts.user_id')
            ->SELECT('users.name', 'posts.title', 'posts.content')
            ->WHERE ('title', 'LIKE', '%' . $q . '%')->orWhere ('content', 'LIKE', '%' . $q . '%')
            ->get(); */	
		if (count ( $posts ) > 0)
			return view('posts.index', ['posts' => $posts]);
		else
        Session::flash('success_msg', 'No Record Found');
        return redirect('posts');
    }    
#-------------------------------- ---------------------------------------------------------------#

}