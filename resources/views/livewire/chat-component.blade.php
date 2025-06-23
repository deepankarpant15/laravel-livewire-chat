<div class="flex flex-col h-screen bg-gray-50 antialiased font-sans">
    <div class="fixed top-0 left-0 right-0 z-10 bg-gray-900 shadow-lg p-4 flex items-center justify-between text-white">
        <a href="/dashboard" class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-gray-200 hover:text-white transition-colors duration-200">
                <path fill-rule="evenodd" d="M11.03 4.53a.75.75 0 0 0-1.06 0L3.47 10.03a.75.75 0 0 0 0 1.06l6.5 6.5a.75.75 0 1 0 1.06-1.06L5.81 11.5H16.5a.75.75 0 0 0 0-1.5H5.81l5.22-5.22a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
            </svg>
            <span class="text-lg font-medium text-gray-200">Back</span>
        </a>
        <div class="flex-grow text-center">
            @if($user)
            <h1 class="text-xl font-semibold text-white">{{ $user->name }}</h1>
            @else
            <h1 class="text-xl font-semibold text-white">Select a User</h1>
            @endif
        </div>
        <button class="text-gray-200 hover:text-white transition-colors duration-200 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div
        class="flex-1 p-4 overflow-y-auto mt-16 mb-20 space-y-4 bg-gray-50"
        id="chat-scroll"
        x-data="{
            init() {
                this.$el.scrollTop = this.$el.scrollHeight;
                Livewire.on('message-sent', () => { this.$el.scrollTop = this.$el.scrollHeight; });
                Livewire.on('message-received', () => { this.$el.scrollTop = this.$el.scrollHeight; });
            },
            messagesChanged() {
                this.$nextTick(() => { this.$el.scrollTop = this.$el.scrollHeight; });
            }
        }"
        wire:ignore.self
        wire:key="chat-messages-container-{{ $receiver_id }}"
        x-on:livewire:updated="messagesChanged()">
        @foreach($messages as $message)
        @if(isset($message['sender_id']) && auth()->check())
        @if($message['sender_id'] == auth()->user()->id)
        {{-- My message (darker background) --}}
        <div class="flex justify-end">
            <div class="bg-gray-800 text-white p-3 rounded-lg max-w-[75%] shadow-md break-words">
                {{ $message['message'] }}
                @if(isset($message['created_at']))
                <span class="block text-right text-xs opacity-70 mt-1">{{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}</span>
                @endif
            </div>
        </div>
        @else
        {{-- Other user's message (lighter background) --}}
        <div class="flex justify-start">
            <div class="bg-gray-200 text-gray-900 p-3 rounded-lg max-w-[75%] shadow-md break-words">
                <span class="font-semibold text-sm block mb-1 text-gray-700">{{ $message['sender_name'] ?? 'Unknown' }}</span>
                {{ $message['message'] }}
                @if(isset($message['created_at']))
                <span class="block text-right text-xs text-gray-600 mt-1">{{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}</span>
                @endif
            </div>
        </div>
        @endif
        @else
        <div class="flex justify-start text-red-500">
            <div class="bg-red-100 p-3 rounded-lg max-w-[75%] shadow-md">
                Error displaying message.
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-3 flex items-center shadow-lg">
        <textarea
            class="flex-grow py-2 px-4 mr-2 rounded-full border border-gray-300 bg-gray-100 text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 resize-none h-10 overflow-hidden"
            rows="1"
            wire:model.live="message"
            placeholder="Type your message..."
            x-ref="messageInput"
            wire:loading.attr="disabled"
            wire:target="sendMessage"></textarea>
        <button
            type="submit"
            class="bg-gray-800 hover:bg-gray-900 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400"
            wire:loading.attr="disabled"
            wire:target="sendMessage">
            <span wire:loading.remove wire:target="sendMessage">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z" />
                </svg>
            </span>
            <span wire:loading wire:target="sendMessage">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    </form>
</div>