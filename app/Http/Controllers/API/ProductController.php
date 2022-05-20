<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->sendResponse(ProductResource::collection($products) , 'Products Retrieved Successfully');
    }

    public function userProducts($id)
    {
        $product = Product::find($id);
        if($product == null)
        {
            return $this->sendError('Product Not Found');
        }
        else
        {
            $products = Product::where('user_id' , $id)->get();
            return $this->sendResponse(new ProductResource($products) , 'Products Retrieved Successfully!');
        }
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'details'=>'required',
            'price'=>'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Validated Error' , $validator->errors());
        }

        $product = Product::create($request->all());
        return $this->sendResponse(new ProductResource($product) , "Product Added Successfully");
    }

   
    public function show($id)
    {
        $product = Product::find($id);
        if($product == null)
        {
            return $this->sendError('Product Not Found');
        }
        else
        {
            return $this->sendResponse(new ProductResource($product) , 'Product Retrieved Successfully');
        }
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
        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'details'=>'required',
            'price'=>'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Validated Error' , $validator->errors());
        }

        $product = Product::find($id);
        $product->update($request->all());
        return $this->sendResponse(new ProductResource($product) , "Product Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product == null)
        {
            return $this->sendError('Product Not Found');
        }
        else
        {
            $product->delete();
            return $this->sendResponse(new ProductResource($product) , 'Product Deleted Successfully!');
        }
    }
}
