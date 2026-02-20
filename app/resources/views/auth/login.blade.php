<x-layouts.app>

  <div class="min-h-screen grid place-items-center">

    <x-ui.card class="w-full h-screen sm:h-auto sm:max-w-md">

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-ui.header-auth :title="config('app.name')" subtitle="Laravel Gateway" />

        <div>
          <x-form.input
            name="email"
            type="email"
            value="{{ old('email') }}"
            autocomplete="email"
            placeholder="Email"
            required
          />
          @error('email')
            <x-form.input-error>{{ $message }}</x-form.input-error>
          @enderror
        </div>

        <div>
          <x-form.input
            name="password"
            type="password"
            placeholder="Password"
            autocomplete="current-password"
            required
          />
          @error('password')
            <x-form.input-error>{{ $message }}</x-form.input-error>
          @enderror
        </div>

        <x-form.button type="submit" class="w-full font-medium">Login</x-form.button>

        <p class="text-center text-sm text-gray-600">
          Don’t have an account?
          <a href="{{ route('register') }}"
            class="text-blue-600 hover:underline font-medium">
            Register
          </a>
        </p>
      </form>

    </x-ui.card>

  </div>

</x-layouts.app>