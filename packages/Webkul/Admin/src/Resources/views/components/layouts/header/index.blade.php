<header class="sticky top-0 z-[10001] flex items-center justify-between gap-1 border-b border-gray-200 bg-white px-4 py-2.5 transition-all dark:border-gray-800 dark:bg-gray-900">  
    <!-- logo -->
    <div class="flex items-center gap-1.5">
        <!-- Sidebar Menu -->
        <x-admin::layouts.sidebar.mobile />
        
        <a href="{{ route('admin.dashboard.index') }}">
            @if ($logo = core()->getConfigData('general.general.admin_logo.logo_image'))
                <img
                    class="logo-image h-8 max-h-8 w-auto sm:h-9 sm:max-h-9"
                    src="{{ Storage::url($logo) }}"
                    alt="{{ config('app.name') }}"
                />
            @else
                <img
                    class="logo-image desktop-logo h-8 max-h-8 w-auto max-sm:hidden sm:h-9 sm:max-h-9"
                    src="{{ request()->cookie('dark_mode') ? vite()->asset('images/dark-logo.svg') : vite()->asset('images/logo.svg') }}"
                    alt="{{ config('app.name') }}"
                />

                <img
                    class="logo-image mobile-logo h-8 max-h-8 w-auto sm:hidden"
                    src="{{ request()->cookie('dark_mode') ? vite()->asset('images/mobile-dark-logo.svg') : vite()->asset('images/mobile-light-logo.svg') }}"
                    alt="{{ config('app.name') }}"
                />
            @endif
        </a>
    </div>

    <div class="flex items-center gap-1.5 max-md:hidden">
        <!-- Mega Search Bar -->
        @include('admin::components.layouts.header.desktop.mega-search')

        <!-- Quick Creation Bar -->
        @include('admin::components.layouts.header.quick-creation')
    </div>

    <div class="flex items-center gap-2.5">
        <div class="md:hidden">
            <!-- Mega Search Bar -->
            @include('admin::components.layouts.header.mobile.mega-search')
        </div>
        
        <!-- Dark mode -->
        <v-dark>
            <div class="flex">
                <span
                    class="{{ request()->cookie('dark_mode') ? 'icon-light' : 'icon-dark' }} p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                ></span>
            </div>
        </v-dark>

        <!-- In-App Notifications -->
        <v-notifications></v-notifications>

        <!-- Language Switcher -->
        <x-admin::dropdown position="bottom-{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'left' : 'right' }}">
            <x-slot:toggle>
                <button class="flex h-9 w-9 items-center justify-center rounded-md transition-all hover:bg-gray-100 dark:hover:bg-gray-950" aria-label="Switch Language">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-800 dark:text-gray-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" />
                    </svg>
                </button>
            </x-slot>

            <x-slot:content class="mt-2 border-t-0 !p-0 min-w-[150px]">
                <div class="grid gap-1 py-1.5 text-left">
                    @foreach (config('app.available_locales') as $key => $language)
                        <a
                            class="flex items-center gap-2 cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950 {{ app()->getLocale() === $key ? 'bg-gray-100 font-semibold dark:bg-gray-950' : '' }}"
                            href="{{ route('admin.switch_locale', $key) }}"
                        >
                            {{ $language }}
                        </a>
                    @endforeach
                </div>
            </x-slot>
        </x-admin::dropdown>

        <div class="md:hidden">
            <!-- Quick Creation Bar -->
            @include('admin::components.layouts.header.quick-creation')
        </div>
        
        <!-- Admin profile -->
        <x-admin::dropdown position="bottom-{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'left' : 'right' }}">
            <x-slot:toggle>
                @php($user = auth()->guard('user')->user())

                @if ($user->image)
                    <button class="flex h-9 w-9 cursor-pointer overflow-hidden rounded-full hover:opacity-80 focus:opacity-80">
                        <img
                            src="{{ $user->image_url }}"
                            class="h-full w-full object-cover"
                        />
                    </button>
                @else
                    <button class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-pink-400 font-semibold leading-6 text-white">
                        {{ substr($user->name, 0, 1) }}
                    </button>
                @endif
            </x-slot>

            <!-- Admin Dropdown -->
            <x-slot:content class="mt-2 border-t-0 !p-0">
                <div class="flex items-center gap-1.5 border border-x-0 border-b-gray-300 px-5 py-2.5 dark:border-gray-800">
                    <img
                        src="{{ url('cache/logo.png') }}"
                        width="24"
                        height="24"
                    />

                    <!-- Version -->
                    <p class="text-gray-400">
                        @lang('admin::app.layouts.app-version', ['version' => core()->version()])
                    </p>
                </div>

                <div class="grid gap-1 pb-2.5">
                    <a
                        class="cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                        href="{{ route('admin.user.account.edit') }}"
                    >
                        @lang('admin::app.layouts.my-account')
                    </a>

                    <!--Admin logout-->
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        @lang('admin::app.layouts.sign-out')
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </div>
</header>

@push('scripts')
    <script
        type="text/x-template"
        id="v-dark-template"
    >
        <div class="flex">
            <span
                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                :class="[isDarkMode ? 'icon-light' : 'icon-dark']"
                @click="toggle"
            ></span>
        </div>
    </script>

    <!-- In-App Notifications Template -->
    <script
        type="text/x-template"
        id="v-notifications-template"
    >
        <v-dropdown position="bottom-right" :close-on-click="false" class="relative">
            <template v-slot:toggle>
                <button class="relative flex h-9 w-9 items-center justify-center rounded-md transition-all hover:bg-gray-100 dark:hover:bg-gray-950" aria-label="Notifications">
                    <span class="icon-notification text-2xl text-gray-800 dark:text-gray-200"></span>
                    <span v-if="count > 0" class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">
                        @{{ count }}
                    </span>
                </button>
            </template>

            <template #content="{ isActive, positionStyles }">
                <div
                    class="absolute z-[10002] w-80 sm:w-96 rounded bg-white py-5 shadow-[0px_10px_20px_0px_#0000001F] dark:bg-gray-900 border border-gray-300 dark:border-gray-800 text-left"
                    :style="positionStyles"
                    v-show="isActive"
                >
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 pb-3 dark:border-gray-800">
                        <span class="text-base font-bold text-gray-800 dark:text-white">Notifikasi</span>
                        <button v-if="count > 0" @click="markAllAsRead" class="text-xs font-semibold text-brandColor hover:underline">
                            Tandai semua dibaca
                        </button>
                    </div>

                    <div class="max-h-80 overflow-y-auto journal-scroll mt-2">
                        <div v-if="notifications.length === 0" class="flex flex-col items-center justify-center px-4 py-8 text-center">
                            <span class="icon-notification text-4xl text-gray-300 dark:text-gray-700 mb-2"></span>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi baru</p>
                        </div>
                        
                        <div v-else>
                            <a v-for="notification in notifications" :key="notification.id" :href="notification.action_url" class="block border-b border-gray-100 px-4 py-3 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-950 transition-colors">
                                <div class="flex gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full" :class="getTypeClass(notification.type)">
                                        <span :class="getTypeIcon(notification.type)" class="text-lg"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">@{{ notification.title }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 line-clamp-2">@{{ notification.message }}</p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">@{{ notification.created_at }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </v-dropdown>
    </script>

    <script type="module">
        app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: {{ request()->cookie('dark_mode') ?? 0 }},

                    logo: "{{ vite()->asset('images/logo.svg') }}",

                    dark_logo: "{{ vite()->asset('images/dark-logo.svg') }}",

                    mobile_logo: "{{ vite()->asset('images/mobile-light-logo.svg') }}",

                    mobile_dark_logo: "{{ vite()->asset('images/mobile-dark-logo.svg') }}",
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);

                    this.$emitter.emit('change-theme', this.isDarkMode ? 'dark' : 'light');

                    document.querySelectorAll('.logo-image').forEach(el => {
                        if (el.classList.contains('mobile-logo')) {
                            el.src = this.isDarkMode ? this.mobile_dark_logo : this.mobile_logo;
                        } else {
                            el.src = this.isDarkMode ? this.dark_logo : this.logo;
                        }
                    });
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });

        app.component('v-notifications', {
            template: '#v-notifications-template',

            data() {
                return {
                    count: 0,
                    notifications: [],
                    urlIndex: '{{ route('admin.notifications.index') }}',
                    urlReadAll: '{{ route('admin.notifications.read_all') }}',
                };
            },

            mounted() {
                this.fetchNotifications();
                
                // Fetch every 30 seconds
                setInterval(() => {
                    this.fetchNotifications();
                }, 30000);
            },

            methods: {
                fetchNotifications() {
                    this.$axios.get(this.urlIndex)
                        .then(response => {
                            this.count = response.data.count;
                            this.notifications = response.data.notifications;
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                        });
                },

                markAllAsRead() {
                    this.$axios.post(this.urlReadAll)
                        .then(() => {
                            this.count = 0;
                            this.notifications = [];
                        })
                        .catch(error => {
                            console.error('Error marking notifications as read:', error);
                        });
                },

                getTypeClass(type) {
                    switch (type) {
                        case 'lead':
                            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                        case 'invoice':
                            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                        case 'activation':
                            return 'bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-400';
                        default:
                            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
                    }
                },

                getTypeIcon(type) {
                    switch (type) {
                        case 'lead':
                            return 'icon-leads';
                        case 'invoice':
                            return 'icon-quote';
                        case 'activation':
                            return 'icon-success';
                        default:
                            return 'icon-info';
                    }
                }
            }
        });
    </script>
@endpush
