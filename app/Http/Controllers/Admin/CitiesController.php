<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CitiesController extends Controller
{
    public function index()
    {
        $countries = CountryResource::collection(Country::latest()->get());

        return view('backend.cities.index', compact('countries'));
    }

    public function cities_api(Request $request)
    {

        $columns = [
            1 => 'id',
            2 => 'name',
            3 => 'country_id',
            4 => 'created_at',
        ];

        $search = $request->input('search.value');
        $countryId = $request->input('country_id');



        $totalData = City::count();

        $query = City::with('country')
            ->orderBy($columns[$request->input('order.0.column')], $request->input('order.0.dir'));

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name->ar', 'LIKE', "%{$search}%")
                    ->orWhere('name->en', 'LIKE', "%{$search}%")
                    ->orWhereHas('country', function ($query) use ($search) {
                        $query->where('name->ar', 'LIKE', "%{$search}%")
                            ->orWhere('name->en', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by country if a country ID is provided
        if (!empty($countryId)) {
            $query->where('country_id', $countryId);
        }

        $totalFiltered = $query->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $cities = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($cities)) {
            $ids = $start;

            foreach ($cities as $city) {
                $nestedData['id'] = $city->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $city->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['lang'] = app()->getLocale(Config::get('app.locale'));
                $nestedData['slug'] = $city->slug;
                $nestedData['country'] = $city->country ? $city->country->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['created_at'] = $city->created_at->format('M Y');
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
        $cityId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:cities,name->ar,'. $cityId,
            'name_en' => 'required|unique:cities,name->en,'. $cityId,
            'country_id' => 'required|exists:countries,id',
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['country_id'] = $request->country_id;

        if ($cityId) {
            // update the value
            $city = City::whereId($cityId)->firstOrFail();
            $city->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $city = City::where('id', $request->id)->first();

            if (empty($city)) {
                $city = City::create($data);

                // city created
                return response()->json('Created');
            } else {
                // city already exist
                return response()->json(['message' => "already exits"], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $city = City::where('id', $id)->first();

        return response()->json($city);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();
        return 'City deleted';
    }
}
