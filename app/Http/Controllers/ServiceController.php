<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Service::paginate(15));
    }

    public function store(StoreServiceRequest $request)
    {
        $item = Service::create($request->validated());
        return response()->json($item, 201);
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);
        return response()->json($service);
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);
        $service->update($request->validated());
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();
        return response()->json(null, 204);
    }
}
