<?php

namespace App\Notifications;

use App\Models\reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationDeclinedNotification extends Notification
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $startDate = date('F j, Y', strtotime($this->reservation->start_date));
        $endDate = date('F j, Y', strtotime($this->reservation->end_date));

        return (new MailMessage)
            ->subject('Reservation Update - EasyStay')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We regret to inform you that your reservation request has been declined by the owner.')
            ->line('Property: ' . $this->reservation->appartement->title)
            ->line('Check-in: ' . $startDate)
            ->line('Check-out: ' . $endDate)
            ->action('Find Other Properties', url('/appartements_index'))
            ->line('We encourage you to explore other great properties on EasyStay.')
            ->line('Thank you for your understanding.');
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