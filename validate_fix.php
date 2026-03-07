<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\Inventory\ProductService;

$productService = app(ProductService::class);

DB::enableQueryLog();

echo "--- VALIDATING CATEGORY 1 (ROOT) ---\n";
$res1 = $productService->getAllProducts(['category_id' => 1]);
$ids1 = collect($res1['data'])->pluck('id')->toArray();
echo "SQL: " . DB::getQueryLog()[0]['query'] . "\n";
echo "Bindings: " . json_encode(DB::getQueryLog()[0]['bindings']) . "\n";
echo "Products for cat:1: [" . implode(',', $ids1) . "] (Count: " . count($ids1) . ")\n";

DB::flushQueryLog();

echo "\n--- VALIDATING CATEGORY 66 (SUBCAT) ---\n";
$res66 = $productService->getAllProducts(['category_id' => 66]);
$ids66 = collect($res66['data'])->pluck('id')->toArray();
echo "SQL: " . DB::getQueryLog()[0]['query'] . "\n";
echo "Bindings: " . json_encode(DB::getQueryLog()[0]['bindings']) . "\n";
echo "Products for cat:66: [" . implode(',', $ids66) . "] (Count: " . count($ids66) . ")\n";

if (in_array(1119, $ids66)) {
    echo "\nSUCCESS: Product 1119 is correctly found when filtering by its subcategory (66)!\n";
} else {
    echo "\nFAILURE: Product 1119 NOT found when filtering by subcategory 66.\n";
}
