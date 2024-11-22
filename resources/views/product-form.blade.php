<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Product Form</h2>

        <form action="{{ route('product.save') }}" method="POST" class="card p-4 mb-5">
            @csrf
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" name="productName" id="productName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="qtyInStock" class="form-label">Quantity in Stock</label>
                <input type="number" name="qtyInStock" id="qtyInStock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="pricePerItem" class="form-label">Price per Item</label>
                <input type="number" step="0.01" name="pricePerItem" id="pricePerItem" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        @if (!empty($products))
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity in Stock</th>
                        <th>Price per Item</th>
                        <th>Datetime Submitted</th>
                        <th>Total Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($products as $index => $product)
                        <tr>
                            <td>{{ $product['productName'] }}</td>
                            <td>{{ $product['qtyInStock'] }}</td>
                            <td>${{ number_format($product['pricePerItem'], 2) }}</td>
                            <td>{{ $product['datetimeSubmitted'] }}</td>
                            <td>${{ number_format($product['totalValue'], 2) }}</td>
                            <td>
                                <a href="{{ route('product.edit', $index) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                        @php $grandTotal += $product['totalValue']; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Grand Total:</th>
                        <th>${{ number_format($grandTotal, 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p class="text-center">No products submitted yet.</p>
        @endif
    </div>
</body>
</html>
