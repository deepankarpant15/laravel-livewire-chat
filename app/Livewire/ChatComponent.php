<?php

namespace App\Livewire;

use App\Events\MessageSendEvent;
use App\Models\Message; // Assuming you meant App\Models\Message, not App\Models\ChatMessage
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ChatComponent extends Component
{
    public $user; // Represents the currently selected chat partner
    public $sender_id;
    public $receiver_id;
    public $message = ''; // Holds the content of the new message input
    public $messages = []; // For displaying messages in the frontend

    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id)
    {
        $this->sender_id = Auth::id();
        $this->receiver_id = $user_id;

        // Fetch messages for the conversation between sender_id and receiver_id
        $fetchedMessages = Message::where(function ($query) {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })
            ->orWhere(function ($query) {
                $query->where('sender_id', $this->receiver_id)
                    ->where('receiver_id', $this->sender_id);
            })
            ->with('sender:id,name', 'receiver:id,name') // Eager load sender and receiver names
            ->orderBy('created_at', 'asc') // Order chronologically
            ->get();

        // Debugging point 1: See what the database returns (full message objects)
        // dd('Fetched Messages from DB:', $fetchedMessages->toArray());

        foreach ($fetchedMessages as $message) {
            $this->appendChatMessage($message);
        }

        // Debugging point 2: See what ends up in $this->messages after appendChatMessage
        // This will show if only the 4 fields were taken
        // dd('Messages after appendChatMessage:', $this->messages);

        $this->user = User::whereId($user_id)->first(); // Get the selected chat partner's details
    }

    public function sendMessage()
    {
        // Simple validation (consider adding Livewire rules for better UX)
        if (empty($this->message)) {
            return; // Don't send empty messages
        }

        $chatMessage = new Message();
        $chatMessage->sender_id = $this->sender_id;
        $chatMessage->receiver_id = $this->receiver_id;
        $chatMessage->message = $this->message;
        $chatMessage->save();

        // Optional: Immediately append the sent message to the local list for instant display
        // You might consider dispatching an event here to trigger the broadcast and let the listener handle appending,
        // which ensures consistency with received messages.
        // For now, this adds it instantly.
        $this->appendChatMessage($chatMessage); // Append the newly saved message


        // IMPORTANT: If you want real-time update for other users and for yourself (if not relying on local append),
        // you would typically dispatch a broadcast event here.
        broadcast(new MessageSendEvent($chatMessage))->toOthers();
        $this->dispatch('message-sent'); // For frontend auto-scroll
        $this->message = ""; // Clear the input field
    }

    #[On('echo-private:chat-channel.{sender_id},MessageSendEvent')]

    public function listenForMessage($event)
    {
        $chatMessage = Message::whereId($event['message']['id'])
            ->with('sender:id,name', 'receiver:id,name')
            ->first();
        $this->appendChatMessage($chatMessage);
    }
    public function appendChatMessage($message)
    {
        // This is where you control what goes into $this->messages for the frontend
        // $this->messages[] = [
        //     'id' => $message->id,
        //     'message' => $message->message, // Access the 'message' attribute from the model
        //     'sender' => $message->sender->name,
        //     'receiver' => $message->receiver->name,
        //     //  'created_at' => $message->created_at->format('Y-m-d H:i:s'), // Add timestamp for display
        // ];

        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender_id' => $message->sender_id, // Add this
            'receiver_id' => $message->receiver_id, // Add this 
            'sender_name' => $message->sender->name, // Add this
            'receiver_name' => $message->receiver->name, // Add this
            'created_at' => $message->created_at->toDateTimeString(), // Add this
        ];

        // Debugging point 3: See what a single message looks like when appended
        // dd('Appending message:', $this->messages[count($this->messages) - 1]);
    }
}
