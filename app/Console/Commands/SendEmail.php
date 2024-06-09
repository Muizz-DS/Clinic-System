<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateEmail;
use App\Models\Appointment;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $appointments = Appointment::with('user')->get();
        foreach($appointments as $key => $appointment){
            if(Carbon::parse($appointment->booked_at)->diffInDays(Carbon::now()) == 1){ 
                Mail::to($appointment->user->email)->send(new UpdateEmail($appointment->user));
            }
        }
    }
}
