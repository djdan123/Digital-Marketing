@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')
@section('page_title', 'Modifier un utilisateur')

@section('content')
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 p-6">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Rôle</label>
            <input type="text" name="role" value="{{ old('role', $user->role) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">Annuler</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
