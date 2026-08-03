@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('page_title', 'Tableau de bord')

@section('content')
<div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Utilisateurs</div>
        <div class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ $usersCount }}</div>
    </div>
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Campagnes</div>
        <div class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ $campaignsCount }}</div>
    </div>
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Médias</div>
        <div class="mt-3 text-3xl font-semibold text-gray-900 dark:text-white">{{ $mediaCount }}</div>
    </div>
</div>

<div class="mt-6 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dernières campagnes</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Les 5 campagnes récemment créées.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-gray-600 dark:text-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Annonceur</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Budget</th>
                    <th class="px-4 py-3">Dates</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($latestCampaigns as $campaign)
                    <tr>
                        <td class="px-4 py-3">{{ $campaign->name }}</td>
                        <td class="px-4 py-3">{{ $campaign->advertiser?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $campaign->status == 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : ($campaign->status == 'draft' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">{{ ucfirst($campaign->status) }}</span>
                        </td>
                        <td class="px-4 py-3">{{ number_format($campaign->budget, 2) }} €</td>
                        <td class="px-4 py-3">{{ $campaign->starts_at?->format('d/m/Y') }} - {{ $campaign->ends_at?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
