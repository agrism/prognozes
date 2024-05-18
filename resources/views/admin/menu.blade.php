@php
    $routeCollection = Illuminate\Support\Facades\Route::getRoutes();

    $adminMenu = [];

    foreach ($routeCollection as $value) {
       if(preg_match('/.index$/', $name = $value->getName())){
           $adminMenu[] = sprintf('<a style="text-decoration: none;color:white;background-color:black;padding:2px;" href="%s">%s</a>', route($name), str_replace('.', ' ', substr($name, 0, -1 * strlen('.index'))) );
       }
    }
@endphp

@foreach($adminMenu ?? [] as $adminMenuItem)
    <li>
        {!! $adminMenuItem !!}
    </li>
@endforeach
