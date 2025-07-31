<?php

namespace App\Events;

use App\Models\Borrowing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BorrowingUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

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
        return 'borrowing.updated';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->borrowing->id,
            'user' => $this->borrowing->user->name,
            'status' => $this->borrowing->status,
            'borrow_date' => $this->borrowing->borrow_date->format('d M Y'),
            'return_date' => optional($this->borrowing->return_date)->format('d M Y'),
            'penalty' => $this->borrowing->penalty,
            'items' => $this->borrowing->items->map(fn($item) => [
                'name' => $item->name,
                'quantity' => $item->pivot->quantity,
                'location' => $item->location->name,
            ]),
        ];
    }
}
