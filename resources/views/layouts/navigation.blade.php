<!-- Main Navigation -->
<nav x-data="{ open: false }" class="bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-dark-blue)] border-b border-[var(--color-primary)] fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <span class="text-white font-bold text-2xl tracking-wider">WoW</span>
                        <!-- <span class="text-gray-300 text-sm hidden md:inline-block">Worth of Worship Ministry International</span> -->
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex lg:items-center lg:ml-6 lg:space-x-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
                        <i class="fas fa-users mr-2"></i> Members
                    </x-nav-link>

                    <x-nav-link :href="route('families.index')" :active="request()->routeIs('families.*')">
                        <i class="fas fa-users-family mr-2"></i> Families
                    </x-nav-link>

                    <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
                        <i class="fas fa-clipboard-check mr-2"></i> Attendance
                    </x-nav-link>

                    <!-- Finance Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white focus:outline-none">
                            <i class="fas fa-money-bill mr-2"></i> Finance
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <x-dropdown-link :href="route('finance.donations.index')">
                                    <i class="fas fa-gift mr-2"></i> Donations
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('finance.pledges.index')">
                                    <i class="fas fa-handshake mr-2"></i> Pledges
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('finance.expenses.index')">
                                    <i class="fas fa-receipt mr-2"></i> Expenses
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('finance.campaigns.index')">
                                    <i class="fas fa-bullhorn mr-2"></i> Campaigns
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('finance.transactions.index')">
                                    <i class="fas fa-exchange-alt mr-2"></i> Transactions
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <!-- Communication Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white focus:outline-none">
                            <i class="fas fa-envelope mr-2"></i> Communication
                            <i class="fas fa-chevron-down ml-2 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <x-dropdown-link :href="route('messages.index')">
                                    <i class="fas fa-comments mr-2"></i> Messages
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('sms.index')">
                                    <i class="fas fa-sms mr-2"></i> SMS
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('prayer-requests.index')">
                                    <i class="fas fa-pray mr-2"></i> Prayer Requests
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('notifications.index')">
                                    <i class="fas fa-bell mr-2"></i> Notifications
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <!-- Events & Services -->
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                        <i class="fas fa-calendar-alt mr-2"></i> Services
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ml-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white focus:outline-none">
                        <img class="h-8 w-8 rounded-full object-cover mr-2" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}">
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down ml-2 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                         style="display: none;">
                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="fas fa-user-circle mr-2"></i> {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('settings.index')">
                                <i class="fas fa-cog mr-2"></i> {{ __('Settings') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white focus:outline-none">
                    <i class="fas" :class="{'fa-times': open, 'fa-bars': !open}"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="lg:hidden" style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-home mr-2"></i> Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
                <i class="fas fa-users mr-2"></i> Members
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('families.index')" :active="request()->routeIs('families.*')">
                <i class="fas fa-users-family mr-2"></i> Families
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
                <i class="fas fa-clipboard-check mr-2"></i> Attendance
            </x-responsive-nav-link>

            <!-- Mobile Finance Section -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center px-3 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-opacity-75 focus:outline-none">
                    <i class="fas fa-money-bill mr-2"></i>
                    <span>Finance</span>
                    <i class="fas fa-chevron-down ml-auto transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" class="pl-4 space-y-1">
                    <x-responsive-nav-link :href="route('finance.donations.index')" :active="request()->routeIs('finance.donations.*')">
                        <i class="fas fa-gift mr-2"></i> Donations
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('finance.pledges.index')" :active="request()->routeIs('finance.pledges.*')">
                        <i class="fas fa-handshake mr-2"></i> Pledges
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('finance.expenses.index')" :active="request()->routeIs('finance.expenses.*')">
                        <i class="fas fa-receipt mr-2"></i> Expenses
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('finance.campaigns.index')" :active="request()->routeIs('finance.campaigns.*')">
                        <i class="fas fa-bullhorn mr-2"></i> Campaigns
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('finance.transactions.index')" :active="request()->routeIs('finance.transactions.*')">
                        <i class="fas fa-exchange-alt mr-2"></i> Transactions
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Mobile Communication Section -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center px-3 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-opacity-75 focus:outline-none">
                    <i class="fas fa-envelope mr-2"></i>
                    <span>Communication</span>
                    <i class="fas fa-chevron-down ml-auto transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open" class="pl-4 space-y-1">
                    <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        <i class="fas fa-comments mr-2"></i> Messages
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('sms.index')" :active="request()->routeIs('sms.*')">
                        <i class="fas fa-sms mr-2"></i> SMS
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('prayer-requests.index')" :active="request()->routeIs('prayer-requests.*')">
                        <i class="fas fa-pray mr-2"></i> Prayer Requests
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                        <i class="fas fa-bell mr-2"></i> Notifications
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Mobile Services Link -->
            <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                <i class="fas fa-calendar-alt mr-2"></i> Services
            </x-responsive-nav-link>
        </div>

        <!-- Mobile profile section -->
        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-300">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user-circle mr-2"></i> {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('settings.index')">
                    <i class="fas fa-cog mr-2"></i> {{ __('Settings') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                         onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer to prevent content from being hidden under fixed navbar -->
<div class="h-16"></div> 