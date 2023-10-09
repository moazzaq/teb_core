<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CurrencyController extends Controller
{
    public function index()
    {
        $countries = CountryResource::collection(Country::latest()->get());

        return view('backend.currencies.index', compact('countries'));
    }

    public function currencies_api(Request $request)
    {

        $columns = [
            1 => 'id',
            2 => 'name',
            3 => 'country_id',
            4 => 'created_at',
        ];

        $search = $request->input('search.value');
        $countryId = $request->input('country_id');



        $totalData = Currency::count();

        $query = Currency::with('country')
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
        $currencies = $query->skip($start)->take($limit)->get();

        $data = [];

        if (!empty($currencies)) {
            $ids = $start;

            foreach ($currencies as $currency) {
                $nestedData['id'] = $currency->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $currency->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['lang'] = app()->getLocale(Config::get('app.locale'));
                $nestedData['country'] = $currency->country ? $currency->country->getTranslation('name', app()->getLocale(Config::get('app.locale'))) : '';
                $nestedData['created_at'] = $currency->created_at->format('M Y');
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
        $currencyId = $request->id;

        $request->validate([
            'name_ar' => 'required|unique:currencies,name->ar,'. $currencyId,
            'name_en' => 'required|unique:currencies,name->en,'. $currencyId,
            'country_id' => 'required|exists:countries,id',
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar' => $request->name_ar];
        $data['country_id'] = $request->country_id;

        if ($currencyId) {
            // update the value
            $currency = Currency::whereId($currencyId)->firstOrFail();
            $currency->update($data);

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $currency = Currency::where('id', $request->id)->first();

            if (empty($currency)) {
                $currency = Currency::create($data);

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
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $currency = Currency::where('id', $id)->first();

        return response()->json($currency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();
        return 'Currency deleted';
    }
}
