<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function show(Activity $activity): View
    {
        return view('activities.show', compact('activity'));
    }
}
