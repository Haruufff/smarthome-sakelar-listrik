<div class="card">
    <div class="pt-5">
        <div class="flex justify-between items-center">
            <h1 class="header-1">Realtime Overview</h1>
            @foreach ($taxes as $item)
                <button type="button" data-taxes-id="1" data-category-tax-id="{{ $item->category_tax_id }}" id="display-taxes" class="open-taxes-modal border-1 font-semibold py-2 px-5 rounded-md hover:bg-[#5d87ff] hover:text-white cursor-pointer">{{ $item->name }}</button>
            @endforeach
        </div>
    </div>
    <div class="relative w-full">
        <div id="RealtimeMonitoringChart"></div>
    </div>
</div>