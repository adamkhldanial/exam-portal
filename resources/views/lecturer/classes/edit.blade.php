<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Class</h2></x-slot>
    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('lecturer.classes.update', $classRoom) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class Name</label>
                    <input type="text" name="name" value="{{ old('name', $classRoom->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class Code</label>
                    <input type="text" name="code" value="{{ old('code', $classRoom->code) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $classRoom->description) }}</textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('lecturer.classes.index') }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
