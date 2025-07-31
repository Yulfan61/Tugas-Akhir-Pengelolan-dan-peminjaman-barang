<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class BorrowingDeleted implements ShouldBroadcastNow
{
    use SerializesModels;

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return new Channel('borrowings');
    }

    public function broadcastAs()
    {
        return 'BorrowingDeleted'; // penting: match dengan JS
    }

    public function broadcastWith()
    {
        return ['id' => $this->id];
    }
}
