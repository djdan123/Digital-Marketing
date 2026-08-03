@extends('layouts.admin')

@section('title', 'Modifier un média')
@section('page_title', 'Modifier un média')

@section('content')
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 p-6">
    <form action="{{ route('admin.media.update', $media) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
            <input type="text" name="name" value="{{ old('name', $media->name) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie</label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"{{ old('category_id', $media->category_id) == $category->id ? ' selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Société</label>
                <select name="company_id" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">Sélectionnez une société</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"{{ old('company_id', $media->company_id) == $company->id ? ' selected' : '' }}>{{ $company->display_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select name="type" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">Sélectionnez un type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->value }}"{{ old('type', $media->type) === $type->value ? ' selected' : '' }}>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Modalité de tarification</label>
                <select name="pricing_type" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">Sélectionnez une modalité</option>
                    @foreach($pricingTypes as $pricingType)
                        <option value="{{ $pricingType->value }}"{{ old('pricing_type', $media->pricing_type) === $pricingType->value ? ' selected' : '' }}>{{ $pricingType->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $media->description) }}</textarea>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Prix</label>
                <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $media->base_price) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg bg-gray-50 border-gray-300 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="active"{{ old('status', $media->status) === 'active' ? ' selected' : '' }}>Actif</option>
                    <option value="inactive"{{ old('status', $media->status) === 'inactive' ? ' selected' : '' }}>Inactif</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.media.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">Annuler</a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
