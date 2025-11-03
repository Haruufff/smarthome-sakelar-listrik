@extends('layout.layout')

@section('content')
    <div class="content sm:mx-10 sm:pt-10 sm:flex-col space-y-5 grow pb-25 sm:pl-60">
        <h1 class="text-3xl font-bold max-sm:text-center mb-10 pt-10">Monitoring History</h1>
        @include('partials.chartMonthlyOverview')
        @include('partials.chartMonthly')
    </div>
@endsection

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/chartMonthlyAverage.js') }}"></script>
    <script src="{{ asset('assets/js/chartMonthlyPrice.js') }}"></script>
    <script src="{{ asset('assets/js/chartMonthlyData.js') }}"></script>

    <script>
        window.monitoringConfig = {
            monthlyAverageEnable: true,
            monthlyPriceEnable: true,
            monthlyAverageApiUrl: `{{ url("/api/monitoring/monthly-average") }}`,
            monthlyPriceApiUrl: `{{ url("/api/monitoring/monthly-total-price") }}`,
            monthlyApiUrl: `{{ url("/api/monitoring") }}`,
            monthDatas: @json($historyMonitoring->map(function($item) {
                return [
                    'year' => $item->year,
                    'month' => $item->month
                ];
            }))
        };
    </script>

    <script src="{{ asset('assets/js/monthlyMonitoring.js') }}"></script>
@endpush