<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

        // return response
        return ProductResource::collection($query->paginate());
    }
}
