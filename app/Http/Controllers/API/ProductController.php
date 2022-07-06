<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();
        return response()->json([ProductResource::collection($product), 'Product Fetched']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['slug'] = Str::slug($request->name);
        $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required',
            'price' => 'required|integer',
        ]);

        $product = new Product($request->all());
        $category = Category::where('id', $request->category_id)->first();
        $product->category()->associate($category);

        $product->save();
        $files = $request->file('image');
        $allowedfileExtension = ['jpg', 'png', 'jpeg', 'JPG', 'JPEG', 'PNG'];
        if($files){
            // foreach($request->file('image') as $file) {
                $ext = $files->getClientOriginalExtension();
                $check = in_array($ext, $allowedfileExtension);

                if($check) {
                    $name = $files->getClientOriginalName();
                    $path = $files->storeAs('public/productAssets', $name);
                    $asset = new ProductAsset();
                    $asset->product_id = $product->id;
                    $asset->image = $name;
                    $asset->save();
                } else {
                    return response()->json(['File Format Not Supported'], 422);
                }
            // }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->get();
        return response()->json([ProductResource::collection($product), 'Product Fetched']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required',
            'price' => 'required|integer',
        ]);
        $request['slug'] = Str::slug($request->name);
        $category = Category::where('id', $request->category_id)->first();
        $product->category()->associate($category);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->save();
        $files = $request->file('image');
        if($files){
            $allowedfileExtension = ['jpg', 'png', 'jpeg', 'JPG', 'JPEG', 'PNG'];
            // foreach($request->file('image') as $file) {
                $ext = $files->getClientOriginalExtension();
                $check = in_array($ext, $allowedfileExtension);

                if($check) {
                    $name = $files->getClientOriginalName();
                    $path = $files->storeAs('public/productAssets', $name);
                    $asset = new ProductAsset();
                    $asset->product_id = $product->id;
                    $asset->image = $name;
                    $asset->save();
                } else {
                    return response()->json(['File Format Not Supported'], 422);
                }
            // }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductAsset::where('product_id', $id)->delete();
        Product::find($id)->delete();
    }
}
