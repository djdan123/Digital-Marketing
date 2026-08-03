<nav class="fixed top-0 left-0 right-0 z-10 flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 lg:pl-72">
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" class="inline-flex items-center justify-center w-10 h-10 text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
            <span class="sr-only">Toggle sidebar</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>
        </button>
        <div class="flex items-center gap-3">
            <div class="relative hidden md:block">
                <input type="search" placeholder="Rechercher..." class="w-full px-4 py-2 text-sm border rounded-lg bg-gray-50 border-gray-200 text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <button id="theme-toggle" type="button" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
            <span class="sr-only">Toggle dark mode</span>
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>
        @auth
            <div class="relative">
                <button type="button" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-900 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600" id="user-menu-button" aria-expanded="false">
                    <span>{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                    <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="Avatar" class="w-9 h-9 rounded-full">
                </button>
                <div class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700" id="user-menu">
                    <div class="px-4 py-3">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Utilisateur' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Déconnexion</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
