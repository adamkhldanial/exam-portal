<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Classes</h2>
            <a href="{{ route('lecturer.classes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ New Class</a>
        </div>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>@endif
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Students</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subjects</th>
                    <th class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($classes as $class)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $class->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $class->code }}</td>
                        <td class="px-6 py-4">{{ $class->students_count }}</td>
                        <td class="px-6 py-4">{{ $class->subjects_count }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('lecturer.classes.show', $class) }}" class="text-indigo-600 hover:underline">Manage</a>
                            <a href="{{ route('lecturer.classes.edit', $class) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('lecturer.classes.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Delete this class?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No classes yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
