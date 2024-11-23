<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $filePath = 'products.json';

    public function showForm()
    {
        return view('product-form');
    }

    public function index()
    {
        $products = $this->getProducts();
        return response()->json($products);
    }

    public function saveProduct(Request $request)
    {
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'qtyInStock' => 'required|integer|min:0',
            'pricePerItem' => 'required|numeric|min:0',
        ]);

        $validated['id'] = $this->generateUniqueId();
        $validated['datetimeSubmitted'] = now();
        $validated['totalValue'] = $validated['qtyInStock'] * $validated['pricePerItem'];

        $products = $this->getProducts();
        $products[] = $validated;

        file_put_contents(public_path($this->filePath), json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'message' => 'Product added successfully!']);
    }

    public function editProduct($id)
    {
        $products = $this->getProducts();
        $product = collect($products)->firstWhere('id', $id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        return response()->json(['success' => true, 'product' => $product]);
    }

    public function updateProduct(Request $request, $id)
    {
        $products = $this->getProducts();
        $productIndex = collect($products)->search(fn($product) => $product['id'] == $id);

        if ($productIndex === false) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $field = $request->input('field');
        $value = $request->input('value');
        $products[$productIndex][$field] = $value;

        if (in_array($field, ['qtyInStock', 'pricePerItem'])) {
            $products[$productIndex]['totalValue'] = $products[$productIndex]['qtyInStock'] * $products[$productIndex]['pricePerItem'];
            $products[$productIndex]['datetimeSubmitted'] = now()->toISOString();
        }

        file_put_contents(public_path($this->filePath), json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
    }

    private function getProducts()
    {
        $path = public_path($this->filePath);
        if (!file_exists($path)) {
            return [];
        }

        $products = json_decode(file_get_contents($path), true);

        usort($products, function ($a, $b) {
            return strtotime($b['datetimeSubmitted']) - strtotime($a['datetimeSubmitted']);
        });

        return $products;
    }

    private function generateUniqueId()
    {
        return uniqid();
    }
}
