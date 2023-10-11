<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class NationalityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.nationalities.index');
    }

    public function nationality_api(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'name',
            5 => 'created_at',
        ];

        $search = $request->input('search.value');

        $totalData = Nationality::count();

        $query = Nationality::orderBy($columns[$request->input('order.0.column')], $request->input('order.0.dir'));

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name->ar', 'LIKE', "%{$search}%")
                    ->orWhere('name->en', 'LIKE', "%{$search}%");
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
        $nationalityId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:nationalities,name->ar,'. $nationalityId,
            'name_en' => 'required|unique:nationalities,name->en,'. $nationalityId,
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar'  => $request->name_ar];

        if ($nationalityId) {
            // update the value
            $nationality = Nationality::whereId($nationalityId)->firstOrFail();

            $nationality->update($data);

            // user updated
            return response()->json(__('cp.update'));
        } else {
            // create new one if email is unique
            $nationality = Nationality::where('id', $request->id)->first();

            if (empty($country)) {
                $nationality = Nationality::create($data);

                return response()->json(__('cp.create'));
            } else {
                // country Already exist
                return response()->json(['message' => "Already exits"], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Nationality $nationality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nationality $nationality)
    {
        return response()->json($nationality);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nationality $nationality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nationality $nationality)
    {
        $nationality->delete();
        return 'Nationality deleted';
    }
}
