@props([
  'type' => 'text',
  'name' => '',
])

<input
  type="{{ $type }}"
  name="{{ $name }}"
  aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
  aria-describedby="{{ $name }}-error"
  {{ $attributes->merge([
    'class' => 'w-full px-4 py-2.5 rounded-lg border border-gray-300
          focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
          outline-none transition'
  ]) }}
>