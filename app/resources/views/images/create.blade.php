<x-layouts.dashboard>

  <div class="container mx-auto sm:px-4 sm:py-6">
    <x-ui.card class="max-w-3xl mx-auto">

      <x-ui.title-section>Upload Images</x-ui.title-section>

      <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data"  class="space-y-4">
        @csrf
        <div>
          <x-form.input name="title" type="text" value="{{ old('title') }}" placeholder="Your Image name" required></x-form.input>
          @error('title')
            <x-form.input-error>{{ $message }}</x-form.input-error>
          @enderror
        </div>
        <div>
          <label class="flex items-center px-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 relative">
            <span id="file-name">Upload Image</span>
            <input type="file" name="image" accept=".png"
              onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Upload Image'"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
          </label>
          @error('image')
            <x-form.input-error>{{ $message }}</x-form.input-error>
          @enderror
        </div>

        <x-form.button type="submit">Upload</x-form.button>
      </form>
    </x-ui.card>
  </div>

</x-layouts.dashboard>