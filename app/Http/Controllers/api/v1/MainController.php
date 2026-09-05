<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MovementResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Movement;
use App\Models\Product;
use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function status(): JsonResponse
  {
    return ApiResponse::success([
      'currentStatusString' => 'API is running',
      'serverDate' => now()->toDateString(),
      'serverTime' => now()->toTimeString(),
      'serverTimestamp' => now()->timestamp,
      'serverTimezone' => now()->timezoneName,
      'apiVersion' => 'v1'
    ]);
  }

  public function listCategories(): JsonResponse
  {
    $perPage = request()->query('per_page', 15);
    $categories = Category::paginate($perPage);

    return ApiResponse::success([
      'categories' => CategoryResource::collection($categories),
      'pagination' => [
        'current_page' => $categories->currentPage(),
        'last_page' => $categories->lastPage(),
        'per_page' => $categories->perPage(),
        'total_categories' => $categories->total(),
      ],
    ]);
  }

  public function getCategoryById(int $id): JsonResponse
  {
    $category = Category::find($id);

    if (!$category) {
      return ApiResponse::error("Category with ID {$id} not found", 404);
    }

    return ApiResponse::success([
      'category' => new CategoryResource($category),
    ]);
  }

  public function createCategory(Request $request): JsonResponse
  {
    $data = $request->validate([
      'name' => 'required|string|max:50|unique:categories,name',
      'description' => 'nullable|string',
    ]);

    $category = Category::create($data);

    return ApiResponse::success(
      [new CategoryResource($category)],
      'Category created successfully',
      201,
    );
  }

  public function updateCategory(Request $request, int $id): JsonResponse
  {
    $category = Category::find($id);

    if (!$id) {
      return ApiResponse::error("Category with ID {$id} not found", 404);
    }

    $data = $request->validate([
      'name' => "string|max:50|unique:categories,name,{$id}",
      'description' => 'nullable|string',
    ]);

    $category->update($data);

    return ApiResponse::success(
      [new CategoryResource($category)],
      'Category updated successfully',
    );
  }

  public function deleteCategory(int $id): JsonResponse
  {
    $category = Category::find($id);

    if (!$category) {
      return ApiResponse::error("Category with ID {$id} not found", 404);
    }

    $category->delete();

    return ApiResponse::success(
      [],
      'Category deleted successfully',
      204
    );
  }

  public function listProducts(): JsonResponse
  {
    $perPage = request()->query('per_page', 15);
    $products = Product::with('category')->paginate($perPage);

    return ApiResponse::success([
      'products' => ProductResource::collection($products),
      'pagination' => [
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'per_page' => $products->perPage(),
        'total_products' => $products->total(),
      ],
    ]);
  }

  public function getProductById(int $id): JsonResponse
  {
    $product = Product::find($id);

    if (!$product) {
      return ApiResponse::error("Product with ID {$id} not found", 404);
    }

    return ApiResponse::success([
      'product' => new ProductResource($product),
    ]);
  }

  public function getProductByCategory(int $id): JsonResponse
  {
    $category = Category::find($id);

    if (!$category) {
      return ApiResponse::error("Category with ID {$id} not found", 404);
    }

    $products = Product::where('category_id', $id)
      ->get()
      ->toResourceCollection(ProductResource::class)
      ->resolve();

    $products = array_map(function ($product) {
      unset($product['category']);
      return $product;
    }, $products);

    return ApiResponse::success([
      'category' => new CategoryResource($category),
      'products' => $products,
      'total' => count($products),
    ]);
  }

  public function createProducts(Request $request): JsonResponse
  {
    $data = $request->validate([
      'name' => 'required|string|max:50|unique:products,name',
      'description' => 'nullable|string',
      'category_id' => 'required|integer|exists:categories,id',
    ]);

    $product = Product::create($data);

    return ApiResponse::success(
      [new ProductResource($product)],
      'Product created successfully',
      201,
    );
  }

  public function updateProducts(Request $request, int $id): JsonResponse
  {
    $product = Product::find($id);

    if (!$product) {
      return ApiResponse::error("Product with ID {$id} not found", 404);
    }

    $data = $request->validate([
      'name' => "string|max:50|unique:products,name,{$id}",
      'description' => 'nullable|string',
      'category_id' => 'integer|exists:categories,id',
    ]);

    $product->update($data);

    return ApiResponse::success(
      [new ProductResource($product)],
      'Product updated successfully',
    );
  }

  public function deleteProducts(int $id): JsonResponse
  {
    $product = Product::find($id);

    if (!$product) {
      return ApiResponse::error("Product with ID {$id} not found", 404);
    }

    $product->delete();

    return ApiResponse::success(
      [],
      'Product deleted successfully',
      204
    );
  }

  public function listMovements(): JsonResponse
  {
    $perPage = request()->query('per_page', 15);
    $movements = Movement::with('product.category')->paginate($perPage);

    return ApiResponse::success([
      'movements' => MovementResource::collection($movements),
      'pagination' => [
        'current_page' => $movements->currentPage(),
        'last_page' => $movements->lastPage(),
        'per_page' => $movements->perPage(),
        'total_movements' => $movements->total(),
      ],
    ]);
  }

  public function listMovementsOrdered(string $field, string $direction): JsonResponse
  {
    $validFields = ['id', 'product_id', 'quantity', 'movement_type', 'created_at', 'updated_at'];
    $validDirection = ['asc', 'desc'];

    if (!in_array($field, $validFields)) {
      return ApiResponse::error("Invalid filter for ordering: {$field}", 400);
    }

    if (!in_array($direction, $validDirection)) {
      return ApiResponse::error("Invalid direction for ordering: {$direction}", 400);
    }

    $perPage = request()->query('per_page', 15);
    $movements = Movement::with('product.category')
      ->orderBy($field, $direction)
      ->paginate($perPage);

    return ApiResponse::success([
      'movements' => MovementResource::collection($movements),
      'pagination' => [
        'current_page' => $movements->currentPage(),
        'last_page' => $movements->lastPage(),
        'per_page' => $movements->perPage(),
        'total_movements' => $movements->total(),
      ],
    ]);
  }

  public function createMovements(Request $request): JsonResponse
  {
    $data = $request->validate([
      'product_id' => 'required|integer|exists:products,id',
      'quantity' => 'required|integer|min:1',
      'movement_type' => 'required|in:in,out',
    ]);

    $movement = Movement::create($data);

    return ApiResponse::success(
      [new MovementResource($movement)],
      'Movement created successfully',
      201,
    );
  }

  public function updateMovements(Request $request, int $id): JsonResponse
  {
    $movement = Movement::find($id);

    if (!$movement) {
      return ApiResponse::error("Movement with ID {$id} not found", 404);
    }

    $data = $request->validate([
      'product_id' => 'integer|exists:products,id',
      'quantity' => 'integer|min:1',
      'movement_type' => 'in:in,out',
    ]);

    $movement->update($data);

    return ApiResponse::success(
      [new MovementResource($movement)],
      'Movement updated successfully',
    );
  }

  public function deleteMovements(int $id): JsonResponse
  {
    $movement = Movement::find($id);

    if (!$movement) {
      return ApiResponse::error("Movement with ID {$id} not found", 404);
    }

    $movement->delete();

    return ApiResponse::success(
      [],
      'Movement deleted successfully',
      204
    );
  }
}
