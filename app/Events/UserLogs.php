<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLogs
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userid;
    public $module;
    public $activity;
    public $request;
    public $status;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userid, $module, $activity, $request, $status)
    {
        $this->userid = $userid;
        $this->module = $module;
        $this->activity = $activity;
        $this->request = $request;
        $this->status = $status;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
