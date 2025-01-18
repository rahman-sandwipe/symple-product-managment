<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Product;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($search = $request->search){
            $search = $request->search;
            // Get sorting parameters
            $sortBy = $request->query('sort_by', 'name'); // Default sort by 'name'
            $sortOrder = $request->query('sort_order', 'asc'); // Default order 'asc'

            $products =Product::where (function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                ->orwhere('product_id', 'like', "%$search%")
                ->orwhere('price', 'like', "%$search%")
                ->orwhere('description', 'like', "%$search%");
            })->orderBy($sortBy, $sortOrder)->paginate(5);
            

            // Pass sorting parameters to the view
            return view('products.index',compact('products', 'sortBy', 'sortOrder'));
        }else{
            // Get sorting parameters
            $sortBy = $request->query('sort_by', 'name'); // Default sort by 'name'
            $sortOrder = $request->query('sort_order', 'asc'); // Default order 'asc'

            // Fetch and sort data
            $products = Product::orderBy($sortBy, $sortOrder)->paginate(5);

            // Pass sorting parameters to the view
            return view('products.index',compact('products', 'sortBy', 'sortOrder'));
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->all();
        $id = IdGenerator::generate(['table'=>'products','field'=>'product_id','length'=>8,'prefix'=>'PID']);
        $input['product_id'] = $id;
        //output: PID00001
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/products';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "uploads/products/$profileImage";
        }
        Product::create($input);
        return redirect()->route('products.index')->with('success','Product created successfully.');
    }

    /**
    * Display the specified resource.
    */
    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }
     
 
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'uploads/products';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "uploads/products/$profileImage";
        }else{
            unlink($input['image']);
        }
        $product->update($input);
        return redirect()->route('products.index')->with('success','Product updated successfully');
    }
 
   
 
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function destroy(Product $product)
    {
        if (empty($product)) {
            abort(404);
        }else{
            unlink($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
