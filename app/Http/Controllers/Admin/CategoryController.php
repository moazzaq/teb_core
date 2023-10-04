<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.categories.index');
    }

    public function categories_api(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'name',
            3 => 'slug',
            4 => 'created_at',
        ];

        $search = [];

        $totalData = Category::whereNull('parent_id')->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $categoriesQuery = Category::whereNull('parent_id');

        if (empty($request->input('search.value'))) {
            $categories = $categoriesQuery
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $categories = $categoriesQuery
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('name->ar', 'LIKE', "%{$search}%")
                        ->orWhere('name->en', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $categoriesQuery
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('name->ar', 'LIKE', "%{$search}%")
                        ->orWhere('name->en', 'LIKE', "%{$search}%");
                })
                ->count();
        }

        $data = [];

        if (!empty($categories)) {
            // providing a dummy id instead of database ids
            $ids = $start;

            foreach ($categories as $category) {
                $nestedData['id'] = $category->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $category->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['slug'] = $category->slug;
                //   $nestedData['image'] = asset('storage/'.$category->image);
                $nestedData['created_at'] = $category->created_at->format('M Y');

                $data[] = $nestedData;
            }
        }

        if ($data) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'code' => 200,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server Error',
                'code' => 500,
                'data' => [],
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categoryId = $request->id;
        $existingCategory = Category::find($categoryId);
//        $request->validate([
//            'name_ar' => 'required|unique:categories,name->ar,'. $categoryId,
//            'name_en' => 'required|unique:categories,name->en,'. $categoryId,
//            'image' => 'required',
//        ]);
        $commonRules = [
            'name_ar' => 'required|unique:categories,name->ar,'. $categoryId,
            'name_en' => 'required|unique:categories,name->en,'. $categoryId,
        ];

        // Add the 'required' rule for 'image' for store operation
        $storeRules = $commonRules + [
                //      'image' => 'required',
            ];

        // Apply the appropriate validation rules based on the operation
        $request->validate($categoryId ? $commonRules : $storeRules);

        $data['name'] = ['en' => $request->name_en, 'ar'  => $request->name_ar];
//        if ($request->hasFile('image')) {
//            $newImagePath = $request->file('image')->store('categories', 'public');
//
//            $oldCategoryPath = $existingCategory->image;
//            if ($oldCategoryPath !== null && Storage::disk('public')->exists($oldCategoryPath)) {
//                Storage::disk('public')->delete($oldCategoryPath);
//            }
//            $data['image'] = $newImagePath;
//        }
        if ($categoryId) {
            // update the value
            $category = Category::whereId($categoryId)->firstOrFail();

            $category->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $category = Category::where('id', $request->id)->first();

            if (empty($category)) {
                $category = Category::create($data);

                return response()->json('Created');
            } else {
                // category Already exist
                return response()->json(['message' => "Already exits"], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return 'Category deleted';
    }
}
