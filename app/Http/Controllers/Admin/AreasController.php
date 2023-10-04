<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AreasController extends Controller
{
    public function index()
    {
        $cities = CityResource::collection(City::latest()->get());

        return view('backend.areas.index', compact('cities'));
    }

    public function areas_api(Request $request)
    {

        $columns = [
            1 => 'id',
            2 => 'name',
            3 => 'city_id',
            4 => 'created_at',
        ];

        $search = $request->input('search.value');
        $cityId = $request->input('city_id');



        $totalData = Area::count();

        $query = Area::with('city')
            ->orderBy($columns[$request->input('order.0.column')], $request->input('order.0.dir'));

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name->ar', 'LIKE', "%{$search}%")
                    ->orWhere('name->en', 'LIKE', "%{$search}%")
                    ->orWhereHas('city', function ($query) use ($search) {
                        $query->where('name->ar', 'LIKE', "%{$search}%")
                            ->orWhere('name->en', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by country if a country ID is provided
        if (!empty($cityId)) {
            $query->where('city_id', $cityId);
        }

        $totalFiltered = $query->count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $areas = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($areas)) {
            $ids = $start;

            foreach ($areas as $area) {
                $nestedData['id'] = $area->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $area->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['lang'] = app()->getLocale(Config::get('app.locale'));
                $nestedData['slug'] = $area->slug;
                $nestedData['city'] = $area->city ? $area->city->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['created_at'] = $area->created_at->format('M Y');
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
        $areaId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:areas,name->ar,'. $areaId,
            'name_en' => 'required|unique:areas,name->en,'. $areaId,
            'city_id' => 'required|exists:cities,id',
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['city_id'] = $request->city_id;

        if ($areaId) {
            // update the value
            $area = Area::whereId($areaId)->firstOrFail();
            $area->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $area = Area::where('id', $request->id)->first();

            if (empty($area)) {
                $area = Area::create($data);

                // area created
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
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $area = Area::where('id', $id)->first();

        return response()->json($area);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return 'Area deleted';
    }
}
