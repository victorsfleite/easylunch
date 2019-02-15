<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;

class InvoicePending extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $range;
    public $orders;
    public $total;

    public function __construct(User $user, array $range)
    {
        $this->user   = $user;
        $this->range  = $range;
        $this->orders = Order::betweenDates($range)->ofUser($user)->get();
        $this->total  = $this->orders->sum('price');
    }

    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('mail.invoice.pending', [
            'user'   => $this->user,
            'range'  => $this->range,
            'orders' => $this->orders,
            'total'  => $this->total,
        ]);
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content("{$notifiable->name} você precisa pagar R$ {$this->total} de almoço dessa última semana.")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->title('Clique aqui para mais detalhes', route('menus.report'));
            });
    }
}
