<x-layouts.app>

  <header class="w-full bg-gray-800 shadow px-6 py-4 flex items-center justify-between">
    {{-- Left: Title --}}
    <h1 class="text-xl font-semibold text-white">
      <a href="{{ route('dashboard') }}" class="hover:underline">{{ config('app.name') }}</a>
    </h1>

    {{-- Right: Logout button --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button
          type="submit"
          class="px-4 py-2 rounded-lg text-white font-medium
              hover:bg-blue-500 active:scale-[0.98] transition"
      >
        Logout
      </button>
    </form>
  </header>

  {{ $slot }}

</x-layouts.app>