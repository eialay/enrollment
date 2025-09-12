<x-sidebar>
    <x-slot:title>
        Edit User
    </x-slot>
    <div class="max-w-lg mx-auto">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role_id" name="role_id" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password <span class="text-xs text-gray-400">(leave blank to keep current)</span></label>
                <input id="password" name="password" type="password" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            </div>
            <div class="flex justify-end">
                <a href="{{ route('users.index') }}" class="mr-4 text-gray-600 hover:underline">Cancel</a>
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition">Update User</button>
            </div>
        </form>
    </div>
</x-sidebar>
