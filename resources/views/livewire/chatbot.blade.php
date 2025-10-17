<div class="chat-container">
    <div id="chat-box" class="chat-box mb-4">
        @forelse ($chatMessages as $key => $chatMessage)
            @if (isset($chatMessage['role']) && isset($chatMessage['content']))
                @if ($chatMessage['role'] == 'user')
                    <div class="content-user-container">
                        <div class="content-message-user">
                            <p class="m-0 fs-5">{{ $chatMessage['content'] }}</p>
                        </div>
                    </div>
                @elseif ($chatMessage['role'] == 'assistant')
                    <div wire:key="{{ $key }}" class="ai-message-container mt-4">
                        <div class="ai-message-content">
                            <p class="m-0 fs-5">{{ $chatMessage['content'] }}</p>
                        </div>
                    </div>
                @endif
            @endif
        @empty
            <h3 class="display-4 text-center">Di cosa hai bisogno oggi?</h3>
        @endforelse
    </div>
    <div>
        <form wire:submit.prevent="asking" class="d-flex py-2 main-form">
            <div class="form-container">
                <input class="form-control input-message fs-5" wire:model.live="currentMessage" type="text"
                    placeholder="Inserisci messaggio....">
                @error('currentMessage')
                    <div class="error-span-box">
                        <span class="error-span fs-4">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-info mx-2 button-form">
                    <i class="bi bi-arrow-up fs-4"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@script
    <script>
        const chatBox = document.querySelector('#chat-box');

        $wire.on('scrollChatBottom', () => {
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        });
    </script>
@endscript
