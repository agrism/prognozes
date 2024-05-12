<x-layouts.app>

        {{-- EXAMPLE: this section contains forms and routes not readily accesible upon intital installation. It is meant to be repalced --}}
        <section class="flex justify-center">
{{--            <div>--}}
{{--                <li>--}}
{{--                    <div><x-flag-4x3-be class="w-8"/></div>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <div><x-flag-4x3-lv class="w-8"/></div>--}}
{{--                </li>--}}
{{--            </div>--}}

            <div class="flex min-h-screen items-center1 py-3 justify-center">
                <div class="overflow-x-auto">
                    <table class="min-w-full shadow-md">
                        <thead>
                        <tr class="bg-gray-300 text-gray-700">
                            <th class="py-3 px-4 text-left">Home</th>
                            <th class="py-3 px-4 text-left">Away</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Result</th>
                            <th class="py-3 px-4 text-left">Forecast</th>
                        </tr>
                        </thead>
                        <tbody class="text-blue-gray-900 bg-blue-100">
                        @foreach($games as $game)
                            <tr class="border-b border-blue-gray-900">
                                <td class="py-3 px-4">
                                    <div style="display: inline-block">
                                        {!! svg('flag-4x3-' . \Illuminate\Support\Str::of(data_get($game, 'teamHome.code'))->lower()->value(), ['class' => 'w-6'])->toHtml() !!}
                                    </div>
                                    <div style="display: inline-block">
{{--                                    {{ \CountryEnums\Country::from(data_get($game, 'teamHome.code'))?->label() }}--}}
                                    </div>
                                </td>

                                <td class="py-3 px-4">
                                    <div style="display: inline-block">
                                    {!! svg('flag-4x3-' . \Illuminate\Support\Str::of(data_get($game, 'teamAway.code'))->lower()->value(), ['class' => 'w-6'])->toHtml() !!}
                                    </div>
                                    <div style="display: inline-block">
{{--                                    {{ \CountryEnums\Country::from(data_get($game, 'teamAway.code'))?->label() }}--}}
                                    </div>
                                </td>
                                <td class="py-3 px-4">{{data_get($game, 'date')->setTimezone("Europe/Riga")->format('d M H:i')}}</td>
                                <td class="py-3 px-4">{{data_get($game, 'result')}}</td>
                                <td>
                                    <div id="{{$id = uniqid().time()}}">

                                        @if(data_get($game, 'date')->gt(now()))

                                        <a hx-get="{{route('forecasts.edit', data_get($game, 'id'))}}"
                                           hx-swap="outerHTML"
                                           hx-target="[id='{{$id}}']"
                                           class="font-medium text-blue-600 hover:text-blue-800 cursor-pointer">Edit</a>
                                        @else
                                            {{data_get($game, 'forecast.result', '-')}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
</x-layouts.app>
