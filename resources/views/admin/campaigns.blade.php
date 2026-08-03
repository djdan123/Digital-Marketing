@extends('layouts.admin')

@section('title', 'Gestion des campagnes')
@section('page_title', 'Campagnes')

@section('header_actions')
    <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Ajouter une campagne</a>
@endsection

@section('content')
<div class="grid gap-4 md:grid-cols-3 mb-6">
    <div>
        <label for="status-filter" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
        <form method="GET" class="space-y-2">
            <select name="status" id="status-filter" class="block w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Tous</option>
                <option value="draft"{{ request('status') == 'draft' ? ' selected' : '' }}>Brouillon</option>
                <option value="pending"{{ request('status') == 'pending' ? ' selected' : '' }}>En attente</option>
                <option value="approved"{{ request('status') == 'approved' ? ' selected' : '' }}>Approuvée</option>
                <option value="active"{{ request('status') == 'active' ? ' selected' : '' }}>Active</option>
                <option value="completed"{{ request('status') == 'completed' ? ' selected' : '' }}>Terminée</option>
                <option value="cancelled"{{ request('status') == 'cancelled' ? ' selected' : '' }}>Annulée</option>
            </select>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Filtrer</button>
        </form>
    </div>
</div>
<div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <table class="min-w-full text-left text-gray-600 dark:text-gray-300">
        <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Annonceur</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Budget</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($campaigns as $campaign)
                <tr>
                    <td class="px-4 py-3">{{ $campaign->name }}</td>
                    <td class="px-4 py-3">{{ $campaign->advertiser?->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ ucfirst($campaign->status) }}</td>
                    <td class="px-4 py-3">{{ number_format($campaign->budget, 2) }} €</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Éditer</a>
                            <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">Supprimer</button>
                            </form>
                            <form action="{{ route('admin.campaigns.approve', $campaign) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">Approuver</button>
                            </form>
                            <form action="{{ route('admin.campaigns.reject', $campaign) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">Rejeter</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $campaigns->withQueryString()->links() }}</div>
@endsection
