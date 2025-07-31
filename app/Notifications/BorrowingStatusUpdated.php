<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Carbon;

class BorrowingStatusUpdated extends Notification
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
            'message' => "Status peminjaman Anda diperbarui menjadi {$this->borrowing->status}",
            'link' => route('borrowings.show', $this->borrowing->id),
            'time' => Carbon::now()->timezone('Asia/Jakarta')->format('H:i'),
        ]);
    }

    public function toArray($notifiable)
{
    return [
        'message' => "Status peminjaman Anda diperbarui menjadi {$this->borrowing->status}",
        'link' => route('borrowings.show', $this->borrowing->id),
    ];
}

}
