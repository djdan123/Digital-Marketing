@extends('layouts.admin')

@section('title', 'Connexion')
@section('page_title', 'Connexion')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 rounded-lg shadow p-8">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Se connecter</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input id="email" name="email" type="email" required autofocus
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mot  passe</label>
            <input id="password" name="password" type="password" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <button type="submit"
            class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
            Se connecter
        </button>
    </form>
</div>
@endsection
