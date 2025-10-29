@extends('layout.layout')

@section('content')
<div class="content sm:mx-10 sm:pt-10 sm:flex-col space-y-5 grow sm:pl-60">
    <h1 class="text-3xl font-bold max-sm:text-center mb-10 pt-10">Dashboard</h1>
    <div class="grid grid-cols-3 gap-3 max-lg:grid-cols-1">
        <a href="{{ route('switches') }}">
            <div class="font-semibold max-xl:text-sm text-lg rounded-lg border border-gray-100 pl-3 py-5 pr-45 bg-white shadow-[0px_0px_5px_rgba(0,0,0,0.15)]">Switches</div>
        </a>
        <a href="{{ route('realtime') }}">
            <div class="font-semibold max-xl:text-sm text-lg rounded-lg border border-gray-100 pl-3 py-5 pr-30 bg-white shadow-[0px_0px_5px_rgba(0,0,0,0.15)]">Monitoring Realtime</div>
        </a>
        <a href="{{ route('history') }}">
            <div class="font-semibold max-xl:text-sm text-lg rounded-lg border border-gray-100 pl-3 py-5 pr-30 bg-white shadow-[0px_0px_5px_rgba(0,0,0,0.15)]">Monitoring History</div>
        </a>
    </div>
    @include('partials.chartCostMonthlyOverview')
</div>
@endsection

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/chartMonthlyPrice.js') }}"></script>

    <script>
        window.monitoringConfig = {
            monthlyPriceEnable: true,
            monthlyPriceApiUrl: `{{ url("/api/monitoring/monthly-total-price") }}`,
        };
    </script>

    <script src="{{ asset('assets/js/DashboardMonitoring.js') }}"></script>
@endpush