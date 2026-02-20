@props([
    'type' => 'text'
])

<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2.5 rounded-lg border border-gray-300
                    focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                    outline-none transition'
    ]) }}
>