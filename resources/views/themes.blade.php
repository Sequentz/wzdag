<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('themes.create') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">Add Theme</a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Themes') }}
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
                @if($themes->count() > 0)
                @foreach($themes as $theme)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $theme->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $theme->name }}</td>

                    <!-- Theme Preview: Show the first image of the theme -->
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($theme->images->count() > 0)
                        <a href="javascript:void(0);" class="open-preview-modal" data-theme-id="{{ $theme->id }}">
                            <img src="{{ asset('storage/' . $theme->images->first()->file_path) }}" alt="Preview" class="w-16 h-16 object-cover rounded-lg shadow-md">
                        </a>
                        @else
                        <span>No Images</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="{{ route('themes.edit', $theme->id) }}" class="text-blue-500 hover:text-blue-700">✏️</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <button type="button" class="open-delete-modal hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-id="{{ $theme->id }}" data-name="{{ $theme->name }}">
                            🗑️
                        </button>
                    </td>
                </tr>
                @endforeach
                @else
                <!-- If no themes are found, display this message -->
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No themes available</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Image Preview Modal (Smaller and Grid Layout) -->
    <div id="imagePreviewModal" class="modal hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg max-w-xl w-full relative">
            <span class="close-preview-modal absolute top-2 right-4 cursor-pointer bg-red-500 w-8 h-8 text-white rounded-full flex items-center justify-center text-lg">&times;</span>
            <h2 class="text-lg font-bold mb-4 text-center">Theme Images</h2>
            <div id="imagePreviewGrid" class="grid grid-cols-2 gap-4"></div> <!-- Grid for images -->
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal hidden fixed z-50 left-0 top-0 w-full h-full overflow-auto bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg max-w-lg w-full">

            <h2 class="text-lg font-bold mb-4">Are you sure you want to delete <span id="deleteFileName"></span>?</h2>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded">Yes, Delete</button>
                <button type="button" class="close-delete-modal bg-gray-500 text-white font-bold py-2 px-4 rounded ml-4">Cancel</button>
            </form>
        </div>
    </div>

</x-app-layout>

<!-- JavaScript to Handle Image Preview and Delete Modals -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview modal
        const previewModal = document.getElementById('imagePreviewModal');
        const previewGrid = document.getElementById('imagePreviewGrid');
        const closePreviewModalButton = document.querySelector('.close-preview-modal');
        const openPreviewModalLinks = document.querySelectorAll('.open-preview-modal');

        openPreviewModalLinks.forEach(link => {
            link.addEventListener('click', function() {
                const themeId = this.getAttribute('data-theme-id');

                // Fetch the images for the theme via AJAX
                fetch(`/themes/${themeId}/images`)
                    .then(response => response.json())
                    .then(data => {
                        previewGrid.innerHTML = ''; // Clear the previous images
                        data.images.forEach(image => {
                            const img = document.createElement('img');
                            img.src = `/storage/${image.file_path}`;
                            img.classList.add('w-full', 'h-32', 'object-cover', 'rounded-lg', 'shadow-md');
                            previewGrid.appendChild(img);
                        });
                    });

                previewModal.classList.remove('hidden');
            });
        });

        closePreviewModalButton.addEventListener('click', function() {
            previewModal.classList.add('hidden');
        });

        // Delete modal
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
                deleteForm.setAttribute('action', `/themes/${fileId}`);
                deleteModal.classList.remove('hidden');
            });
        });

        closeDeleteModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        });

        // Close modals when clicking outside the content
        document.addEventListener('click', function(event) {
            if (previewModal.contains(event.target) && !previewModal.querySelector('.bg-white').contains(event.target)) {
                previewModal.classList.add('hidden');
            }
            if (deleteModal.contains(event.target) && !deleteModal.querySelector('.bg-white').contains(event.target)) {
                deleteModal.classList.add('hidden');
            }
        });
    });
</script>