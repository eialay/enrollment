<x-sidebar>
    <x-slot:title>
        User Management
    </x-slot>

    <div class="max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">User Management</h2>
            <a href="{{ route('users.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Add User</a>
        </div>
        <form method="GET" action="" class="mb-6 flex items-center gap-4">
            <label for="role" class="text-sm font-medium text-gray-700">Filter by Role:</label>
            <select name="role" id="role" class="border-gray-300 rounded px-3 py-2 pr-8 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
                <option value="all" {{ empty($roleFilter) || $roleFilter === 'all' ? 'selected' : '' }}>All</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ (isset($roleFilter) && $roleFilter == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </form>
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($users->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-lg">No users found.</td>
                        </tr>
                    @else
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->role->name ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('users.edit', $user->id) }}" class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition mr-2">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-sidebar>
