<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::with("products")->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
    $category = new Category();
    $category->name = $request->input('name'); // Accessing request parameters directly
    $category->description = $request->input('description');

if ($request->hasFile('avatar')) {
    $avatar = $request->file("avatar");
    $fileName = time() . "." . $avatar->getClientOriginalExtension();

    $avatar->storeAs("public/categories", $fileName);
    $category->avatar = $fileName;
}

$category->save();
return response()->json(["success" => "category created", "category" => $category], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Category=Category::with("products")->where("id",$id)->first();
        if(!$Category){
            return response()->json(["error"=>"Category not found"],404);

        }else{
            return response()->json($Category);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
