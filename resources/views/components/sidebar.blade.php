<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 hidden w-64 pt-16 overflow-y-auto border-r border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 lg:block">
    <div class="px-3 pb-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="TruckAll" class="h-8 mr-3">
            <span class="text-xl font-semibold text-gray-900 dark:text-white">TruckAll</span>
        </a>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.media.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.media.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    <span>Médias</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.campaigns.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.campaigns.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    <span>Campagnes</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 rounded-lg text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    <span>Utilisateurs</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<div id="sidebarBackdrop" class="fixed inset-0 z-20 hidden bg-black/50 lg:hidden"></div>
