@forelse ($historyMonitoring as $item)
    <div class="card">
        <div class="flex justify-between p-2">
            <h1 class="header-1">Month : {{ $item->month_name }}</h1>
            @if ($item->last_data)
                <div class="p-3">
                    <span class=" font-bold">Cost : </span>
                    <span>Rp {{ number_format($item->last_data->total_price, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>
        <div class="space-y-5">
            <div>
                <div id="chart{{ $item->year }}-{{ $item->month }}"></div>
            </div>
        </div>
    </div>
@empty
    <div class="card">
        <div class="py-10 px-5 space-y-10">
            <h1 class="header-1 text-center">No monitoring history available.</h1>
        </div>
    </div>
@endforelse