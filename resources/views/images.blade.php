<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('images.create') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">Add image</a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add image') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-8 flex justify-center">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Preview</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Edit</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Delete</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($images->count() > 0)
                @foreach($images as $image)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $image->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $image->file_name }}</td>

                    <!-- Image Preview -->
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="javascript:void(0);" class="open-preview-modal" data-id="{{ $image->id }}" data-file="{{ asset('storage/' . $image->file_path) }}">
                            <img src="{{ asset('storage/' . $image->file_path) }}" alt="Preview" class="w-16 h-16 object-cover">
                        </a>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="{{ route('images.edit', $image->id) }}" class="text-blue-500 hover:text-blue-700">✏️</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <button type="button" class="open-delete-modal hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-id="{{ $image->id }}" data-name="{{ $image->file_name }}">
                            🗑️
                        </button>
                    </td>
                </tr>
                @endforeach
                @else
                <!-- If no images are found, display this message -->
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No images available</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Delete Modal (Shared by All Images) -->
    <div id="deleteModal" class="modal hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-4 rounded shadow-lg max-w-lg w-full">
            <span class="close-delete-modal float-right cursor-pointer bg-red-500 w-8 mb-2 h-auto text-white rounded-sm text-center text-lg">&times;</span>
            <h2 class="text-lg mb-4">Are you sure you want to delete <span id="deleteFileName"></span>?</h2>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded">Yes, Delete</button>
                <button type="button" class="close-delete-modal bg-gray-500 text-white font-bold py-2 px-4 rounded ml-4">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Image Preview Modal (Shared by All Images) -->
    <div id="imagePreviewModal" class="modal hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-4 rounded shadow-lg max-w-lg w-full">
            <span class="close-preview-modal float-right cursor-pointer bg-red-500 w-8 mb-2 h-auto text-white rounded-sm text-center text-lg">&times;</span>
            <img id="imagePreview" src="" alt="Image Preview" class="w-full h-auto">
        </div>
    </div>
</x-app-layout>

<!-- JavaScript to Handle Modal Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Image Preview Modal
        const previewModal = document.getElementById('imagePreviewModal');
        const previewImage = document.getElementById('imagePreview');
        const openPreviewModalLinks = document.querySelectorAll('.open-preview-modal');
        const closePreviewModalButton = document.querySelector('.close-preview-modal');

        openPreviewModalLinks.forEach(link => {
            link.addEventListener('click', function() {
                const fileSrc = this.getAttribute('data-file');
                previewImage.setAttribute('src', fileSrc);
                previewModal.classList.remove('hidden');
            });
        });

        closePreviewModalButton.addEventListener('click', function() {
            previewModal.classList.add('hidden');
        });

        // Handle Delete Modal
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteFileName = document.getElementById('deleteFileName');
        const openDeleteModalButtons = document.querySelectorAll('.open-delete-modal');
        const closeDeleteModalButtons = document.querySelectorAll('.close-delete-modal');

        openDeleteModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const fileName = this.getAttribute('data-name');
                const fileId = this.getAttribute('data-id');

                deleteFileName.textContent = fileName;
                deleteForm.setAttribute('action', `/images/${fileId}`);
                deleteModal.classList.remove('hidden');
            });
        });

        closeDeleteModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        });

        // Close modal when clicking outside the modal content
        document.addEventListener('click', function(event) {
            if (deleteModal.contains(event.target)) {
                const modalContent = deleteModal.querySelector('.bg-white');
                if (!modalContent.contains(event.target)) {
                    deleteModal.classList.add('hidden');
                }
            }

            if (previewModal.contains(event.target)) {
                const modalContent = previewModal.querySelector('.bg-white');
                if (!modalContent.contains(event.target)) {
                    previewModal.classList.add('hidden');
                }
            }
        });
    });
</script>