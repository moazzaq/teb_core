<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.countries.index');
    }

    public function countries_api(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'name',
            4 => 'created_at',
        ];

        $search = [];

        $totalData = Country::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $countriesQuery = Country::query();

        if (empty($request->input('search.value'))) {
            $countries = $countriesQuery
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $countries = $countriesQuery
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('name->ar', 'LIKE', "%{$search}%")
                        ->orWhere('name->en', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $countriesQuery
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('name->ar', 'LIKE', "%{$search}%")
                        ->orWhere('name->en', 'LIKE', "%{$search}%");
                })
                ->count();
        }

        $data = [];

        if (!empty($countries)) {
            // providing a dummy id instead of database ids
            $ids = $start;

            foreach ($countries as $country) {
                $nestedData['id'] = $country->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $country->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                //   $nestedData['image'] = asset('storage/'.$country->image);
                $nestedData['created_at'] = $country->created_at->format('M Y');

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
        $countryId = $request->id;
        $existingCountry = Country::find($countryId);

        $commonRules = [
            'name_ar' => 'required|unique:countries,name->ar,'. $countryId,
            'name_en' => 'required|unique:countries,name->en,'. $countryId,
        ];

        // Add the 'required' rule for 'image' for store operation
        $storeRules = $commonRules + [
                //      'image' => 'required',
            ];

        // Apply the appropriate validation rules based on the operation
        $request->validate($countryId ? $commonRules : $storeRules);

        $data['name'] = ['en' => $request->name_en, 'ar'  => $request->name_ar];
//        if ($request->hasFile('image')) {
//            $newImagePath = $request->file('image')->store('countries', 'public');
//
//            $oldCountryPath = $existingCountry->image;
//            if ($oldCountryPath !== null && Storage::disk('public')->exists($oldCountryPath)) {
//                Storage::disk('public')->delete($oldCountryPath);
//            }
//            $data['image'] = $newImagePath;
//        }
        if ($countryId) {
            // update the value
            $country = Country::whereId($countryId)->firstOrFail();

            $country->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $country = Country::where('id', $request->id)->first();

            if (empty($country)) {
                $country = Country::create($data);

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
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        return response()->json($country);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return 'Country deleted';
    }
}
