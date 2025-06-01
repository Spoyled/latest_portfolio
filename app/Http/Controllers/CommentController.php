<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->body = $request->body;

        // If a normal user is logged in
        if (auth()->check()) {
            $comment->user_id = auth()->id(); // uses default web guard
        }
        // Else if an employer is logged in
        elseif (auth('employer')->check()) {
            $comment->employer_id = auth('employer')->id();
        }
        else {
            // If neither user nor employer is logged in, deny or redirect
            return redirect()->route('login')->with('error', 'You must be logged in to comment.');
        }

        $comment->save();

        return back()->with('success', 'Comment added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
 * Remove the specified resource from storage.
 */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id); 
        $comment->delete();
        return response()->json(['success' => true]);
    }

}
