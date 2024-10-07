<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('themes.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View Themes</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add Theme') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('themes.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div>
        <label for="name" class="block mb-2 text-3xl font-medium text-gray-900">Theme Name:</label>
        <input type="text" name="name" id="name" required class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

      <!-- Multiple Image Upload Section -->
      <div class="mt-4">
        <label for="images" class="block mb-2 text-3xl font-medium text-gray-900">Upload Images:</label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple onchange="previewImages(event)" class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

      <!-- Image Previews in a Row -->
      <div class="mt-4 flex space-x-4" id="imagePreviewContainer"></div>

      <!-- Submit Button -->
      <button type="submit" class="align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
        Add Theme
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

<!-- JavaScript to handle multiple image previews and removal -->
<script>
  const allFiles = new DataTransfer();

  function previewImages(event) {
    const input = event.target;
    const previewContainer = document.getElementById('imagePreviewContainer');

    // Add new files to allFiles and create previews
    Array.from(input.files).forEach((file, index) => {
      allFiles.items.add(file); // Add new file to DataTransfer object

      const reader = new FileReader();

      // Create a wrapper for the image and delete button
      const previewWrapper = document.createElement('div');
      previewWrapper.classList.add('relative', 'w-32', 'h-32', 'overflow-hidden', 'border', 'rounded-lg');

      // Create the image element
      const imgElement = document.createElement('img');
      imgElement.classList.add('w-full', 'h-full', 'object-cover');

      // Create the delete button (cross in the top-right corner)
      const deleteButton = document.createElement('button');
      deleteButton.innerHTML = '&times;';
      deleteButton.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center');

      deleteButton.addEventListener('click', function(event) {
        event.preventDefault();
        previewWrapper.remove(); // Remove the preview
        removeFileFromSelection(index); // Remove the file from selection
      });

      // Append elements to the preview container
      previewWrapper.appendChild(imgElement);
      previewWrapper.appendChild(deleteButton);
      previewContainer.appendChild(previewWrapper);

      // Load the image preview
      reader.onload = function(e) {
        imgElement.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });

    // Update input with new file list
    input.files = allFiles.files;
  }

  // Remove file from the input's file list
  function removeFileFromSelection(indexToRemove) {
    const newFiles = new DataTransfer();

    // Add all files except the one that should be removed
    Array.from(allFiles.files)
      .filter((file, index) => index !== indexToRemove)
      .forEach(file => newFiles.items.add(file));

    // Replace the original allFiles with the updated file list
    allFiles.items.clear();
    Array.from(newFiles.files).forEach(file => allFiles.items.add(file));

    // Update the input with the new file list
    document.getElementById('images').files = allFiles.files;
  }
</script>