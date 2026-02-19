<x-layouts.app>
  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 p-3 mb-4">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf

    <input name="name" placeholder="Name" required>
    <input name="email" type="email" placeholder="Email" required>

    <input name="password" type="password" placeholder="Password" required>
    <input name="password_confirmation" type="password" placeholder="Confirm" required>

    <button>Register</button>

    <a href="{{ route('login') }}">Login</a>
  </form>
</x-layouts.app>