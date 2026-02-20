<div
    {{ $attributes->merge([
        'class' => "mb-4 text-red-700 bg-red-100 border-l-4 border-red-500 px-4 py-3",
        'role' => "alert"
    ]) }}
>
    {{ $slot }}
</div>