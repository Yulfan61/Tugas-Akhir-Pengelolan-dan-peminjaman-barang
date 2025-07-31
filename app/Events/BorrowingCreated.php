<?php

namespace App\Events;

use App\Models\Borrowing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BorrowingCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $borrowing;

    public function __construct(Borrowing $borrowing)
    {
        $this->borrowing = $borrowing->load('user', 'items.location');
    }

    public function broadcastOn()
    {
        return new Channel('borrowings');
    }

    public function broadcastAs()
{
    return 'borrowing.created';
}


    public function broadcastWith()
    {
        return [
            'id' => $this->borrowing->id,
            'user' => $this->borrowing->user->name,
            'status' => $this->borrowing->status,
            'borrow_date' => $this->borrowing->borrow_date->format('d M Y'),
            'return_date' => optional($this->borrowing->return_date)->format('d M Y'),
            'items' => $this->borrowing->items->map(fn($item) => [
                'name' => $item->name,
                'quantity' => $item->pivot->quantity,
                'location' => $item->location->name,
            ]),
        ];
    }
}
