<div
    {{ $attributes->merge([
        'class' => "mb-4 text-gray-600 bg-gray-100 border-l-4 border-gray-400 px-4 py-3",
        'role' => "alert"
    ]) }}
>
    {{ $slot }}
</div>