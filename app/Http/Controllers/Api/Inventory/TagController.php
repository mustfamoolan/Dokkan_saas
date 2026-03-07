<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $tags = $query->withCount('products')
            ->orderBy('name')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'data' => TagResource::collection($tags->items()),
            'meta' => [
                'current_page' => $tags->currentPage(),
                'last_page' => $tags->lastPage(),
                'per_page' => $tags->perPage(),
                'total' => $tags->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
        ]);

        $tag = Tag::create($validated);

        return response()->json([
            'message' => 'تم إنشاء التاغ بنجاح',
            'data' => new TagResource($tag),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): JsonResponse
    {
        $tag->loadCount('products');

        return response()->json([
            'data' => new TagResource($tag),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name,' . $tag->id],
        ]);

        $tag->update($validated);

        return response()->json([
            'message' => 'تم تحديث التاغ بنجاح',
            'data' => new TagResource($tag),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json([
            'message' => 'تم حذف التاغ بنجاح',
        ]);
    }
}
