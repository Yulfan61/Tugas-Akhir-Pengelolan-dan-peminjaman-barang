<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ItemStockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function broadcastOn()
    {
        return new Channel('stock-channel');
    }

    public function broadcastWith()
    {
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'location_id' => $this->item->location_id,
            'location_name' => $this->item->location->name ?? 'Tidak diketahui',
            'stock' => $this->item->stock,
        ];
    }

    public function broadcastAs()
    {
        return 'item.stock.updated';
    }
}
