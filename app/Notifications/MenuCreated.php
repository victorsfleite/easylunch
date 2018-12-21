<?php

namespace App\Notifications;

use App\Models\Menu;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class MenuCreated extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->success()
            ->content('#### PODEM MANDAR OS PEDIDOS DE ALMOÇO!!! ####')
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->title('Clique aqui para ver o menu de hoje', route('menus.today'));
            });
    }
}
