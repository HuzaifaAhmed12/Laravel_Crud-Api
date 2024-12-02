<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // Fetch all products with pagination
        $products = Product::paginate(5);
     
        // Return JSON response with paginated products
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image', // Ensuring image is required and is a valid image
        ]);

        try {
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();

            // Create the product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            // Save image in the storage folder
            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            // Return JSON response
            return response()->json([
                'message' => "Product successfully created.",
                'product' => $product,
            ], 201);

        } catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json([
                'message' => "Something went wrong!",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        // Find the product by ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found.'
            ], 404);
        }

        // Return JSON response with product details
        return response()->json([
            'product' => $product
        ], 200);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Find product by ID
            $product = Product::find($id);
    
            if (!$product) {
                return response()->json([
                    'message' => 'Product Not Found.'
                ], 404);
            }
    
            // Update other product fields
            $product->name = $request->name;
            $product->description = $request->description;
    
            // Check if image is provided
            if ($request->hasFile('image')) {
                // Check if there is an existing image and delete it if necessary
                $storage = Storage::disk('public');
                if ($storage->exists($product->image)) {
                    $storage->delete($product->image);
                }
    
                // Handle the new image
                $imageName = Str::random(32) . '.' . $request->image->getClientOriginalExtension();
                $product->image = $imageName;
    
                // Save the new image
                $storage->put($imageName, file_get_contents($request->image));
            }
    
            // Save the updated product
            $product->save();
    
            // Return a success message
            return response()->json([
                'message' => 'Product successfully updated.'
            ], 200);
        } catch (\Exception $e) {
            // Log the error and return a failure message
            return response()->json([
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the product by ID
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Product Not Found.'
                ], 404);
            }

            // Delete product image if exists in storage
            $storage = Storage::disk('public');
            if ($storage->exists($product->image)) {
                $storage->delete($product->image);
            }

            // Delete product record
            $product->delete();

            // Return JSON response
            return response()->json([
                'message' => "Product successfully deleted."
            ], 200);

        } catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json([
                'message' => "Something went wrong!",
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}


