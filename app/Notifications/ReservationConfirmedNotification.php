<?php

namespace App\Notifications;

use App\Models\reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmedNotification extends Notification
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
            ->subject('Your Reservation is Confirmed - EasyStay')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Good news! Your reservation has been confirmed by the owner.')
            ->line('Property: ' . $this->reservation->appartement->title)
            ->line('Check-in: ' . $startDate)
            ->line('Check-out: ' . $endDate)
            ->line('Total Price: $' . number_format($this->reservation->total_price, 2))
            ->action('View Reservation Details', url('/client/profile/' . $notifiable->id))
            ->line('We hope you enjoy your stay!')
            ->line('Thank you for choosing EasyStay.');
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