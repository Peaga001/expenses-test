<?php

namespace App\Notifications;

//Models
use App\Models\Expense;

//Utils
use App\Utils\{
    PriceUtils,
    DateUtils
};

//Miscellaneous
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class CreatedExpense extends Notification
{
    use Queueable;

    public function __construct(
        protected Expense $expense
    )
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $value       = PriceUtils::formatValueToDisplay($this->expense->getAttribute('value'));
        $date        = DateUtils::formatDateToDisplay($this->expense->getAttribute('date'));
        $description = $this->expense->getAttribute('description');
        $user        = $this->expense->user()->first()->getAttribute('name');

        return (new MailMessage)
                    ->line("Olá $user, nova despesa disponível!")
                    ->line("$description")
                    ->line("Valor: $value - Vencimento: $date")
                    ->subject('Despesa Cadastrada');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
