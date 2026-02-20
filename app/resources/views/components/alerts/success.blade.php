<div
    {{ $attributes->merge([
        'class' => "mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 px-4 py-3",
        'role' => "alert"
    ]) }}
>
    {{ $slot }}
</div>