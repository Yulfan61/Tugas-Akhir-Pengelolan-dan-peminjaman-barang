<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $blogs = Blog::with('user')->latest()->paginate(10);
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        $this->authorize('create', Blog::class);
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Blog::class);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Blog::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog berhasil dibuat.');
    }

    public function show(Blog $blog)
    {
        if (auth()->check()) {
            return view('blogs.show_auth', compact('blog'));
        } else {
            return view('blogs.show_guest', compact('blog'));
        }
    }


    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->authorize('update', $blog);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Optional: hapus thumbnail lama jika ada
            if ($blog->thumbnail && \Storage::disk('public')->exists($blog->thumbnail)) {
                \Storage::disk('public')->delete($blog->thumbnail);
            }
            $blog->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $blog->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog berhasil diperbarui.');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);

        // Optional: hapus thumbnail dari storage
        if ($blog->thumbnail && \Storage::disk('public')->exists($blog->thumbnail)) {
            \Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog berhasil dihapus.');
    }
}
