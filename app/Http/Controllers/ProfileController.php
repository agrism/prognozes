<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Http\Request;
use Illuminate\View\View as IlluminateView;

class ProfileController extends Controller
{
    public function __invoke(): Application|Factory|View|FoundationApplication|IlluminateView
    {
        return view('profile.index');
    }
}
