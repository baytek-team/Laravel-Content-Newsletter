<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewsletterPublished implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $newsletter;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($newsletter)
    {
        $this->newsletter = $newsletter;
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('newsletter.'.$this->newsletter->id);
    }
}
