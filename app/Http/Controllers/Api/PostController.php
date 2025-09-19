<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Models\Post; // dùng model của Voyager

class PostController extends Controller
{
    // GET /api/posts
    public function index(Request $request)
    {
        $perPage = (int)($request->get('per_page', 10));
        $status  = $request->get('status'); // ví dụ: PUBLISHED
        $q       = $request->get('q');      // tìm theo tiêu đề

        $posts = Post::query()
            ->when($status, fn($qb) => $qb->where('status', strtoupper($status)))
            ->when($q, fn($qb) => $qb->where('title', 'like', "%{$q}%"))
            ->latest('created_at')
            ->paginate($perPage);

        return PostResource::collection($posts);
    }

    // GET /api/posts/{id}
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }

    // (tuỳ chọn) GET /api/posts/slug/{slug}
    public function showBySlug($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return new PostResource($post);
    }

    // POST /api/posts
    public function store(Request $request)
    {
        // Validation đơn giản nhất, nhận file ảnh hoặc path
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:posts,slug',
            'excerpt'     => 'nullable|string',
            'body'        => 'nullable|string',
            'status'      => 'nullable|in:PUBLISHED,DRAFT',
            'featured'    => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
            'image'       => 'nullable', // có thể là file upload hoặc chuỗi path
        ]);

        // slug tự sinh nếu không truyền
        if (empty($data['slug'])) {
            $base = Str::slug($data['title']);
            $slug = $base;
            $i = 1;
            while (Post::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;
        }

        // xử lý ảnh: cho phép multipart file 'image'
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = new Post();
        $post->title       = $data['title'];
        $post->slug        = $data['slug'];
        $post->excerpt     = $data['excerpt'] ?? null;
        $post->body        = $data['body'] ?? null;
        $post->status      = $data['status'] ?? 'PUBLISHED';
        $post->featured    = (bool)($data['featured'] ?? false);
        $post->category_id = $data['category_id'] ?? null;
        $post->author_id   = auth()->id() ?? 1; // đơn giản: nếu chưa auth thì gán 1

        if (!empty($data['image'])) {
            $post->image = $data['image']; // path đã lưu hoặc chuỗi path truyền vào
        }

        $post->save();

        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    // PUT/PATCH /api/posts/{id}
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt'     => 'nullable|string',
            'body'        => 'nullable|string',
            'status'      => 'nullable|in:PUBLISHED,DRAFT',
            'featured'    => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
            'image'       => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            // xoá ảnh cũ nếu cần
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        // gán từng field để tránh mass-assignment
        foreach (['title','slug','excerpt','body','status','category_id'] as $f) {
            if (array_key_exists($f, $data)) $post->{$f} = $data[$f];
        }
        if (array_key_exists('featured', $data)) {
            $post->featured = (bool)$data['featured'];
        }
        if (array_key_exists('image', $data)) {
            $post->image = $data['image']; // path mới hoặc path string
        }

        $post->save();

        return new PostResource($post);
    }

    // DELETE /api/posts/{id}
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
