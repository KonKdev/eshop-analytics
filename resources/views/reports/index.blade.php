<!-- Top Products -->
<div class="bg-white dark:bg-gray-800 p-6 shadow rounded-lg mt-6">
    <h3 class="text-gray-500 mb-4">Top Products</h3>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Product</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Units Sold</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($topProducts as $product)
                <tr>
                    <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                        {{ $product->product_name }}
                    </td>
                    <td class="px-4 py-2 font-bold text-gray-900 dark:text-white">
                        {{ $product->total_sold }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
