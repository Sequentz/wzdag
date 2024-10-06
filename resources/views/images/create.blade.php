<x-app-layout>
  <x-slot name="header">
    <a href="{{ route('images.index') }}" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">View images</a>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Add image') }}
    </h2>
  </x-slot>

  <div class="container mx-auto mt-8 bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div>
        <label for="image" class="block mb-2 text-3xl font-medium text-gray-900">Select Image:</label>
        <input type="file" name="image" id="image">
      </div>
      <button type="submit" class="align-middle bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">Upload</button>
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