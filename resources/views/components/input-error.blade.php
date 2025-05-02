@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'list-group text-sm text-danger space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="d-block">{{ $message }}</li>
        @endforeach
    </ul>
@endif
