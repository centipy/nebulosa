<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\CustomerBirthdayReminder;
use Illuminate\Support\Facades\Notification;


class SendBirthdayReminders extends Command
{
     /**
      * The name and signature of the console command.
      *
      * @var string
      */
     protected $signature = 'notifications:send-birthday-reminders';

     /**
      * The console command description.
      *
      * @var string
      */
     protected $description = 'Sends notifications to users about customer birthdays happening tomorrow.';

     /**
      * Execute the console command.
      */
     public function handle()
     {
          $this->info('Checking for upcoming birthdays...');

          $tomorrow = now()->addDay();

          $customersWithBirthdayTomorrow = Customer::whereMonth('birthday', $tomorrow->month)
               ->whereDay('birthday', $tomorrow->day)
               ->get();

          if ($customersWithBirthdayTomorrow->isEmpty()) {
               $this->info('No customer birthdays tomorrow.');
               return 0;
          }

          $users = User::all();

          if ($users->isEmpty()) {
               $this->warn('No users found to notify.');
               return 0;
          }

          foreach ($customersWithBirthdayTomorrow as $customer) {
               $this->info("Found birthday for: {$customer->full_name}");
               Notification::send($users, new CustomerBirthdayReminder($customer));
          }

          $this->info('Birthday reminder notifications sent successfully.');
          return 0;
     }
}
