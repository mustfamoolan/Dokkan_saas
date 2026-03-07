<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "--- ROOT CATEGORIES (parent_id IS NULL) ---\n";
$roots = Category::whereNull('parent_id')->get();
foreach ($roots as $root) {
    echo "ID: " . $root->id . " | Name: " . $root->name . "\n";
}

echo "\n--- CHILDREN OF ID 1 ---\n";
$children = Category::where('parent_id', 1)->get();
foreach ($children as $child) {
    echo "ID: " . $child->id . " | Name: " . $child->name . " | Parent: " . $child->parent_id . "\n";
}

echo "\n--- CATEGORY 66 DETAILS ---\n";
$cat66 = Category::find(66);
if ($cat66) {
    echo "ID: 66 | Name: " . $cat66->name . " | Parent: " . ($cat66->parent_id ?? 'NULL') . "\n";
} else {
    echo "Category 66 not found\n";
}
