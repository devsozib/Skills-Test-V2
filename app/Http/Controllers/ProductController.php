<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $filePath = 'products.json';

    public function showForm()
    {
        $products = $this->getProducts();
        return view('product-form', compact('products'));
    }

    public function saveProduct(Request $request)
    {
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'qtyInStock' => 'required|integer|min:0',
            'pricePerItem' => 'required|numeric|min:0',
        ]);

        $validated['datetimeSubmitted'] = now();
        $validated['totalValue'] = $validated['qtyInStock'] * $validated['pricePerItem'];

        $products = $this->getProducts();
        $products[] = $validated;

        file_put_contents(public_path($this->filePath), json_encode($products, JSON_PRETTY_PRINT));

        return redirect()->route('product.form');
    }

    public function editProduct($index)
    {
        $products = $this->getProducts();
        $product = $products[$index];
    
        return view('product-edit', compact('product', 'index'));
    }

    public function updateProduct(Request $request, $index)
    {
        $products = $this->getProducts();
    
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'qtyInStock' => 'required|integer|min:0',
            'pricePerItem' => 'required|numeric|min:0',
        ]);
    
        $products[$index]['productName'] = $validated['productName'];
        $products[$index]['qtyInStock'] = $validated['qtyInStock'];
        $products[$index]['pricePerItem'] = $validated['pricePerItem'];
        $products[$index]['datetimeSubmitted'] = now();
        $products[$index]['totalValue'] = $validated['qtyInStock'] * $validated['pricePerItem'];
    
        // Save updated data back to the file
        file_put_contents(public_path('products.json'), json_encode($products, JSON_PRETTY_PRINT));
    
        return redirect()->route('product.form');
    }
    
    

    private function getProducts()
{
    $path = public_path($this->filePath);
    if (!file_exists($path)) {
        return [];
    }

    $products = json_decode(file_get_contents($path), true);

    usort($products, function($a, $b) {
        return strtotime($b['datetimeSubmitted']) - strtotime($a['datetimeSubmitted']);
    });

    return $products;
}

}

