<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Manage Student</h2></x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('lecturer.students.update', $student) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password (optional)</label>
                        <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep existing password.</p>
                        @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Assign to Your Classes</label>
                    @if($classes->isNotEmpty())
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($classes as $class)
                                @php
                                    $checked = collect(old('class_room_ids', $assignedClassIds))->contains($class->id);
                                @endphp
                                <label class="flex items-center gap-2 border rounded px-3 py-2 hover:bg-gray-50">
                                    <input type="checkbox" name="class_room_ids[]" value="{{ $class->id }}" {{ $checked ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $class->name }} ({{ $class->code }})</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-2">You have no classes yet. Create classes first to enroll students.</p>
                    @endif
                    @error('class_room_ids.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('lecturer.students.index') }}" class="px-4 py-2 border rounded text-gray-600">Back</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
