<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('backend.dashboard');
    }

    public function get_sub_categories(Category $category)
    {
        $items = CategoryResource::collection($category->children()->latest()->get());
        return response()->json($items);
    }
}
