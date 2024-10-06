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
            <tbody class="divide-y divide-gray-200" id="imageTableBody">
                @if($images->count() > 0)
                @foreach($images as $image)
                <tr id="imageRow-{{ $image->id }}" class="hover:bg-gray-100">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $image->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $image->file_name }}</td>

                    <!-- Image Preview -->
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="#imageModal-{{ $image->id }}" class="open-modal">
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
                <!-- Display the message when no images are found -->
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No images found.</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Delete Confirmation Modal -->
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
    </div>

</x-app-layout>

<!-- OPEN MODAL AND DELETE MODAL LOGIC -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all open-modal links
        const openModalLinks = document.querySelectorAll('.open-modal');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        const openDeleteModalButtons = document.querySelectorAll('.open-delete-modal');
        const closeDeleteModalButtons = document.querySelectorAll('.close-delete-modal');

        const deleteForm = document.getElementById('deleteForm');
        const deleteModal = document.getElementById('deleteModal');

        // Open modal
        openModalLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const modalId = this.getAttribute('href').replace('#', '');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                }
            });
        });

        // Close modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                modal.classList.add('hidden');
            });
        });

        // Open delete confirmation modal
        openDeleteModalButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const fileName = this.getAttribute('data-name');
                const fileId = this.getAttribute('data-id');
                const deleteFileName = document.getElementById('deleteFileName');

                deleteFileName.textContent = fileName;
                deleteForm.setAttribute('action', `/images/${fileId}`);
                deleteForm.dataset.id = fileId;
                deleteModal.classList.remove('hidden');
            });
        });

        // Close delete modal
        closeDeleteModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        });

        // Handle form submission via AJAX and close the modal after deletion
        deleteForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting the traditional way
            const fileId = deleteForm.dataset.id; // Get the image ID from the dataset
            const formAction = deleteForm.getAttribute('action');
            const formData = new FormData(deleteForm);

            fetch(formAction, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Remove the row for the deleted image
                        const imageRow = document.getElementById(`imageRow-${fileId}`);
                        imageRow.remove();

                        // Close the modal
                        deleteModal.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error deleting image:', error);
                });
        });

        // Close modal when clicking outside the modal content
        document.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.contains(event.target)) {
                    if (!modal.querySelector('.relative') && !modal.querySelector('.modal-content').contains(event.target)) {
                        modal.classList.add('hidden');
                    }
                }
            });
        });
    });
</script>