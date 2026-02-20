@props([
  'color' => 'blue',
  'type' => 'button',
])

@php
$colors = [
  'blue' => 'bg-blue-600 hover:bg-blue-700',
  'red' => 'bg-red-600 hover:bg-red-700',
  'green' => 'bg-green-600 hover:bg-green-700',
];
$colorClasses = $colors[$color] ?? $colors['blue'];
@endphp


<button
  type="{{ $type }}"
  {{ $attributes->merge([
    'class' => "px-4 py-2 rounded-lg text-white active:scale-[0.99] transition $colorClasses disabled:opacity-50"
  ]) }}
>
  {{ $slot }}
</button>