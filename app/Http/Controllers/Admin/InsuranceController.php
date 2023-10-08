<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.insurances.index');
    }

    public function insurance_api(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'name',
            5 => 'created_at',
        ];

        $search = $request->input('search.value');

        $totalData = Insurance::count();

        $query = Insurance::orderBy($columns[$request->input('order.0.column')], $request->input('order.0.dir'));

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
        $insurances = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($insurances)) {
            $ids = $start;

            foreach ($insurances as $insurance) {
                $nestedData['id'] = $insurance->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $insurance->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['created_at'] = $insurance->created_at->format('M Y');
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
        $insuranceId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:insurances,name->ar,'. $insuranceId,
            'name_en' => 'required|unique:insurances,name->en,'. $insuranceId,
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar'  => $request->name_ar];

        if ($insuranceId) {
            // update the value
            $insurance = Insurance::whereId($insuranceId)->firstOrFail();

            $insurance->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $insurance = Insurance::where('id', $request->id)->first();

            if (empty($country)) {
                $insurance = Insurance::create($data);

                return response()->json('Created');
            } else {
                // country Already exist
                return response()->json(['message' => "Already exits"], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        return response()->json($insurance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insurance $insurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();
        return 'Insurance deleted';
    }
}
