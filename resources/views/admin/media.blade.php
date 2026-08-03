@extends('layouts.admin')

@section('title', 'Gestion des médias')
@section('page_title', 'Médias')

@section('header_actions')
    <a href="{{ route('admin.media.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Ajouter un média</a>
@endsection

@section('content')
<div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <table class="min-w-full text-left text-gray-600 dark:text-gray-300">
        <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Prix</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($medias as $media)
                <tr>
                    <td class="px-4 py-3">{{ $media->name }}</td>
                    <td class="px-4 py-3">{{ ucfirst($media->type) }}</td>
                    <td class="px-4 py-3">{{ ucfirst($media->status) }}</td>
                    <td class="px-4 py-3">{{ number_format($media->base_price, 2) }} €</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.media.edit', $media) }}" class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">Éditer</a>
                            <form action="{{ route('admin.media.destroy', $media) }}" method="POST" class="inline-block">
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
<div class="mt-4">{{ $medias->links() }}</div>
@endsection
