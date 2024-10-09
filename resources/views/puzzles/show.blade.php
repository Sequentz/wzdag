<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('puzzles.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View Puzzles</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Puzzle Details') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Puzzle Name & Category -->
      <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Name: {{ $puzzle->name }}</h2>

        <!-- Category (Theme) -->
        <p class="text-xl text-gray-700 mb-4">
          <strong>Theme:</strong> {{ $theme->name }}
        </p>
      </div>

      <!-- Puzzle Image -->
      <div class="border border-gray-200 p-2 rounded-lg shadow-md">
        @if($puzzle->image)
        <img src="{{ asset('storage/' . $puzzle->image->file_path) }}" alt="Puzzle Image" class="w-48 h-48 object-cover rounded-lg">
        @else
        <p>No image selected for this puzzle.</p>
        @endif
      </div>

    </div>

    <!-- Words of the Puzzle -->
    <div class="mt-6">
      <h3 class="text-2xl font-bold mb-2">Words in this Puzzle:</h3>
      @if($words->count() > 0)
      <ul class=" pl-5 list-none">
        @foreach($words as $word)
        <li class="text-lg text-gray-500">{{ $word->word }}</li>
        @endforeach
      </ul>
      @else
      <p class="text-gray-500">No words added for this puzzle.</p>
      @endif
    </div>

    <!-- Back Button -->
    <div class="mt-6">
      <a href="{{ route('puzzles.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Back to Puzzles
      </a>
    </div>
  </div>
</x-app-layout>