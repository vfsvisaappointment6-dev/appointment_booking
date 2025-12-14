<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Notification::with('user')->paginate(15)
        );
    }

    public function store(StoreNotificationRequest $request)
    {
        $item = Notification::create($request->validated());
        return response()->json($item->load('user'), 201);
    }

    public function show(Notification $notification)
    {
        $this->authorize('view', $notification);
        return response()->json($notification->load('user'));
    }

    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        $this->authorize('update', $notification);
        $notification->update($request->validated());
        return response()->json($notification->load('user'));
    }

    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        $notification->delete();
        return response()->json(null, 204);
    }
}
