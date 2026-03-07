<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;

$id = 66;
$category = Category::withCount(['products', 'subcategoryProducts'])->find($id);

if (!$category) {
    echo "Category $id not found\n";
    exit;
}

echo "Category: " . $category->name . " (ID: $id)\n";
echo "Parent ID: " . ($category->parent_id ?? 'NULL') . "\n";
echo "Products Count: " . $category->products_count . "\n";
echo "Subcategory Products Count: " . $category->subcategory_products_count . "\n";

$products = Product::where('category_id', $id)->orWhere('subcategory_id', $id)->get();
echo "Products found via query: " . $products->count() . "\n";
foreach ($products as $p) {
    echo " - Product: " . $p->name . " (ID: " . $p->id . ") cat:" . ($p->category_id ?? 'NULL') . " subcat:" . ($p->subcategory_id ?? 'NULL') . "\n";
}
