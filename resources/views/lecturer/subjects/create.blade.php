<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Create Subject</h2></x-slot>
    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('lecturer.subjects.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_room_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select a class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_room_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }} ({{ $class->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('class_room_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('lecturer.subjects.index') }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
