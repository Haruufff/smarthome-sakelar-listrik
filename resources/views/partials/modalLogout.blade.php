{{-- Modal Logout --}}
<div id="logoutModal" class="modal z-10 opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <div class="modal-content py-4 text-left px-6">
            <div class="flex justify-between items-center pb-3">
                <span class="text-2xl font-bold">Log Out!</span>
                <div class="modal-close cursor-pointer z-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <div>
                <span>Are you sure you want to log out?</span>
            </div>

            <div class="flex justify-end pt-2 gap-2">
                <button type="button" data-action="logout" class="bg-red-300 text-white py-2 px-5 rounded-md hover:bg-red-400">Logout</button>
            </div>
        </div>
    </div>
</div>