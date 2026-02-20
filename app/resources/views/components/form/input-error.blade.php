@props([
  'name' => '',
])

@error($name)
<p id="{{ $name }}-error"
  {{ $attributes->merge([
    'class' => 'mt-1 text-sm text-red-600'
  ]) }}
>
{{ $message }}
</p>
@enderror