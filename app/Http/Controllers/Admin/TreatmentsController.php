<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TreatmentsController extends Controller
{
    public function index()
    {
      //  $categories = Category::latest()->get();
        $parent_categories = CategoryResource::collection(Category::whereNull('parent_id')->latest()->get());
        $child_categories = CategoryResource::collection(Category::whereNotNull('parent_id')->latest()->get());

        return view('backend.treatments.index', compact('parent_categories', 'child_categories'));
    }

    public function treatments_api(Request $request)
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



        $totalData = Treatment::count();

        $query = Treatment::with('category_parent', 'category_child')
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

        // Filter by category if a category ID is provided
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $totalFiltered = $query->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $treatments = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($treatments)) {
            $ids = $start;

            foreach ($treatments as $treatment) {
                $nestedData['id'] = $treatment->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $treatment->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['lang'] = app()->getLocale(Config::get('app.locale'));
                $nestedData['slug'] = $treatment->slug;
                $nestedData['category'] = $treatment->category_parent ? $treatment->category_parent->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['sub_category'] = $treatment->category_child ? $treatment->category_child->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['created_at'] = $treatment->created_at->format('M Y');
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
        $treatmentId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:treatments,name->ar,'. $treatmentId,
            'name_en' => 'required|unique:treatments,name->en,'. $treatmentId,
            'category_id' => 'required|exists:categories,id',
            'child_category_id' => 'nullable|exists:categories,id',
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['category_id'] = $request->category_id;
        $data['child_category_id'] = $request->child_category_id;

        if ($treatmentId) {
            // update the value
            $treatment = Treatment::whereId($treatmentId)->firstOrFail();
            $treatment->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $treatment = Treatment::where('id', $request->id)->first();

            if (empty($treatment)) {
                $treatment = Treatment::create($data);

                // category created
                return response()->json('Created');
            } else {
                // category already exist
                return response()->json(['message' => "already exits"], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $treatment = Treatment::where('id', $id)->first();

        return response()->json($treatment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return 'Treatment deleted';
    }
}
