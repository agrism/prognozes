@php
    $routeCollection = Illuminate\Support\Facades\Route::getRoutes();

    $adminMenu = [];

    $link = function(string $url, $name): string{
        return sprintf('<a style="text-decoration: none;color:white;background-color:black;padding:2px;" href="%s">%s</a>', $url, $name);
    };

    if( !($ignoreHome ?? null)){
        $adminMenu[] = $link('/', 'Home');
    }

    foreach ($routeCollection as $value) {
       if(preg_match('/.index$/', $name = $value->getName())){
           $adminMenu[] = $link(route($name), \Illuminate\Support\Str::of($name)->substr(0, -1 * strlen('.index'))->replace('.', ' ')->ucfirst() );
       }
    }
@endphp

@foreach($adminMenu ?? [] as $adminMenuItem)
    <li>
        {!! $adminMenuItem !!}
    </li>
@endforeach
