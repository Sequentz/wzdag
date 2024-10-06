<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('images.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View images</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add image') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div>
        <label for="image" class="block mb-2 text-3xl font-medium text-gray-900">Upload an image:</label>

        <!-- Custom Styled File Input -->
        <label for="image" class="cursor-pointer inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Choose File
        </label>
        <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)" class="hidden">
        <span id="fileName" class="ml-4 text-gray-700"></span>
      </div>

      <!-- Image Preview Section -->
      <div class="mt-4">
        <img id="imagePreview" class="w-48 h-48 object-cover mt-2 hidden" />
      </div>

      <!-- Upload Button -->
      <button id="uploadButton" type="submit" class="hidden align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
        Upload
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

<!-- JavaScript for Image Preview and Upload Button -->
<script>
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    const uploadButton = document.getElementById('uploadButton');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden'); // Show the image preview
      };
      reader.readAsDataURL(input.files[0]); // Read the image file as a data URL

      // Display the file name beside the button
      fileName.textContent = input.files[0].name;

      // Show the Upload button
      uploadButton.classList.remove('hidden');
    } else {
      // Hide the preview and upload button if no file is selected
      preview.classList.add('hidden');
      uploadButton.classList.add('hidden');
      fileName.textContent = '';
    }
  }
</script>