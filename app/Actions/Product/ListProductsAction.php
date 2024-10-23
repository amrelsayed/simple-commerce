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
        $cacheKey = $this->generateCacheKey($request);

        $products = Cache::remember($cacheKey, 60 * 60, function () use ($request) {
            // initiate query
            $query = Product::query();

            // filter query
            if ($request->has(key: "name")) {
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

    protected function generateCacheKey(Request $request): string
    {
        $key = 'products:';

        if ($request->has("name")) {
            $key .= 'name_' . $request->name . ':';
        }

        if ($request->has("price_from")) {
            $key .= 'price_from_' . $request->price_from . ':';
        }

        if ($request->has("price_to")) {
            $key .= 'price_to_' . $request->price_to . ':';
        }

        if ($request->has("category_id")) {
            $key .= 'category_' . $request->category_id . ':';
        }

        $page = $request->get('page', 1);
        $key .= 'page_' . $page;

        return rtrim($key, ':');
    }
}