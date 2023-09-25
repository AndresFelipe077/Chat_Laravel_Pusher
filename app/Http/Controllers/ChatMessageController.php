<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{

  /**
   * Gets chat message
   *
   * @param GetMessageRequest $request
   * @return JsonResponse
   */
  public function index(GetMessageRequest $request): JsonResponse
  {
    $data = $request->validated();

    $chatId = $data['chat_id'];
    $currentPage = $data['page'];
    $pageSize = $data['page_size'] ?? 15;

    $messages = ChatMessage::where('chat_id', $chatId)
      ->with('user')
      ->latest('created_at')
      ->simplePaginate(
        $pageSize,
        ['*'],
        'page',
        $currentPage
      );

    return $this->success($messages->getCollection());

  }

  /**
   * Create chat a message
   *
   * @param StoreMessageRequest $request
   * @return JsonResponse
   */
  public function store(StoreMessageRequest $request): JsonResponse
  {

    $data = $request->validated();
    $data['user_id'] = auth()->user()->id;

    $chatMessage = ChatMessage::create($data);
    $chatMessage->load('user');

    /// TODO send broadcast event to pusher and send notification to onesignal services

    return $this->success($chatMessage, 'Message has been sent successfully');

  }

}
