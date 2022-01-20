<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\RelatedProduct;

class RelatedProductsController extends Controller
{

    /** 
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        if (auth()->user()->isAdmin()){

            $this->validate($request,[
                'orig_product'=>'required'
            ]);

            // Find product
            $original_product = Product::find($request->input('orig_product'));
            if ($original_product === null) abort(404);

            // Delete all stored values for specified product
            $relatedProducts = RelatedProduct::where('product_id', $original_product->id)->get();
            foreach ($relatedProducts as $related_product){
                $related_product->delete();
            }

            // Add related products to specified product
            $all_products = Product::all();
            $inputs = $request->all();
            foreach ($all_products as $product){
                if (array_key_exists($product->id, $inputs)){
                    $related_product = new RelatedProduct;
                    $related_product->product_id = $original_product->id;
                    $related_product->related_product_id = $product->id;
                    $related_product->save();
                }
            }

            return redirect('/products'.'/'.$original_product->id);

        }
        else abort(404);
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
     * Show the form for editing resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($productID)
    {
        if (auth()->user()->isAdmin()){
            $product = Product::find($productID);
            if ($product === null) abort(404);

            $all_products = Product::all();
            $relatedProducts = RelatedProduct::where('product_id', $product->id)->get();

            $data = array(
                'original_product'=>$product,
                'products'=>$all_products,
                'relatedProducts'=>$relatedProducts
            );

            return view('pages.related_products.edit')->with($data);
        }
        else abort(404);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
