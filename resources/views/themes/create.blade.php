<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('themes.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View Themes</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add Theme') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('themes.store') }}" method="POST">
      @csrf
      <div>
        <label for="name" class="block mb-2 text-3xl font-medium text-gray-900">Theme Name:</label>
        <input type="text" name="name" id="name" required class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

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