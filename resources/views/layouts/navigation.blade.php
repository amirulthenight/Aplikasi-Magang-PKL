<header x-data="{ mobileMenuOpen: false, sidebarOpen: false }" class="relative flex-shrink-0 flex h-16 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm border-b border-gray-200 dark:border-gray-700/50 z-30">
    <div class="flex-1 px-4 sm:px-6 lg:px-8 flex justify-end">
        <div class="flex items-center">
            <div class="relative">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER MOBILE --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="mobileMenuOpen = ! mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': ! mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{'block': mobileMenuOpen, 'hidden': ! mobileMenuOpen}" class="hidden sm:hidden border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- KHUSUS ADMIN --}}
            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('barang.index')" :active="request()->routeIs('barang.*')">
                {{ __('Data Barang') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.*')">
                {{ __('Data Karyawan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.*')">
                {{ __('Data Peminjaman') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peminjaman.indexPengembalianCepat')" :active="request()->routeIs('peminjaman.indexPengembalianCepat')">
                {{ __('Pengembalian Cepat') }}
            </x-responsive-nav-link>
            @endif

          {{-- KHUSUS ADMIN: MANAGEMEN PENGGUNA --}}
            @if(Auth::user()->role === 'admin')
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                {{ __('Data Pengguna') }}
            </x-responsive-nav-link>
            @endif


            {{-- LAPORAN (ADMIN & KEPALA) --}}
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'kepala')
            <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            @endif
        </div>
    </div>
</header>