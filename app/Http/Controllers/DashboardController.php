<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;


class DashboardController extends Controller
{
    public function show(){

        $user = auth()->user();
        if ($user->getRoleNames()->implode("") == "admin" || $user->getRoleNames()->implode("") == "doctor") {
            $user_appointments = Appointment::all();

        }else{
            $user_appointments = Appointment::with('user')->where('user_id', auth()->id())->get();
        }

        // return compact('user_appointments');
        // return $user_appointments;
        return view('layouts.app', compact('user_appointments'));
    }
}
