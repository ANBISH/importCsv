@extends('app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold mb-4">Імпорт та експорт CSV</h2>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('csv.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="csv_file" class="block text-sm font-medium text-gray-700">Оберіть CSV файл</label>
                <input type="file" name="csv_file"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Імпортувати</button>
        </form>

        <hr class="my-6">

        <a href="{{ route('csv.export') }}"
            class="block text-center w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">Експортувати
            CSV</a>
    </div>
@endsection
