<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sell;
use App\Services\Calc;
use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'unit' => 'required|in:шт,гр',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'expireAt' => 'required|date',
        ]);

        // Если есть ошибки валидации, установить сообщение об ошибке
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Получение данных о товаре
        $name = $request->input('name');
        $description = $request->input('description');
        $unit = $request->input('unit');
        $price = $request->input('price');
        $stock = $request->input('stock');
        $expireAt = $request->input('expireAt');

        // Создание нового товара
        $product = new Product();
        $product->name = $name;
        $product->description = $description;
        $product->unit = $unit;
        $product->price = $price;
        $product->stock = $stock;
        $product->expireAt = $expireAt;
        $product->save();

        // Получение и сохранение изображения под идентификатором товара
        $image = $request->file('image');
        if ($image) {
            $imageName = $product->id . '.png'; // Формирование имени файла
            $image->storeAs('public/products', $imageName); // Сохранение изображения
        }

        // Возвращение ответа с уведомлением
        return response()->json(['success' => true, 'message' => 'Товар успешно создан']);
    }

    public function list()
    {
        $perPage = 10; // Количество продуктов на странице
        
        $products = Product::paginate($perPage);

        $pagination = [
            'currentPage' => $products->currentPage(),
            'totalPages' => $products->lastPage(),
            'prevPage' => $products->previousPageUrl(),
            'nextPage' => $products->nextPageUrl(),
        ];

        return response()->json([
            'success' => true,
            'products' => $products->items(),
            'pagination' => $pagination,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $perPage = 10; // Number of products per page
        $page = $request->input('page', 1); // Get the page number from the "page" GET parameter
    
        $products = Product::where(function (Builder $builder) use ($query) {
            $builder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
        })->paginate($perPage, ['*'], 'page', $page);
    
        $pagination = [
            'currentPage' => $products->currentPage(),
            'totalPages' => $products->lastPage(),
            'prevPage' => $products->previousPageUrl(),
            'nextPage' => $products->nextPageUrl(),
        ];
    
        return response()->json([
            'success' => true,
            'products' => $products->items(),
            'pagination' => $pagination
        ]);
    }
    function product() {
        return Product::where('id', request()->post('product_id'))->first();
    }

    function sell() {
    $product = request()->post('product');
    $guantity = request()->post('quantity');
       $price = Calc::sell([
            'quantity' => $guantity,
            'unit' => $product['unit'],
            'price' => $product['price']
        ]);
        Sell::create([
            'product_id' => $product['id'],
            'quantity' => $guantity,
            'sum' => $price
        ]);
        Product::where('id', $product['id'])->update(['stock' => $product['stock'] - $guantity]);
        return response()->json([
            'success' => true,
        ]);
    }
    function update() {
        $field = request()->post('field');
        $value = request()->post('value');
        $productId = request()->post('productId');

        Product::where('id', $productId)->update([
            $field => $value
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
