<div class="card">
    <div class="py-10 px-5 space-y-10">
        @foreach ($switches as $switch)
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold py-3" id="switch-name-{{ $switch->id }}">{{ $switch->name }}</h1>
                <div class="flex items-center justify-self-end">
                    <label for="switch-{{ $switch->id }}" class="relative w-20 h-10">
                        <input type="checkbox" class="sr-only switch-checkbox"
                        id="switch-{{ $switch->id }}"
                        {{ $switch->is_actived == 1 ? 'checked' : '' }}
                        data-id="{{ $switch->id }}" 
                        data-state-status="{{ $switch->state_status }}" 
                        data-is-actived="{{ $switch->is_actived }}">
                        <span id="bg-{{ $switch->id }}" class="{{ $switch->is_actived == 1 ? 'bg-green-300' : 'bg-red-300' }} switch-bg absolute w-20 h-10 rounded-full transition-all duration-300"></span>
                        <span id="dot-{{ $switch->id }}" class="{{ $switch->is_actived == 1 ? 'left-11' : 'left-2' }} switch-dot w-2/5 h-4/5 bg-white absolute rounded-full shadow-md top-1 transition-all duration-300"></span>
                    </label>

                    <button class="open-switch-modal ml-3 px-2 py-2 bg-gray-100 rounded-3xl cursor-pointer" type="button" data-switch-id="{{ $switch->id }}" data-switch-name="{{ $switch->name }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>