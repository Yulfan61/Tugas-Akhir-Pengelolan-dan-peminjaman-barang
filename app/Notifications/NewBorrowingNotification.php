<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Carbon;

class NewBorrowingNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $borrowing;

    public function __construct(Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Permintaan peminjaman baru dari {$this->borrowing->user->name}",
            'link' => route('borrowings.show', $this->borrowing->id),
            'time' => Carbon::now()->timezone('Asia/Jakarta')->format('H:i'),
        ]);
    }

    public function toArray($notifiable)
{
    return [
        'message' => "Permintaan peminjaman baru dari {$this->borrowing->user->name}",
        'link' => route('borrowings.show', $this->borrowing->id),
    ];
}


    public function broadcastType()
    {
        return 'new-borrowing';
    }
}
