<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateConversationAction;
use App\Actions\SendMessageAction;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    public function __construct(
        private readonly CreateConversationAction $createConversation,
        private readonly SendMessageAction $sendMessage
    ) {
    }

    public function index(): Response
    {
        $conversations = Conversation::with(['messages' => function ($query) {
            $query->latest()->limit(1);
        }])
            ->latest()
            ->paginate(10);

        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
        ]);
    }

    public function show(Conversation $conversation): Response
    {
        $conversation->load('messages');

        return Inertia::render('Chat/Show', [
            'conversation' => $conversation,
        ]);
    }

    public function store(): Response
    {
        $conversation = $this->createConversation->execute();

        return Inertia::render('Chat/Show', [
            'conversation' => $conversation->load('messages'),
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        try {
            $this->sendMessage->execute($conversation, $validated['content']);

            return response()->json([
                'success' => true,
                'conversation' => $conversation->load('messages'),
            ]);
        } catch (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Has enviado demasiados mensajes. Por favor, espera un momento antes de intentar nuevamente.',
            ], 429);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje. Por favor, intenta nuevamente.',
            ], 500);
        }
    }
}

# cGFuZ29saW4=
