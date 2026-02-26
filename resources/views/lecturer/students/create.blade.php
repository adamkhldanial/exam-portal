<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Create Student</h2></x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('lecturer.students.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">Assign to Your Classes</label>
                        @if($classes->isEmpty())
                            <span class="text-xs text-gray-500">Create a class first</span>
                        @endif
                    </div>

                    @if($classes->isNotEmpty())
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($classes as $class)
                                @php
                                    $checked = collect(old('class_room_ids', $preselectedClassId ? [$preselectedClassId] : []))->contains($class->id);
                                @endphp
                                <label class="flex items-center gap-2 border rounded px-3 py-2 hover:bg-gray-50">
                                    <input type="checkbox" name="class_room_ids[]" value="{{ $class->id }}" {{ $checked ? 'checked' : '' }}>
                                    <span class="text-sm">{{ $class->name }} ({{ $class->code }})</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    @error('class_room_ids.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('lecturer.students.index') }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Create Student</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
