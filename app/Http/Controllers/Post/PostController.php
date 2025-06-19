<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // list
    public function list()
    {
        $posts = Post::paginate(10);
        return view('post.list', compact('posts'));
    }


    // Add or Edit view
    public function addEdit(Request $request)
    {
        if (!isset($request->id)) {
            //add
            return view('post.add-edit');
        }

        //edit
        $id = Crypt::decrypt($request->id);
        $post = Post::findOrFail($id);

        return view('post.add-edit', compact('post'));
    }

    // Store or Update logic
    public function storeOrUpdate(StorePostRequest $request)
    {
        $post = $request->has('id') && !empty($request->id)
            ? Post::findOrFail($request->id)
            : new Post();

        $isUpdate = $post->exists;

        $post->title = $request->title;
        $post->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('', ['disk' => 'post']);
            if ($image) {
                $post->image = $image;
            } else {
                return redirect()->back()->withErrors(['failed' => 'Failed to save Image Or Post.']);
            }
        }

        if (!$post->save()) {
            return redirect()->back()->withErrors(['failed' => 'Failed to save post.']);
        }

        return redirect()->route('post.list')->with('success', $isUpdate ? 'Post updated successfully.' : 'Post created successfully.');
    }

    // Delete method
    public function delete(DeletePostRequest $request)
    {
        $post = Post::find($request->id);

        // Delete image from storage
        $imagePath = $post->getRawOriginal('image');

        if ($imagePath && Storage::disk('post')->exists($imagePath)) {
            Storage::disk('post')->delete($imagePath);
        }

        if ($post->delete()) {
            return redirect()->route('post.list')->with('success', 'Post deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete the post.');
    }


    // Delete a post using AJAX
    public function onDelete(Request $request)
    {
        $id = $request->id;
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'msg' => 'Post not found.'
            ]);
        }

        // Delete image from storage
        if ($post->image && Storage::disk('post')->exists($post->getRawOriginal('image'))) {
            Storage::disk('post')->delete($post->getRawOriginal('image'));
        }

        if ($post->delete()) {
            return response()->json([
                'success' => true,
                'msg' => 'Post deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Failed to delete the post.'
        ]);
    }

}
