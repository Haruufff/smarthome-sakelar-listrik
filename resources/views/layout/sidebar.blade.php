<aside id="sidebar" class="fixed top-0 left-0 z-0 w-61 p-3 pt-25 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white">
    <nav class="inline-flex flex-col space-y-2">
        <div>
            <span class="text-xs font-semibold pl-2 pb-5">HOME</span>
            <div class="py-2 pb-5">
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active-menu' : '' }} hover:bg-[#5d87ff] hover:text-white rounded-lg inline-flex items-center menu py-3 pl-2 pr-27 space-x-3" alt="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                        <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                      </svg>
                      <span class="text-menu text-sm">Dashboard</span>
                </a>
            </div>

            <span class="text-xs font-semibold pl-2 pb-5">CONTROLING</span>
            <div class="py-2 pb-10">
                <a href="{{ route('switches') }}" class="{{ request()->is('switches') ? 'active-menu' : '' }} hover:bg-[#5d87ff] hover:text-white rounded-lg inline-flex items-center menu py-3 pl-2 pr-27 space-x-3" alt="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    <span class="text-menu text-sm">Switches</span>
                </a>
            </div>

            <span class="text-xs font-semibold pl-2 pb-5">MONITORING</span>
            <div class="py-2 pb-10 space-y-1">
                <a href="{{ route('realtime') }}" class="{{ request()->is('monitoring/realtime') ? 'active-menu' : '' }} hover:bg-[#5d87ff] hover:text-white rounded-lg inline-flex items-center menu py-3 pl-2 pr-10 space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M2.25 2.25a.75.75 0 0 0 0 1.5H3v10.5a3 3 0 0 0 3 3h1.21l-1.172 3.513a.75.75 0 0 0 1.424.474l.329-.987h8.418l.33.987a.75.75 0 0 0 1.422-.474l-1.17-3.513H18a3 3 0 0 0 3-3V3.75h.75a.75.75 0 0 0 0-1.5H2.25Zm6.54 15h6.42l.5 1.5H8.29l.5-1.5Zm8.085-8.995a.75.75 0 1 0-.75-1.299 12.81 12.81 0 0 0-3.558 3.05L11.03 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l2.47-2.47 1.617 1.618a.75.75 0 0 0 1.146-.102 11.312 11.312 0 0 1 3.612-3.321Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-menu text-sm">Monitoring Realtime</span>
                </a>

                <a href="{{ route('history') }}" class="{{ request()->is('monitoring/history') ? 'active-menu' : '' }} hover:bg-[#5d87ff] hover:text-white rounded-lg inline-flex items-center menu py-3 pl-2 pr-12 space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M2.25 2.25a.75.75 0 0 0 0 1.5H3v10.5a3 3 0 0 0 3 3h1.21l-1.172 3.513a.75.75 0 0 0 1.424.474l.329-.987h8.418l.33.987a.75.75 0 0 0 1.422-.474l-1.17-3.513H18a3 3 0 0 0 3-3V3.75h.75a.75.75 0 0 0 0-1.5H2.25Zm6.04 16.5.5-1.5h6.42l.5 1.5H8.29Zm7.46-12a.75.75 0 0 0-1.5 0v6a.75.75 0 0 0 1.5 0v-6Zm-3 2.25a.75.75 0 0 0-1.5 0v3.75a.75.75 0 0 0 1.5 0V9Zm-3 2.25a.75.75 0 0 0-1.5 0v1.5a.75.75 0 0 0 1.5 0v-1.5Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-menu text-sm">Monitoring History</span>
                </a>
            </div>
        </div>
    </nav>
</aside>