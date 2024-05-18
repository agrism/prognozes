@php
    /**
     * @var \App\ValueObjects\AdminTable\AdminTableRow $row
     */
@endphp
@if($row ?? false)
    {!! '<div style="border: 1px solid black; border-radius: 5px; padding: 20px;">' !!}
    {!!'<form hx-put="'.route('admin.games.store', $row->id).'">'!!}

    @foreach ($row->cells as $cell)
        @if($cell->isSelect)
            <div>
                <div style="display: inline-block; width: 120px;">
                    {{$cell->columnName}}
                </div>
                <select name="{{$cell->columnName}}" style="margin-left: -4px;min-width: 200px;">
                    @foreach($cell->options as $key => $value)
                    <option value="{{$key}}"
                            @if($cell->value === $key) selected @endif
                    >{{$value}}</option>
                        @endforeach
                </select>
            </div>
            @continue
        @endif

        @if($cell->isAction)
{{--            {!! $cell->value !!}--}}
            @continue
        @endif

        @if($cell->value)
            {!! '<div><div style="display: inline-block; width: 120px;">'.$cell->columnName.':</div><input style="min-width:192;" type="'.($cell->isDate ? 'datetime-local' : 'text').'" name="'.$cell->columnName.'" value="'.$cell->value.'"></div>' !!}
        @endif
    @endforeach

    {!! '<button>submit</button>'!!}
    {!! '</form>' !!}
    {!! '</div>'!!}
@else

    <x-layouts.admin>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <h1>{{$adminTable->title}}</h1>
                        <table
                            style="border:1px solid black"
                            class="min-w-full text-left text-sm font-light text-surface dark:text-black">
                            @foreach($adminTable->rows as $index => $adminTableRow)
                                {{--        @dump($model->getAttributes())--}}

                                @if($index === 0)
                                    <thead class="border-b border-neutral-200 font-medium dark:border-white/10">
                                    <tr>
                                        @foreach($adminTableRow->cells as $cell)
                                            <th>
                                                {{$cell->columnName}}
                                            </th>
                                        @endforeach
                                    </tr>

                                    </thead>
                                @endif

                                <tbody>

                                <tr class="border-b border-neutral-200 dark:border-white/10">
                                    @foreach($adminTableRow->cells as $cell)
                                        <td>
                                            @if($cell->isSelect)
                                                {!! data_get($cell->options, $cell->value) !!}
                                            @else
                                                {!! $cell->value !!}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </table>

        <div id="edit-form-place"
        "></div>
    </x-layouts.admin>
@endif


