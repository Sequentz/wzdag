<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('puzzles.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View puzzles</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit puzzle') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <!-- Edit form for updating the puzzle -->
    <form action="{{ route('puzzles.update', $puzzle->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div>
        <label for="name" class="block mb-2 text-3xl font-medium text-gray-900">Edit Puzzle Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $puzzle->name) }}" required class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

      <!-- Thema Selectie -->
      <div class="mt-4">
        <label for="theme_id" class="block mb-2 text-2xl font-medium text-gray-900">Select Theme:</label>
        <select name="theme_id" id="theme_id" required class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
          @foreach($themes as $theme)
          <option value="{{ $theme->id }}" {{ $theme->id == $puzzle->theme_id ? 'selected' : '' }}>
            {{ $theme->name }}
          </option>
          @endforeach
        </select>
      </div>

      <!-- Woorden Toevoegen -->
      <div class="mt-4">
        <label for="words" class="block mb-2 text-2xl font-medium text-gray-900">Edit Words:</label>
        @foreach($puzzle->woorden as $index => $word)
        <input type="text" name="words[]" value="{{ old('words.' . $index, $word->word) }}" class="block w-full mt-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
        @endforeach
        <!-- Voeg nieuwe lege invoervelden toe voor extra woorden indien nodig -->
        <input type="text" name="words[]" placeholder="Add new word" class="block w-full mt-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
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