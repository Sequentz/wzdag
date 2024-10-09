<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('themes.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View Themes</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Theme') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">

    <form action="{{ route('themes.update', $theme->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div>
        <label for="name" class="block mb-2 text-3xl font-medium text-gray-900">Edit Theme Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $theme->name) }}" required class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

      <!-- Current Images Section -->
      <div class="mt-4">
        <label class="block mb-2 text-3xl font-medium text-gray-900">Current Images:</label>
        <div id="imagePreviewContainer" class="flex space-x-4">
          @foreach($images as $image)
          <div class="relative" id="existing-image-{{ $image->id }}">
            <img src="{{ asset('storage/' . $image->file_path) }}" alt="Image" class="w-32 h-32 object-cover border rounded-lg">
            <form action="{{ route('images.destroy', $image->id) }}" method="POST" class="absolute top-0 right-0">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                &times;
              </button>
            </form>
          </div>
          @endforeach
        </div>
      </div>


      <div class="mt-4">
        <label for="images" class="block mb-2 text-3xl font-medium text-gray-900">Upload Additional Images:</label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple onchange="previewAdditionalImages(event)" class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>


      <div id="additionalImagePreviewContainer" class="flex space-x-4 mt-4"></div>


      <button type="submit" class="align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
        Save Changes
      </button>
    </form>

    @if(session('success'))
    <p class="text-green-500 mt-4">{{ session('success') }}</p>
    @endif

    @if($errors->any())
    <ul class="mt-4">
      @foreach($errors->all() as $error)
      <li class="text-red-500">{{ $error }}</li>
      @endforeach
    </ul>
    @endif
  </div>
</x-app-layout>

<!-- JavaScript to handle image preview for additional uploads -->
<script>
  function previewAdditionalImages(event) {
    const input = event.target;
    const previewContainer = document.getElementById('additionalImagePreviewContainer');

    if (input.files && input.files.length > 0) {
      Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
          // Create a div to hold the image and the delete button
          const imageContainer = document.createElement('div');
          imageContainer.classList.add('relative');

          // Create image preview
          const img = document.createElement('img');
          img.src = e.target.result;
          img.classList.add('w-32', 'h-32', 'object-cover', 'border', 'rounded-lg', 'mt-2');

          // Create delete button
          const deleteButton = document.createElement('button');
          deleteButton.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center');
          deleteButton.innerHTML = '&times;';

          // Delete functionality (remove image preview)
          deleteButton.onclick = function() {
            imageContainer.remove();
          };

          // Append the image and delete button to the container
          imageContainer.appendChild(img);
          imageContainer.appendChild(deleteButton);

          // Add the container to the preview container
          previewContainer.appendChild(imageContainer);
        };
        reader.readAsDataURL(file);
      });
    }
  }
</script>