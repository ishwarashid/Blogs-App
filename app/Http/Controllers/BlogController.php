<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Jobs\DeleteBlogImage;
use App\Mail\BlogPosted;
use App\Models\Blog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\search;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cacheKey = $search ? 'blogs_' . md5($search) : 'blogs';

        $searchKeys = Cache::get('search_cache_keys', []);

        if (!in_array($cacheKey, $searchKeys)) {
            $searchKeys[] = $cacheKey;
            Cache::put('search_cache_keys', $searchKeys);
        }

        try {
            $blogs = Cache::remember($cacheKey, now()->addSeconds(30), function () use ($search) {

                return $search ? Blog::where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%")
                    ->latest()
                    ->paginate(5)
                    : Blog::latest()->paginate(5);
            });

            if ($blogs->isEmpty()) {
                return view('blogs.index')->with('noBlogs', $search ? "No Results Found!" : "No Blogs Found!");
            }

            return view('blogs.index', compact('blogs'));
        } catch (\Exception $e) {
            return view('blogs.index')->with('error', "Something went wrong! Please try again.");
        }
    }


    public function create()
    {
        return view('blogs.create');
    }

    public function store(StoreBlogRequest $request)
    {
        $attributes = $request->validated();
        try {
            $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
                'folder' => 'blog-uploads',
            ])->getSecurePath();

            $attributes['blog_image_url'] =  $uploadedFileUrl;
            $attributes['user_id'] =  Auth::id();

            $blog = Blog::create($attributes);

            $searchKeys = Cache::get('search_cache_keys', []);

            foreach ($searchKeys as $key) {
                Cache::forget($key);
            }

            Cache::forget('blogs');

            Mail::to($blog->author)->queue(new BlogPosted($blog));

            return redirect('/blogs/' . $blog->id)->with('success', 'Blog created successfully!');
        } catch (\Exception $e) {
            Log::error('Blog creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e);
        }
    }

    public function show($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            return view('blogs.show', compact('blog'));
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Blog does not exist!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong! Please try again later.');
        }
    }

    public function edit($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            return view('blogs.edit', compact('blog'));
        } catch (\Exception $e) {
            $message = $e instanceof ModelNotFoundException ? 'Blog does not exist!' : 'Something went wrong! Please try again later.';
            return redirect()->back()->with('error', $message);
        }
    }

    public function update(StoreBlogRequest $request, $id)
    {
        $attributes = $request->validated();

        try {
            $blog = Blog::findOrFail($id);

            if ($request->hasFile('image')) {
                $attributes['blog_image_url'] = cloudinary()->upload($request->file('image')->getRealPath(), [
                    'folder' => 'blog-uploads',
                ])->getSecurePath();

                $publicId = 'blog-uploads/' . $this->getPublicIdFromUrl($blog->blog_image_url);
                DeleteBlogImage::dispatch($publicId);
            }

            $blog->update($attributes);

            $searchKeys = Cache::get('search_cache_keys', []);

            foreach ($searchKeys as $key) {
                Cache::forget($key);
            }

            Cache::forget('blogs');
            return redirect('/blogs/' . $blog->id)->with('success', 'Blog updated successfully!');
        } catch (\Exception $e) {
            $message = $e instanceof ModelNotFoundException ? 'Blog does not exist!' : 'Failed to update blog.';
            return redirect()->back()->with('error', $message);
        }
    }

    public function destroy($id)
    {
        try {

            $blog = Blog::findOrFail($id);
            $blog->delete();

            $searchKeys = Cache::get('search_cache_keys', []);

            foreach ($searchKeys as $key) {
                Cache::forget($key);
            }

            Cache::forget('blogs');

            $publicId = 'blog-uploads/' . $this->getPublicIdFromUrl($blog->blog_image_url);
            DeleteBlogImage::dispatch($publicId);
            return redirect('/user/blogs')->with('success', 'Blog deleted successfully!');
        } catch (\Exception $e) {
            $message = $e instanceof ModelNotFoundException ? 'Blog does not exist!' : 'Failed to delete blog.';
            return redirect()->back()->with('error', $message);
        }
    }

    public function userBlogs(Request $request)
    {

        $search = $request->input('search');

        $query =  Blog::where('user_id', Auth::id());

        try {
            if ($search) {
                $query = $query
                    ->where(function ($q) use ($search) {
                        $q->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('content', 'LIKE', "%{$search}%");
                    });
            }

            $blogs =  $query->latest()->paginate(5);

            if ($blogs->isEmpty()) {
                return view('blogs.user')->with('noBlogs', $search ? "No Results Found!" : "No Blogs Found!");
            }

            return view('blogs.user', compact('blogs'));
        } catch (\Exception $e) {
            return view('blogs.user')->with('error', "Something went wrong! Please try again.");
        }
    }

    private function getPublicIdFromUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', $path);
        $publicId = end($segments);
        $publicId = pathinfo($publicId, PATHINFO_FILENAME);
        return $publicId;
    }
}










// Blog::where('user_id', Auth::user()->id)
//     ->where(function ($query) use ($search) {
//         $query->where('title', 'LIKE', "%{$search}%")
//             ->orWhere('content', 'LIKE', "%{$search}%");
//     })
//     ->latest()
//     ->get();
