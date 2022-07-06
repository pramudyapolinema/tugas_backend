<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAsset;
use Illuminate\Http\Request;

class ProductAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'product_id' => 'required|integer',
            'image' => 'required',
        ]);
        $files = $request->file('image');
        if($files){
            $allowedfileExtension = ['jpg', 'png', 'jpeg', 'JPG', 'JPEG', 'PNG'];
            foreach($files as $file) {
                $ext = $file->getClientOriginalExtension();
                $check = in_array($ext, $allowedfileExtension);

                if($check) {
                    $name = $file->getClientOriginalName();
                    $path = $file->storeAs('public/productAssets', $name);
                    $asset = new ProductAsset();
                    $asset->product_id = $request->product_id;
                    $asset->image = $name;
                    $asset->save();
                } else {
                    return response()->json(['File Format Not Supported'], 422);
                }
            }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $asset = ProductAsset::find($id);
        $request->validate([
            'image' => 'required',
        ]);
        $file = $request->file('image');

        $allowedfileExtension = ['jpg', 'png', 'jpeg', 'JPG', 'JPEG', 'PNG'];
        $ext = $file->getClientOriginalExtension();
        $check = in_array($ext, $allowedfileExtension);
        if($check) {
            $name = $file->getClientOriginalName();
            $path = $file->storeAs('public/productAssets', $name);
            $asset->image = $name;
            $asset->save();
        } else {
            return response()->json(['File Format Not Supported'], 422);
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
        ProductAsset::find($id)->delete();
    }
}
