<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StorecategoriesRequest;
use App\Http\Requests\UpdatecategoriesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $header = 'Categories';
        $desc = "Add, View and or Edit $header As you Wish!";
        return view('categories', compact('header', 'desc', 'categories'));
    }

    public function editCategory(Request $request)
    {
        if (($category = $request->category))
            $category = Category::findOrFail($category);
        $message = 'Category Added Successfully';
        $status = 201;
        if ($category) {
            $this->updateCategory($category, $request);
            $message = 'Category Edited Successfully';
            $status = 200;
        } else
            $this->insertCategory($request);
        session()->flash('message', $message);
        return response()->json([
            'status' => 'success'
        ], $status);
    }

    public function insertCategory($details)
    {
        $details = collect($details->all());
        Validator::make($details->all(), [
            'name' => 'required|min:4|max:255|unique:categories',
        ])->validate();
        $slug = Str::slug($details->get('name'));
        while (Category::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(3);
        }
        $details->put('slug', $slug);
        return Category::create($details->all());
    }

    public function updateCategory($category, $details)
    {
        $details = collect($details->all());
        Validator::make($details->all(), [
            'name' => ['required', 'min:5', 'max:255', Rule::unique('categories', 'name')->ignore($category)],
        ])->validate();
        $slug = Str::slug($details->get('name'));
        while (Category::where('slug', $slug)->where('id', '<>', $category->id)->exists()) {
            $slug .= '-' . Str::random(3);
        }
        $details->put('slug', $slug);
        return $category->update($details->all());
    }
}
