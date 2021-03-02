<?php


namespace App\Http\Controllers\Post;


use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController
{
    public function list()
    {
        $posts = Post::paginate(10);
        $page = 'posts/';

        return view('post.table', compact('posts', 'page'));
    }

    public function author($id)
    {
        $author = User::find($id);
        $posts = $author->posts()->paginate(10);
        $page = 'posts/author/' . $id;

        return view('post.table', compact('posts', 'page'));
    }

    public function category($id)
    {
        $category = Category::find($id);
        $posts = $category->posts()->paginate(10);
        $page = 'posts/category/' . $id;

        return view('post.table', compact('posts', 'page'));
    }

    public function tag($id)
    {
        $tag = Tag::find($id);
        $posts = $tag->posts()->paginate(10);
        $page = 'posts/tag/' . $id;

        return view('post.table', compact('posts', 'page'));
    }

    public function authorAndCategory($author, $category)
    {
        $posts = Post::whereHas('user', function (\Illuminate\Database\Eloquent\Builder $query) use ($author, $category) {
            $query->where('user_id', '=', $author);
            $query->where('category_id', '=', $category);
        })->paginate(10);

        $page = 'posts/author/' . $author . '/category/' . $category;
        return view('post.table', compact('posts', 'page'));
    }

    public function authorAndCategoryAndTag($author, $category, $tag)
    {
        $posts = Post::whereHas('tags', function (\Illuminate\Database\Eloquent\Builder $query) use ($author, $category, $tag) {
            $query->where('user_id', '=', $author);
            $query->where('category_id', '=', $category);
            $query->where('tag_id', '=', $tag);

        })->paginate(10);

        $page = 'posts/author/' . $author . '/category/' . $category . '/tag/' . $tag;
        return view('post.table', compact('posts', 'page'));
    }

    public function create()
    {
        $users = User::all();
        $categories = Category::all();
        $tags = Tag::all();

        return view('post.form', compact('users', 'categories', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'min:5', 'unique:posts,title'],
            'body'        => ['required', 'min:5'],
            'user_id'     => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags'        => ['required', 'exists:tags,id'],
        ]);

        $post = Post::create($data);
        $post->tags()->attach($data['tags']);

        $_SESSION['message'] = [
            'status' => 'success',
            'text'   => "Post \"{$post->title}\" successfully saved"
        ];

        return redirect()->route('posts');
    }

    public function edit(Post $post)
    {
        $users = User::all();
        $categories = Category::all();
        $tags = Tag::all();
        $tag_ids = $post->tags->pluck('id')->toArray();

        return view('post.form', compact('users', 'categories', 'tags', 'post', 'tag_ids'));
    }

    public function update(Post $post, Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'min:5', 'unique:posts,title,' . $post->id],
            'body'        => ['required', 'min:5'],
            'user_id'     => ['required', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags'        => ['required', 'exists:tags,id'],
        ]);

        $post->update($data);
        $post->tags()->sync($data['tags']);

        $_SESSION['message'] = [
            'status' => 'success',
            'text'   => "Post \"{$post->title}\" successfully saved"
        ];

        return redirect()->route('posts');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        $_SESSION['message'] = [
            'status' => 'success',
            'text'   => "Post \"{$post->title}\" successfully deleted"
        ];

        return redirect()->route('posts');
    }
}
