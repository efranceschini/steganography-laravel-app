<x-layouts.dashboard>

  <div class="container mx-auto sm:px-4 sm:py-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 sm:gap-6">

      {{-- Left Col: Images --}}
      <x-ui.card>
        <x-ui.title-section>Your Images</x-ui.title-section>

        <p class="text-gray-600 mb-4">
          List of images you can use for encoding. <a href="{{ route('images.create') }}" class="text-blue-600 hover:underline font-medium">Add new image</a>
        </p>

        @if (session('success'))
          <x-alerts.success>
            {{ session('success') }}
          </x-alerts.success>
        @endif

        @if ($images->isNotEmpty())
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 font-medium">
              <tr>
                <th scope="col" class="w-[100px]">Image</th>
                <th scope="col" class="px-4 py-2 text-left">Title</th>
                <th scope="col" class="px-4 py-2">Size</th>
                <th scope="col" class="w-1 whitespace-nowrap">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach ($images as $image)
                <tr>
                  <td>
                    <img src="{{ Storage::disk('s3')->url($image->path) }}" alt="{{ $image->title }}" class="w-[100px] h-[100px] object-cover">
                  </td>
                  <td class="px-4">
                    {{ $image->title }}
                  </td>
                  <td class="px-4 text-center">
                    {{ $image->human_filesize() }}
                  </td>
                  <td class="px-4 text-center whitespace-nowrap">
                    @can('delete', $image)
                    <form method="POST" action="{{ route('images.destroy', $image) }}">
                      @csrf
                      @method('DELETE')
                      <x-form.button
                        type="submit"
                        color="red"
                        onclick="return confirm(this.dataset.confirm)"
                        data-confirm="Delete image {{ $image->title }}?"
                      >
                        Delete
                      </x-form.button>
                    </form>
                    @endcan
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="mt-4">
            {{ $images->links() }}
          </div>
        </div>
        @else
        <x-alerts.info>
          You don't have any images.
        </x-alerts.info>
        @endif
      </x-ui.card>

      {{-- Right Col: Operations --}}
      <div class="sm:space-y-6" id="app" >

        {{-- Encode --}}
        <x-ui.card>

          <x-ui.title-section>Encode Image</x-ui.title-section>

          <p class="text-gray-600 mb-2">
            Use the following form to encode a message in one of your images.
          </p>

          <x-alerts.danger v-if="encodeForm.error" v-cloak>
            @{{ encodeForm.error }}
          </x-alerts.danger>

          <x-alerts.info v-if="encodeForm.loading" v-cloak>
            Loading, please wait...
          </x-alerts.info>

          <form @submit.prevent="encode" class="space-y-4">

            <div>
              <x-form.select v-model="encodeForm.encoding" name="encoding" placeholder="Select Encoding" required>
                <option value="">Select Encoding</option>
                @foreach ($encodings as $encoding)
                <option value="{{ $encoding }}">{{ Str::title($encoding) }}</option>
                @endforeach
              </x-form.select>
            </div>

            <div>
              <x-form.select v-model="encodeForm.imageId" name="image" required>
                <option value="">Select Image</option>
                @foreach ($allImages as $image)
                <option value="{{ $image->id }}">{{ $image->title }}</option>
                @endforeach
              </x-form.select>
            </div>

            <div>
              <x-form.textarea v-model="encodeForm.message" name="message" placeholder="Message" required></x-form.textarea>
            </div>

            <x-form.button type="submit" ::disabled="encodeForm.loading">Encode</x-form.button>
          </form>

        </x-ui.card>


        {{-- Decode --}}
        <div class="bg-white p-6 sm:rounded-lg sm:shadow">

          <x-ui.title-section>Decode Image</x-ui.title-section>

          <x-alerts.danger v-if="decodeForm.error" v-cloak>
            @{{ decodeForm.error }}
          </x-alerts.danger>

          <x-alerts.info v-if="decodeForm.loading" v-cloak>
            Loading, please wait...
          </x-alerts.info>

          <x-alerts.success v-if="decodeForm.message" v-cloak>
            <strong>Decoded message:</strong> @{{ decodeForm.message }}
          </x-alerts.success>

          <p class="text-gray-600 mb-2">
            Use the following form to decode a message from an image.
          </p>

          <form @submit.prevent="decode" class="space-y-4">

            <div>
              <x-form.select v-model="decodeForm.encoding" name="encoding" placeholder="Select Encoding" required>
                <option value="">Select Encoding</option>
                @foreach ($encodings as $encoding)
                <option value="{{ $encoding }}">{{ Str::title($encoding) }}</option>
                @endforeach
              </x-form.select>
            </div>

            <div>
              <label class="flex items-center px-4 py-2.5 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 relative">
                  @{{ decodeForm.image?.name ?? "Upload Image"}}
                  <input type="file" @change="onDecodeImageChange" name="image" accept=".png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
              </label>
            </div>

            <x-form.button type="submit" ::disabled="decodeForm.loading">Decode</x-form.button>

          </form>

        </div>

      </div>
    </div>
  </div>
</x-layouts.dashboard>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
  const { createApp, reactive } = Vue
  const imageTitles  = {!! $allImages->toJson() !!};

  createApp(
  {
    setup()
    {
      const encodeForm = reactive({
          encoding: "",
          imageId: "",
          message: "",
          error: null,
          loading: false,
      });

      const decodeForm = reactive({
          encoding: "",
          image: null,
          message: "",
          error: null,
      });

      const onDecodeImageChange = (event) => {
        decodeForm.image = event.target.files[0]
      }

      const encode = async () =>
      {
        encodeForm.error = null
        encodeForm.loading = true

        try {
          const formData = new FormData()
          formData.append('image_id', encodeForm.imageId)
          formData.append('message', encodeForm.message)
          formData.append('encoding', encodeForm.encoding)

          const response = await fetch('{{ route("api.encode") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json, image/png',
            },
            body: formData
          })

          if (!response.ok) {
            const json = await response.json()
            throw new Error(json?.error || "An error occured while encoding the image")
          }

          // Filename
          const selectedId = encodeForm.imageId;
          const baseTitle = (imageTitles.find(e => e?.id == selectedId)?.title || 'image') + '_encoded';
          const safeTitle = baseTitle.replace(/[^a-z0-9]/gi, '_').toLowerCase();

          // Trigger download
          const blob = await response.blob();
          const url = window.URL.createObjectURL(blob);
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', `${safeTitle}.png`);
          document.body.appendChild(link);
          link.click();
          link.remove();
          window.URL.revokeObjectURL(url);

        } catch (e) {
          encodeForm.error = e
          console.error(e)
        } finally {
          encodeForm.loading = false
        }
      }

      const decode = async () =>
      {
        decodeForm.error = null
        decodeForm.loading = true
        decodeForm.message = ""

        const formData = new FormData()
        formData.append('encoding', decodeForm.encoding)
        formData.append('image', decodeForm.image)

        try {
          const response = await fetch('{{ route("api.decode") }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
            },
            body: formData
          })

          if (!response.ok) {
            const text = await response.text()
            throw new Error(`API error: ${text}`)
          }

          const result = await response.json();
          decodeForm.message = await result?.data?.message;

        } catch (e) {
          decodeForm.error = "The image doesn't have a message."
          console.error(e)
        } finally {
          decodeForm.loading = false
        }
      }

      return {
        encodeForm,
        decodeForm,
        decode,
        encode,
        onDecodeImageChange
      }
    }
  }).mount('#app')
</script>
