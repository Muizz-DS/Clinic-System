<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class HistoryController extends Controller
{
    public function showHistory(){
        $activities = Activity::causedBy(auth()->user())->get();
        return view( 'histories.index',compact('activities'));
    }
}
