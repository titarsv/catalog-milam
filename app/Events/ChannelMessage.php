<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\Profile;

class ChannelMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $message->load('channel');
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(){
        return [
            new PresenceChannel('channel.'.$this->message->channel->customer_id),
            new PresenceChannel('channel.'.$this->message->channel->merchant_id)
        ];
//        return [
//            new PrivateChannel('App.Models.User.'.$this->message->from_id),
//            new PrivateChannel('App.Models.User.'.$this->message->to_id)
//        ];
//        return [
//            new Channel('App.Models.User.'.$this->message->from_id),
//            new Channel('App.Models.User.'.$this->message->to_id)
//        ];
    }

    public function broadcastWith(){
        return [
            'channel_id' => $this->message->channel_id,
            'message' => [
                'id' => $this->message->id,
                'user_id' => $this->message->user_id,
                'avatar' => $this->message->user->image,
                'username' => $this->message->user->full_name,
                'online' => true,
                'body' => $this->message->message,
                'time' => $this->message->created_at->format('d/m/Y'),
                'unread' => !$this->message->read
            ],
            'customer' => $this->message->channel->merchant->getContact($this->message->channel),
            'merchant' => $this->message->channel->customer->getContact($this->message->channel),
        ];
    }
}
