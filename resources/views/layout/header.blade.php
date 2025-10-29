<header class="max-sm:sr-only sticky top-0 z-1 text-gray-700 body-font border-b border-gray-200 bg-white">
    <div class="flex flex-row justify-between p-5 items-center">
        <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0" href="#">
            <span class="ml-3 text-xl">SMARTHOME - SAKLAR LISTRIK</span>
        </a>

        <li class="group list-none">
            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </a>
            <ul class="hidden absolute bg-white top-23 right-7.5 rounded-lg shadow-lg group-focus-within:block p-5 space-y-2">
                <li>
                    <span id="display-name" class="text-md font-semibold pl-1">
                        Hi, {{ auth()->user()->name }}
                    </span>
                </li>
                <hr>
                <li>
                    <a href="{{ route('profile') }}" class="text-md pl-2 pr-30 py-1 hover:bg-[#5d87ff] hover:text-white rounded-md">
                        Profile
                    </a>
                </li>
                <li>
                    <button type="button" data-modal-open="logoutModal" class="text-md pl-2 pr-30 py-1 hover:bg-[#5d87ff] hover:text-white rounded-md cursor-pointer">
                        Logout
                    </button>
                </li>
            </ul>
        </li>
    </div>
</header>

@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ asset('assets/js/modal.js') }}"></script>
    <script src="{{ asset('assets/js/logout.js') }}"></script>
@endpush