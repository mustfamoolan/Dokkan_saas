<?php

namespace App\Services\Inventory;

use App\Models\Category;

class CategoryService
{
    /**
     * Get all categories
     */
    public function getAllCategories(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Category::with('parent', 'children')->withCount(['products', 'subcategoryProducts']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        // root_only=1: return only top-level categories (parent_id IS NULL)
        if (!empty($filters['root_only'])) {
            $query->whereNull('parent_id');
        } elseif (isset($filters['parent_id'])) {
            // parent_id filter: return children of a specific category
            $query->where('parent_id', $filters['parent_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get category tree (hierarchical structure)
     */
    public function getCategoryTree(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->orderBy('name')->withCount(['products', 'subcategoryProducts']);
                }
            ])
            ->withCount(['products', 'subcategoryProducts'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Create a new category
     */
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        // Prevent setting category as its own parent
        if (isset($data['parent_id']) && $data['parent_id'] == $category->id) {
            throw new \InvalidArgumentException('لا يمكن تعيين الفئة كأصل لنفسها');
        }

        // Prevent circular reference
        if (isset($data['parent_id']) && $this->wouldCreateCircularReference($category, $data['parent_id'])) {
            throw new \InvalidArgumentException('لا يمكن إنشاء مرجع دائري في الفئات');
        }

        $category->update($data);
        return $category->fresh(['parent', 'children']);
    }

    /**
     * Delete category
     */
    public function deleteCategory(Category $category): bool
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            throw new \Exception('لا يمكن حذف الفئة لأنها تحتوي على منتجات');
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            throw new \Exception('لا يمكن حذف الفئة لأنها تحتوي على فئات فرعية');
        }

        return $category->delete();
    }

    /**
     * Check if setting parent would create circular reference
     */
    private function wouldCreateCircularReference(Category $category, int $parentId): bool
    {
        $parent = Category::find($parentId);
        if (!$parent) {
            return false;
        }

        // Check if the new parent is a descendant of this category
        $current = $parent;
        while ($current->parent_id) {
            if ($current->parent_id == $category->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }
}
