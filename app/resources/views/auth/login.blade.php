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

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
      <input name="email" type="email" placeholder="Email" required>
      @error('email')
        <div class="text-red-600">{{ $message }}</div>
      @enderror
    </div>
    <div>
      <input name="password" type="password" placeholder="Password" required>
      @error('password')
        <div class="text-red-600">{{ $message }}</div>
      @enderror
    </div>

    <button>Login</button>

    <a href="{{ route('register') }}">Register</a>
  </form>
</x-layouts.app>