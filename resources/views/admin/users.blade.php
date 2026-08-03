@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')
@section('page_title', 'Utilisateurs')

@section('content')
<div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <table class="min-w-full text-left text-gray-600 dark:text-gray-300">
        <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Rôle</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($users as $user)
                <tr>
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Modifier</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
