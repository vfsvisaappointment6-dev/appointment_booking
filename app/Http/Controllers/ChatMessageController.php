<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Http\Requests\StoreChatMessageRequest;
use App\Http\Requests\UpdateChatMessageRequest;

class ChatMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatMessage::with(['sender', 'receiver']);

        if ($request->has('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }

        if ($request->has('sender_id')) {
            $query->where('sender_id', $request->sender_id);
        }

        if ($request->has('receiver_id')) {
            $query->where('receiver_id', $request->receiver_id);
        }

        return response()->json($query->paginate(15));
    }

    public function store(StoreChatMessageRequest $request)
    {
        $item = ChatMessage::create($request->validated());
        return response()->json($item->load(['sender', 'receiver']), 201);
    }

    public function show(ChatMessage $chatMessage)
    {
        $this->authorize('view', $chatMessage);
        return response()->json($chatMessage->load(['sender', 'receiver']));
    }

    public function update(UpdateChatMessageRequest $request, ChatMessage $chatMessage)
    {
        $this->authorize('update', $chatMessage);
        $chatMessage->update($request->validated());
        return response()->json($chatMessage->load(['sender', 'receiver']));
    }

    public function destroy(ChatMessage $chatMessage)
    {
        $this->authorize('delete', $chatMessage);
        $chatMessage->delete();
        return response()->json(null, 204);
    }
}
