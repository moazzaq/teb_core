<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.services.index');
    }

    public function services_api(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'name',
            4 => 'created_at',
        ];

        $search = [];

        $totalData = Service::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $servicesQuery = Service::query();

        if (empty($request->input('search.value'))) {
            $services = $servicesQuery
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $services = $servicesQuery
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('name->ar', 'LIKE', "%{$search}%")
                        ->orWhere('name->en', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = $servicesQuery
                ->where(function($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('name->ar', 'LIKE', "%{$search}%")
                        ->orWhere('name->en', 'LIKE', "%{$search}%");
                })
                ->count();
        }

        $data = [];

        if (!empty($services)) {
            // providing a dummy id instead of database ids
            $ids = $start;

            foreach ($services as $service) {
                $nestedData['id'] = $service->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['name'] = $service->getTranslation('name', app()->getLocale(Config::get('app.locale')));
                $nestedData['created_at'] = $service->created_at->format('M Y');

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
        $serviceId = $request->id;
        $request->validate([
            'name_ar' => 'required|unique:services,name->ar,'. $serviceId,
            'name_en' => 'required|unique:services,name->en,'. $serviceId,
        ]);

        $data['name'] = ['en' => $request->name_en, 'ar'  => $request->name_ar];

        if ($serviceId) {
            // update the value
            $service = Service::whereId($serviceId)->firstOrFail();

            $service->update($data);

            // user updated
            return response()->json(__('cp.update'));
        } else {
            // create new one if email is unique
            $service = Service::where('id', $request->id)->first();

            if (empty($service)) {
                $service = Service::create($data);

                return response()->json(__('cp.create'));
            } else {
                // category Already exist
                return response()->json(['message' => "Already exits"], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return 'Service deleted';
    }
}
