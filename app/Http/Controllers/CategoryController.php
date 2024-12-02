<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;


class CategoryController extends Controller
{
    // public function index()
    // {
    //     $categories = Category::get();
    //     return view('category.index', compact('categories'));
    // }

    // public function create()
    // {
    //     return view('category.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([

    //         'name'=>'required|max:255|string',
    //         'description'=>'required|max:255|string',
    //         'is_active'=>'sometimes' // Now Create Category model to safe the data
    //     ]);

    //     Category::create([
    //         'name'=>$request->name,
    //         'description'=>$request->description,
    //         'is_active'=>$request->is_active == true ? 1:0,
    //     ]);

    //     return redirect('categories/create')->with('status','Category Created');
    // }
    
    // public function edit(int $id)
    // {
    //     $category = Category::findOrFail($id);
    //     // return $category;

    //     return view('category.edit' , compact('category'));
    // }

    // public function update(Request $request, int $id)
    // {
    //     $request->validate([

    //         'name'=>'required|max:255|string',
    //         'description'=>'required|max:255|string',
    //         'is_active'=>'sometimes' // Now Create Category model to safe the data
    //     ]);

    //     Category::findOrFail($id)->update([
    //         'name'=>$request->name,
    //         'description'=>$request->description,
    //         'is_active'=>$request->is_active == true ? 1:0,
    //     ]);

    //     return redirect()->back()->with('status','Category Update');
    // }

    // public function destroy(int $id)
    // {
    //    $category = Category::findOrFail($id);
    //    $category->delete();

    //    return redirect()->back()->with('status','Category Deleted');
    // }






   // Fetch all categories with their products
   public function index()
   {
       $categories = Category::with('products')->get();
       return response()->json($categories, 200);
   }



   // Create a new category and optionally add products to it
   public function store(Request $request)
   {
       $request->validate([
           'name' => 'required',
           'products' => 'array'
       ]);

       
       $category = Category::create(['name' => $request->name]);

       // If there are products, create them and associate them with the category
       if ($request->has('products')) {
           foreach ($request->products as $productData) {
               $category->products()->create($productData);
           }
       }

       return response()->json([
           'message' => 'Category created successfully',
           'category' => $category->load('products') 
       ], 201);
   }

   // Update a category and its associated products
   public function update(Request $request, $id)
   {
       $request->validate([
           'name' => 'required',
           'products' => 'array' 
       ]);

       $category = Category::find($id);

       if (!$category) {
           return response()->json(['message' => 'Category not found'], 404);
       }

       // Update the category
       $category->update(['name' => $request->name]);

       if ($request->has('products')) {
           foreach ($request->products as $productData) {
               if (isset($productData['id'])) {
                   $product = Product::where('category_id', $category->id)->find($productData['id']);
                   if ($product) {
                       $product->update([
                           'name' => $productData['name'],
                       ]);
                   }
               } else {
                   $category->products()->create($productData);
               }
           }
       }

       return response()->json([
           'message' => 'Category and products updated successfully',
           'category' => $category->load('products') // Return updated category with products
       ], 200);
   }

    
   // Delete a category and all associated products
   public function destroy($id)
   {
       $category = Category::find($id);

       if (!$category) {
           return response()->json(['message' => 'Category not found'], 404);
       }

       // Delete all products associated with the category
       $productsDeleted = $category->products()->delete();

       // Delete the category itself
       $category->delete();

       return response()->json([
           'message' => 'Category and associated products deleted successfully',
           'category' => $category->name,
           'products_deleted' => $productsDeleted
       ], 200);
   }
}

