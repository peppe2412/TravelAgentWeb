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
                'messages' => [

                    [
                        'role' => 'user',
                        'content' => $this->userPrompt
                    ]
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json();

                logger()->info('API Raw Response', $content);

                if (!empty($content['response'])) {
                    $messages = collect($content['response']);

                    $ai_messages = $messages->filter(function ($mess) {
                        return isset($mess['type'], $mess['content']) && $mess['type'] == 'ai';
                    });

                    foreach ($ai_messages as $mess) {
                        $this->chatMessages[] = [
                            'role' => 'assistant',
                            'content' => $mess['content']
                        ];
                    }
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
