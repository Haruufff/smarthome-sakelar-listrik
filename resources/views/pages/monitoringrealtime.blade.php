@extends('layout.layout')

@section('content')
<div class="content sm:mx-10 sm:pt-10 sm:flex-col space-y-5 grow sm:pl-60">
    <h1 class="text-3xl font-bold max-sm:text-center mb-10 pt-10">Monitoring Realtime</h1>
    @include('partials.numericRealtimeMonitoring')
    @include('partials.chartrealtimeMonitoring')
</div>

@include('partials.modalEditDaya')
@endsection

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/editTaxes.js') }}"></script>
    <script src="{{ asset('assets/js/chartRealtimeMonitoring.js') }}"></script>
    <script src="{{ asset('assets/js/numericRealtimeMonitoring.js') }}"></script>

    <script>
        window.monitoringConfig = {
            realtimeMonitoringEnable: true,
            realtimeApiUrl: `{{ url("/api/monitoring/realtime-chart") }}`,
            // realtimeMonths: @json($realtimeMonitoring),
        };
    </script>

    <script src="{{ asset('assets/js/realtimeMonitoring.js') }}"></script>
@endpush