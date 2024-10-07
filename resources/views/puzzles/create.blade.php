<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('puzzles.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View Puzzles</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add Puzzle') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('puzzles.store') }}" method="POST">
      @csrf
      <div>
        <label for="name" class="block mb-2 text-3xl font-medium text-gray-900">Puzzle Name:</label>
        <input type="text" name="name" id="name" class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
      </div>

      <!-- Thema Selectie -->
      <div class="mt-4">
        <label for="theme_id" class="block mb-2 text-2xl font-medium text-gray-900">Select Theme:</label>
        <select name="theme_id" id="theme_id" class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
          @foreach($themes as $theme)
          <option value="{{ $theme->id }}">{{ $theme->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Woorden Toevoegen -->
      <div class="mt-4">
        <label for="words" class="block mb-2 text-2xl font-medium text-gray-900">Add Words (one per field):</label>
        <input type="text" name="words[]" placeholder="Woord 1" class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
        <input type="text" name="words[]" placeholder="Woord 2" class="block w-full mt-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
        <input type="text" name="words[]" placeholder="Woord 3" class="block w-full mt-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
        <!-- Voeg meer velden toe indien nodig -->
      </div>

      <!-- Submit Button -->
      <button type="submit" class="align-middle bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mt-4 rounded">
        Add Puzzle
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