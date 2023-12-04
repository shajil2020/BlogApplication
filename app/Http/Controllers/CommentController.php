<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\User;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function index()
    {
        $blogPosts = BlogComment::paginate(10); 
        return response()->json($blogPosts);
    }

    public function show($id)
    {
        
    }

    public function store(Request $request)
    {
        try {
            $blogPost = BlogPost::findOrFail($request->blog_post_id);
            $blogPost = User::findOrFail($request->user_id);

            $request->validate([
                'content' => 'required',
                'blog_post_id' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);
        
            $blogComment = BlogComment::create($request->all());
            return response(['message' => 'Comment added Succssfully','blogComment'=>$blogComment], 200);
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'Blog post or user not found'], 404);
        } catch (ValidationException $e) {
            return response(['message' => 'Validation failed.','errors' => $e->errors()], 422);
            
        } catch (\Exception $e) {
            return response(['message' => 'Something went wrong.'], 500);
        } 
          
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'blog_post_id' => 'required',
            'content' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $blogComment = BlogComment::findOrFail($id);
        $blogComment->update($request->all());

        return response()->json($blogComment);  
    }

    public function destroy($id)
    {
        $blogComment = BlogComment::findOrFail($id);
        $blogComment->delete();

        return response()->json(null, 204);
    }
    public function replayForComment(Request $request)
    {
        try {
            $blogPost = BlogPost::findOrFail($request->blog_post_id);
            $user = User::findOrFail($request->user_id);
            $request->validate([
                'content' => 'required',
                'blog_post_id' => 'required',
                'user_id' => 'required|exists:users,id',
                "parent_id"=>'required',
            ]);
            
            $blogComment = BlogComment::create($request->all());
            return response(['message' => 'Comment added Succssfully','blogComment'=>$blogComment], 200);
        }
        catch (ModelNotFoundException $e) {
            return response(['message' => 'Blog post or user not found'], 404);
        } catch (ValidationException $e) {
            return response(['message' => 'Validation failed.','errors' => $e->errors()], 422);
            
        } catch (\Exception $e) {
            return response(['message' => 'Something went wrong.'], 500);
        }  
    }
}
