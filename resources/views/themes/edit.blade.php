<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('themes.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View themes</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit theme') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <!-- Edit form for updating the theme -->
    <form action="{{ route('themes.update', $theme->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div>
        <label for="name" class="block mb-2 text-3xl font-medium text-gray-900">Edit Theme Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $theme->name) }}" required class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

      <!-- Submit Button -->
      <button type="submit" class="align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
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