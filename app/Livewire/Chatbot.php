<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Chatbot extends Component
{

    public $currentMessage = '';
    public $userPrompt = '';
    public $chatMessages = [];

    protected $rules = [
        'currentMessage' => 'required',
    ];

    protected $messages = [
        'currentMessage.required' => 'Campo richiesto'
    ];

    public function asking()
    {
        $this->validate();
        $this->chatMessages[] = [
            'role' => 'user',
            'content' => $this->currentMessage,
        ];

        $this->userPrompt = $this->currentMessage;
        $this->currentMessage = '';

        $this->generateResponse();
    }

    public function generateResponse()
    {
        try {
            $response = Http::timeout(60)->post('http://127.0.0.1:8080/chat/travel-agent', [
                'messages' => $this->chatMessages
            ]);

            if ($response->successful()) {
                $content = $response->json();

                logger()->info('API Raw Response', $content);

                if (isset($content['response'])) {
                    $messages = collect($content['response']);

                    $filteredMessages = $messages->filter(function ($message) {
                        return isset($message['type']) && $message['type'] === 'ai';
                    })->map(function ($message) {
                        return [
                            'role' => 'assistant',
                            'content' => $message['content'] ?? ''
                        ];
                    });

                    $this->chatMessages = array_merge($this->chatMessages, $filteredMessages->toArray());
                }
            }
        } catch (\Exception $e) {
            logger('Chat API Error: ' . $e->getMessage());
            $this->chatMessages[] = [
                'role' => 'assistant',
                'content' => 'Errore di connessione'
            ];
        }
    }


    public function render()
    {
        $this->dispatch('scrollChatBottom');
        return view('livewire.chatbot');
    }
}
