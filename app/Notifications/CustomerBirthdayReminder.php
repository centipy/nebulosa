<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Customer;

class CustomerBirthdayReminder extends Notification implements ShouldQueue
{
     use Queueable;

     public $customer;

     /**
      * Create a new notification instance.
      */
     public function __construct(Customer $customer)
     {
          $this->customer = $customer;
     }

     /**
      * Get the notification's delivery channels.
      *
      * @return array<int, string>
      */
     public function via(object $notifiable): array
     {
          return ['database']; // Solo guardaremos en la base de datos
     }

     /**
      * Get the array representation of the notification.
      *
      * @return array<string, mixed>
      */
     public function toArray(object $notifiable): array
     {
          // El año del cumpleaños se ajusta al año actual para la lógica de "vencidas"
          $birthdayThisYear = $this->customer->birthday->setYear(now()->year);

          return [
               'customer_id' => $this->customer->id,
               'customer_name' => $this->customer->full_name,
               'birthday_date' => $birthdayThisYear->format('Y-m-d'),
               'message' => "Mañana es el cumpleaños de {$this->customer->full_name}!",
          ];
     }
}
