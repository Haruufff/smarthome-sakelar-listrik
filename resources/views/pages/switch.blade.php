@extends('layout.layout')

@section('content')
<div class="content sm:mx-10 sm:pt-10 sm:flex-col space-y-5 grow sm:pl-60">
    <h1 class="text-3xl font-bold max-sm:text-center mb-10 pt-10">Switches</h1>
    @include('partials.switches')
    @include('partials.modalEditSwitch')
</div>
@endsection

@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/editSwitches.js') }}"></script>
    <script src="{{ asset('assets/js/switches.js') }}"></script>
@endpush