<?php

namespace App\Http\Controllers\Forecasts;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\View\View as IlluminateView;

class IndexController extends Controller
{
    public function __invoke(): Application|Factory|View|FoundationApplication|IlluminateView
    {
        return [];
    }

}
