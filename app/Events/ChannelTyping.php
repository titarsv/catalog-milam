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

class ChannelTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;
    protected $channel;

    /**
     * ChannelTyping constructor.
     * @param $user
     * @param $id
     */
    public function __construct($user, $id)
    {
        $this->user = $user;
        $this->channel = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(){
        return new PresenceChannel('channel.'.$this->channel);
    }

    public function broadcastWith(){
        return [
            'user_id' => $this->user->id
        ];
    }
}
