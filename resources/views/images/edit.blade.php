<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('images.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View images</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit image') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <!-- Edit form for updating the image -->
    <form action="{{ route('images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div>
        <label for="image" class="block mb-2 text-3xl font-medium text-gray-900">Edit Image:</label>

        <!-- Custom Styled File Input -->
        <label for="image" class="cursor-pointer inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Choose New File
        </label>
        <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)" class="hidden">
      </div>

      <!-- Current Image Preview -->
      <div class="mt-4">
        <p class="font-medium text-gray-700">Current Image:</p>
        <img id="currentImage" src="{{ asset('storage/' . $image->file_path) }}" class="w-48 h-48 object-cover mt-2" />
      </div>

      <!-- Image Preview Section for New Image -->
      <div class="mt-4">
        <p class="font-medium text-gray-700 hidden" id="newImageText">New Image Preview:</p>
        <img id="imagePreview" class="w-48 h-48 object-cover mt-2 hidden" />
      </div>

      <!-- Submit Button -->
      <button id="uploadButton" type="submit" class="align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
        Save Changes
      </button>
    </form>

    @if(session('success'))
    <p class="text-green-500">{{ session('success') }}</p>
    @endif

    @if($errors->any())
    <ul>
      @foreach($errors->all() as $error)
      <li class="text-red-500">{{ $error }}</li>
      @endforeach
    </ul>
    @endif
  </div>

</x-app-layout>

<!-- JavaScript for Image Preview and File Names -->
<script>
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const newImageText = document.getElementById('newImageText');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden'); // Show the new image preview
        newImageText.classList.remove('hidden'); // Show the "New Image Preview" text
      };
      reader.readAsDataURL(input.files[0]); // Read the image file as a data URL
    } else {
      // If no new image is selected, hide the new preview and reset
      preview.classList.add('hidden');
      newImageText.classList.add('hidden');
    }
  }
</script>