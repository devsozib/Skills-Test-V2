<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Product</h2>

        <form action="{{ route('product.update', $index) }}" method="POST" class="card p-4 mb-5">
            @csrf
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" name="productName" id="productName" class="form-control" value="{{ $product['productName'] }}" required>
            </div>
            <div class="mb-3">
                <label for="qtyInStock" class="form-label">Quantity in Stock</label>
                <input type="number" name="qtyInStock" id="qtyInStock" class="form-control" value="{{ $product['qtyInStock'] }}" required>
            </div>
            <div class="mb-3">
                <label for="pricePerItem" class="form-label">Price per Item</label>
                <input type="number" step="0.01" name="pricePerItem" id="pricePerItem" class="form-control" value="{{ $product['pricePerItem'] }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
