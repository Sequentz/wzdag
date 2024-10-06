<x-app-layout>
    <div class="container mx-auto mt-8 flex justify-center">
        <button class="hover:bg-red-700 bg-red-500 text-white font-bold py-2 px-4 rounded">
            <a href="{{ route('images.create') }}">Add image</a>
        </button>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden overflow-x-auto">

            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">@sortablelink('id', 'ID') <span class="inline-block ml-1">⬍</span></th>
                    <th class="px-6 py-3 text-left text-sm font-medium">@sortablelink('image', 'Name') <span class="inline-block ml-1">⬍</span></th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Edit</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Delete</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($images->count())
                @foreach($images as $image)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $image->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $image->file_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <a href="{{ route('images.edit', $image->id) }}" class="text-blue-500 hover:text-blue-700">✏️</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <form action="{{ route('images.destroy', $image->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this image?')" class="hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                🗑️
                            </button>

                        </form>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" colspan="5">No images found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center">
        {!! $images->appends(request()->except('page'))->links() !!}
    </div>
</x-app-layout>