<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Cases;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CasesController extends Controller
{
    public function index()
    {
        //  $categories = Category::latest()->get();
        $parent_categories = CategoryResource::collection(Category::whereNull('parent_id')->latest()->get());
        $child_categories = CategoryResource::collection(Category::whereNotNull('parent_id')->latest()->get());

        return view('backend.cases.index', compact('parent_categories', 'child_categories'));
    }

    public function cases_api(Request $request)
    {

        $columns = [
            1 => 'id',
            2 => 'name',
            3 => 'category_id',
            4 => 'child_category_id',
            5 => 'created_at',
        ];

        $search = $request->input('search.value');
        $categoryId = $request->input('category_id');



        $totalData = Cases::count();

        $query = Cases::with('category_parent', 'category_child')
            ->orderBy($columns[$request->input('order.0.column')], $request->input('order.0.dir'));

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name->ar', 'LIKE', "%{$search}%")
                    ->orWhere('name->en', 'LIKE', "%{$search}%")
                    ->orWhereHas('category_parent', function ($query) use ($search) {
                        $query->where('name->ar', 'LIKE', "%{$search}%")
                            ->orWhere('name->en', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('category_child', function ($query) use ($search) {
                        $query->where('name->ar', 'LIKE', "%{$search}%")
                            ->orWhere('name->en', 'LIKE', "%{$search}%");
                    });
            });
        }

        $totalFiltered = $query->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $cases = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($cases)) {
            $ids = $start;

            foreach ($cases as $case) {
                $nestedData['id'] = $case->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $case->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['lang'] = app()->getLocale(Config::get('app.locale'));
                $nestedData['category'] = $case->category_parent ? $case->category_parent->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['sub_category'] = $case->category_child ? $case->category_child->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['created_at'] = $case->created_at->format('M Y');
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
        $caseId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:cases,name->ar,'. $caseId,
            'name_en' => 'required|unique:cases,name->en,'. $caseId,
            'category_id' => 'required|exists:categories,id',
            'child_category_id' => 'nullable|exists:categories,id',
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['category_id'] = $request->category_id;
        $data['child_category_id'] = $request->child_category_id;

        if ($caseId) {
            // update the value
            $cases = Cases::whereId($caseId)->firstOrFail();
            $cases->update($data);

            // user updated
            return response()->json(__('cp.update'));
        } else {
            // create new one if email is unique
            $cases = Cases::where('id', $request->id)->first();

            if (empty($cases)) {
                $cases = Cases::create($data);

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
    public function show(Cases $cases)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cases = Cases::where('id', $id)->first();

        return response()->json($cases);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cases $cases)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cases $cases)
    {
        $cases->delete();
        return 'Cases deleted';
    }
}
