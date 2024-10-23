<?php

namespace App\Actions\Product;
use App\Models\Product;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProductsAction
{
    public function execute(Request $request): LengthAwarePaginator
    {
        $products = Cache::remember("products", 60 * 60, function () use ($request) {
            // initiate query
            $query = Product::query();

            // filter query
            if ($request->has("name")) {
                $query->where("name", "like", "%" . $request->name . "%");
            }

            if ($request->has("price_from")) {
                $query->where("price", '>=', $request->price_from);
            }

            if ($request->has("price_to")) {
                $query->where("price", '<=', $request->price_to);
            }

            if ($request->has("category_id")) {
                $query->whereHas("category", function ($query) use ($request) {
                    $query->where("id", $request->category_id);
                });
            }

            // load category
            $query->with("category");

            return $query->paginate();
        });

        return $products;
    }
}