<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffProfile;
use App\Http\Requests\StoreStaffProfileRequest;
use App\Http\Requests\UpdateStaffProfileRequest;

class StaffProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = StaffProfile::with('user');

        if ($request->has('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->has('available')) {
            $query->where('available', filter_var($request->available, FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json($query->paginate(15));
    }

    public function store(StoreStaffProfileRequest $request)
    {
        $item = StaffProfile::create($request->validated());
        return response()->json($item->load('user'), 201);
    }

    public function show(StaffProfile $staffProfile)
    {
        $this->authorize('view', $staffProfile);
        return response()->json($staffProfile->load('user'));
    }

    public function update(UpdateStaffProfileRequest $request, StaffProfile $staffProfile)
    {
        $this->authorize('update', $staffProfile);
        $staffProfile->update($request->validated());
        return response()->json($staffProfile->load('user'));
    }

    public function destroy(StaffProfile $staffProfile)
    {
        $this->authorize('delete', $staffProfile);
        $staffProfile->delete();
        return response()->json(null, 204);
    }
}
