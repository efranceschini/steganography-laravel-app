@props([
  'title',
  'subtitle' => null,
])

<div class="text-center space-y-2">
  <h1 class="text-2xl font-semibold">{{ $title }}</h1>
  @isset($subtitle)
    <p class="text-gray-500 text-sm">{{ $subtitle }}</p>
  @endisset
</div>