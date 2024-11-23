<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Product Management</h2>
        <form id="productForm" class="card p-4 mb-5">
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
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
        <div id="productTableContainer">
            <p class="text-center">Loading products...</p>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            loadProducts();
            $('#productForm').submit(function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.post("{{ route('product.save') }}", formData, function (response) {
                    if (response.success) {
                        alert('Product added successfully!');
                        $('#productForm')[0].reset();
                        loadProducts(); // Reload products after adding
                    } else {
                        alert('Failed to add product.');
                    }
                }).fail(function () {
                    alert('An error occurred while adding the product.');
                });
            });
            function loadProducts() {
                $.get("{{ route('product.index') }}", function (data) {
                    if (Array.isArray(data) && data.length > 0) {
                        let tableHtml = `
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity in Stock</th>
                                        <th>Price per Item</th>
                                        <th>Datetime Submitted</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                        let grandTotal = 0;
                        data.forEach((product) => {
                            tableHtml += `
                                <tr data-id="${product.id}">
                                    <td contenteditable="true" class="editable" data-field="productName">${product.productName}</td>
                                    <td contenteditable="true" class="editable" data-field="qtyInStock">${product.qtyInStock}</td>
                                    <td contenteditable="true" class="editable" data-field="pricePerItem">${parseFloat(product.pricePerItem).toFixed(2)}</td>
                                    <td>${product.datetimeSubmitted}</td>
                                    <td>${parseFloat(product.totalValue).toFixed(2)}</td>
                                </tr>`;
                            grandTotal += product.totalValue;
                        });
                        tableHtml += `
                                <tr>
                                    <th colspan="4" class="text-end">Grand Total:</th>
                                    <th>$${grandTotal.toFixed(2)}</th>
                                </tr>
                            </tbody>
                        </table>`;
                        $('#productTableContainer').html(tableHtml);

                        attachEditListeners();
                    } else {
                        $('#productTableContainer').html('<p class="text-center">No products submitted yet.</p>');
                    }
                });
            }
            function attachEditListeners() {
                $('.editable').on('blur', function () {
                    const $cell = $(this);
                    const newValue = $cell.text().trim();
                    const field = $cell.data('field');
                    const productId = $cell.closest('tr').data('id');

                    $.ajax({
                        url: `{{ route('product.update', ':id') }}`.replace(':id', productId),
                        method: 'PUT',
                        data: {
                            field: field,
                            value: newValue,
                        },
                        success: function (response) {
                            if (response.success) {
                                alert('Product updated successfully.');
                                loadProducts();
                            } else {
                                alert('Failed to update product. Please try again.');
                            }
                        },
                        error: function (xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Error occurred while updating product.';
                            alert(errorMsg);
                            loadProducts();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
