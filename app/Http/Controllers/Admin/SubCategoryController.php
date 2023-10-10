<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        $parent_categories = CategoryResource::collection(Category::whereNull('parent_id')->latest()->get());

        return view('backend.sub_categories.index', compact('categories', 'parent_categories'));
    }

    public function sub_categories_api(Request $request)
    {

        $columns = [
            1 => 'id',
            2 => 'name',
            3 => 'slug',
            4 => 'parent_id',
            5 => 'created_at',
        ];

        $search = $request->input('search.value');
        $categoryId = $request->input('category_id');



        $totalData = Category::whereNotNull('parent_id')->count();

        $query = Category::with('parent')
            ->whereNotNull('parent_id')
            ->orderBy($columns[$request->input('order.0.column')], $request->input('order.0.dir'));

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name->ar', 'LIKE', "%{$search}%")
                    ->orWhere('name->en', 'LIKE', "%{$search}%")
                    ->orWhereHas('parent', function ($query) use ($search) {
                        $query->where('name->ar', 'LIKE', "%{$search}%")
                            ->orWhere('name->en', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by category if a category ID is provided
        if (!empty($categoryId)) {
            $query->where('parent_id', $categoryId);
        }

        $totalFiltered = $query->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $categories = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($categories)) {
            $ids = $start;

            foreach ($categories as $category) {
                $nestedData['id'] = $category->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $category->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['lang'] = app()->getLocale(Config::get('app.locale'));
                $nestedData['slug'] = $category->slug;
                $nestedData['main_category'] = $category->parent ? $category->parent->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
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

        $request->validate([
            'name_ar' => 'required|unique:categories,name->ar,'. $categoryId,
            'name_en' => 'required|unique:categories,name->en,'. $categoryId,
            'parent_id' => 'required|exists:categories,id',
        ],[
            'name_ar.required' => __('validation.required'),
            'name_en.required' => __('validation.required'),
            'name_ar.unique' => __('validation.unique'),
            'name_en.unique' => __('validation.unique'),
            'parent_id.exists' => __('validation.exists'),
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['parent_id'] = $request->parent_id;

        if ($categoryId) {
            // update the value
            $category = Category::whereId($categoryId)->firstOrFail();
            $category->update($data);

            // user updated
            return response()->json(__('cp.update'));
        } else {
            // create new one if email is unique
            $category = Category::where('id', $request->id)->first();

            if (empty($category)) {
                $category = Category::create($data);

                // category created
                return response()->json(__('cp.create'));
            } else {
                // category already exist
                return response()->json(['message' => "already exits"], 422);
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
    public function edit($id)
    {
        $category = Category::where('id', $id)->first();

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
