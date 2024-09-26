<?php

namespace App\Livewire;

use App\Events\MessageSentEvent;
use App\Models\Message;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatComponent extends Component
{
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = "";
    public $messages = [];
    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id)
    {
        $this->sender_id = auth()->id();
        $this->receiver_id = $user_id;

        $messages = Message::where(function ($query) {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->receiver_id)
                ->where('receiver_id', $this->sender_id);
        })->with('sender:id,name', 'receiver:id,name')->get();

        foreach ($messages as $message) {
            $this->appendChatMessage($message);
        }

        // dd($this->messages);
        $this->user =  User::whereId($user_id)->first();
    }

    public function sendMessage()
    {
        // dd($this->message);
        $chat_message = new Message();
        $chat_message->sender_id = $this->sender_id;
        $chat_message->receiver_id = $this->receiver_id;
        $chat_message->message = $this->message;
        $chat_message->save();
        $this->appendChatMessage($chat_message);

        broadcast(new MessageSentEvent($chat_message))->toOthers();
        $this->message = '';
    }

    #[On('echo-private:chat-channel.{sender_id},MessageSentEvent')]
    public function listenForMessage($event)
    {
        $chat_message = Message::whereId($event['message']['id'])
            ->with('sender:id,name', 'receiver:id,name')
            ->first();
        // dd($chat_message);
        $this->appendChatMessage($chat_message);
    }

    public function appendChatMessage($message)
    {
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name
        ];
    }
}
