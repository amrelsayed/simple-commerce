<?php

namespace App\Http\Controllers;

use App\Actions\Product\ListProductsAction;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ListProductsAction $listProductsAction)
    {
        // prepare data
        $data = $listProductsAction->execute($request);

        // return response
        return ProductResource::collection($data);
    }
}
