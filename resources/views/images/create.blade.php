<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('images.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View images</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add images') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div>
        <label for="images" class="block mb-2 text-3xl font-medium text-gray-900">Upload Images:</label>

        <!-- Custom Styled File Input for Multiple Images -->
        <label for="images" class="cursor-pointer inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Choose Files
        </label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple onchange="previewImages(event)" class="hidden">
        <span id="fileNames" class="ml-4 text-gray-700"></span>
      </div>

      <!-- Image Preview Section in a Row with Remove Button -->
      <div id="imagePreviewContainer" class="mt-4 flex space-x-4"></div>

      <!-- Upload Button -->
      <button id="uploadButton" type="submit" class="hidden align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
        Upload Images
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

<!-- JavaScript for Multiple Image Preview with Remove Option -->
<script>
  function previewImages(event) {
    const input = event.target;
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const fileNames = document.getElementById('fileNames');
    const uploadButton = document.getElementById('uploadButton');
    imagePreviewContainer.innerHTML = ''; // Clear previous previews
    fileNames.textContent = ''; // Clear previous file names

    const files = Array.from(input.files);
    files.forEach((file, index) => {
      const reader = new FileReader();

      // Create image preview wrapper
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
        removeFileFromSelection(input, index); // Remove file from selection
      });

      // Append elements to the preview container
      previewWrapper.appendChild(imgElement);
      previewWrapper.appendChild(deleteButton);
      imagePreviewContainer.appendChild(previewWrapper);

      // Load the image preview
      reader.onload = function(e) {
        imgElement.src = e.target.result;
      };
      reader.readAsDataURL(file);

      // Append file names
      fileNames.textContent += `${file.name}, `;
    });

    // Show the Upload button if there are files
    if (files.length > 0) {
      uploadButton.classList.remove('hidden');
    } else {
      uploadButton.classList.add('hidden');
      fileNames.textContent = '';
    }
  }

  // Remove file from the input's file list
  function removeFileFromSelection(input, indexToRemove) {
    const dt = new DataTransfer();

    // Add all files except the one that should be removed
    Array.from(input.files)
      .filter((file, index) => index !== indexToRemove)
      .forEach(file => dt.items.add(file));

    input.files = dt.files; // Update the input's files property
  }
</script>