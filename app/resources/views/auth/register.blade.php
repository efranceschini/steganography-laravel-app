<x-layouts.app>

  <div class="min-h-screen grid place-items-center">

    <x-ui.card class="w-full h-screen sm:h-auto sm:max-w-md">

      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-ui.header-auth title="Registration" subtitle="Laravel Gateway" />

        <div>
          <x-form.input name="name" value="{{ old('name') }}" placeholder="Name" required />
          <x-form.input-error name="name" />
        </div>

        <div>
          <x-form.input name="email" type="email" value="{{ old('email') }}" placeholder="Email" required />
          <x-form.input-error name="email" />
        </div>

        <div>
          <x-form.input name="password" type="password" placeholder="Password" required />
          <x-form.input-error name="password" />
        </div>

        <div>
          <x-form.input name="password_confirmation" type="password" placeholder="Confirm Password" required />
          <x-form.input-error name="password_confirmation" />
        </div>

        <x-form.button type="submit" class="w-full font-medium">Register</x-form.button>

        <p class="text-center text-sm text-gray-600">
          Already registered?
          <a href="{{ route('login') }}"
            class="text-blue-600 hover:underline font-medium">
            Login
          </a>
        </p>

      </form>
    </x-ui.card>

  </div>
</x-layouts.app>