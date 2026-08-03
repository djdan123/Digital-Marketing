@extends('layouts.admin')

@section('title', 'Créer une campagne')
@section('page_title', 'Créer une campagne')

@section('content')
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 p-6">
    <form action="{{ route('admin.campaigns.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Annonceur</label>
            <select name="advertiser_id" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                <option value="">Sélectionnez un annonceur</option>
                @foreach($advertisers as $advertiser)
                    <option value="{{ $advertiser->id }}"{{ old('advertiser_id') == $advertiser->id ? ' selected' : '' }}>{{ $advertiser->full_name }} ({{ $advertiser->email }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Objectif</label>
            <textarea name="objective" rows="3" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('objective') }}</textarea>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
            <select name="status" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}"{{ old('status') === $status->value ? ' selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Date de début</label>
                <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                <input type="date" name="ends_at" value="{{ old('ends_at') }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Budget</label>
            <input type="number" step="0.01" name="budget" value="{{ old('budget') }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.campaigns.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">Annuler</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Créer</button>
        </div>
    </form>
</div>
@endsection
