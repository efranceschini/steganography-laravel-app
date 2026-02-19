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

  <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data">
    @csrf

    <div>
      <input name="title" type="text" placeholder="Your Image name" required>
      @error('title')
        <div class="text-red-600">{{ $error }}</div>
      @enderror
    </div>
    <div>
      <label>Image</label>
      <input type="file" name="image" accept=".png" required>
      @error('image')
        <div class="text-red-600">{{ $error }}</div>
      @enderror
    </div>

    <button>Upload</button>
  </form>
</x-layouts.app>