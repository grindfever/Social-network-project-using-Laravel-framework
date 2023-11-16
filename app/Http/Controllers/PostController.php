

public function showPost(string $id): View
    {
        // Get the post.
        $post = Post::findOrFail($id);

        // Check if the current user can see (show) the post.
        $this->authorize('show',$post);

        // Use the pages.post template to display the post,
        return view('pages.dashboard', [
            'post' => $post
        ]);
    }