<x-layouts.app>
  welcome
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button>Logout</button>
  </form>

  <h1>Your Images</h1>

  @if (session('success'))
    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 px-4 py-3 rounded relative">
      {{ session('success') }}
    </div>
  @endif

  <a href="{{ route('images.create') }}">Add Image</a>

  @if (count($images))
    <table>
      <thead>
        <tr>
          <th>Id</th>
          <th>Image</th>
          <th>Title</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($images as $image)
          <tr>
            <td>{{ $image->id }} </td>
            <td>
              <img src="{{ Storage::disk('s3')->url($image->path) }}">
            </td>
            <td>
              {{ $image->title }}
            </td>
            <td>
              @can('delete', $image)
              <form method="POST" action="{{ route('images.destroy', $image) }}">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Delete image {{ $image->title }}?')">
                  Delete
                </button>
              </form>
              @endcan
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $images->links() }}
  @else
    No images
  @endif




  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

  <div id="app">
    <h1>Encrypt Image</h1>
    <form @submit.prevent="encode">
      <div>
        <input v-model="encodeEncoding" name="encoding" type="text" placeholder="Encoding" required>
      </div>
      <div>
        <label>Image</label>
        <input v-model="encodeImageId" name="image" type="text" placeholder="Image" required>
      </div>
      <div>
        <input v-model="encodeMessage" name="message" type="text" placeholder="Message" required>
      </div>
      <button>Encode</button>
    </form>


    <h1>Decrypt Image</h1>
    <form @submit.prevent="decode">
      <div>
        <input v-model="decodeEncoding" name="encoding" type="text" placeholder="Encoding" required>
      </div>
      <div>
        <label>Image</label>
        <input type="file" @change="onDecodeImageChange" name="image" accept=".png" required>
      </div>
      <button>Decode</button>
    </form>

  </div>

  <script>
    const { createApp, ref } = Vue

    createApp(
    {
      setup()
      {
        const encodeEncoding = ref("bit")
        const encodeImageId = ref("5")
        const encodeMessage = ref("Test message")
        const decodeEncoding = ref("bit")
        const decodeImage = ref(null)


        const onDecodeImageChange = (event) => {
          decodeImage.value = event.target.files[0]
        }

        const encode = async () =>
        {
          try {
            const formData = new FormData()
            formData.append('image_id', encodeImageId.value)
            formData.append('message', encodeMessage.value)
            formData.append('encoding', encodeEncoding.value)

            const response = await fetch('/api/encode', {
              method: 'POST',
              credentials: 'include',
              body: formData
            })

            if (!response.ok) {
              const text = await response.text()
              throw new Error(`API error: ${text}`)
            }
            console.log(response.json)
          } catch (err) {
            console.error(err)
            alert("Failed to decode image")
          }
        }

        const decode = async () =>
        {
          const formData = new FormData()
          formData.append('encoding', decodeEncoding.value)
          formData.append('image', decodeImage.value)

          try {
            const response = await fetch('/api/decode', {
              method: 'POST',
              credentials: 'include',
              body: formData
            })

            if (!response.ok) {
              const text = await response.text()
              throw new Error(`API error: ${text}`)
            }
            console.log(response.json)
          } catch (err) {
            console.error(err)
            alert("Failed to decode image")
          }
        }

        return {
          encodeEncoding,
          encodeImageId,
          encodeMessage,
          decodeEncoding,
          decode,
          encode,
          onDecodeImageChange
        }
      }
    }).mount('#app')
  </script>

</x-layouts.app>