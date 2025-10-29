@extends('layout.layout')

@section('content')
<div class="content sm:mx-10 sm:pt-10 sm:flex-col space-y-5 grow sm:pl-60">
    <h1 class="text-3xl font-bold max-sm:text-center sm:ml-5 mb-10 pt-10 cursor-pointer">Profile</h1>
    <div class="rounded-xl border border-gray-200 px-4 pb-5 bg-white">
        <div class="py-5">
            <span class="text-lg font-semibold pl-2">Profile</span>
        </div>
        <div class="px-2 mb-2">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
            <input type="text" id="display-name" class="bg-[#edf2f7] border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="{{ auth()->user()->name }}" disabled readonly>
        </div>
        <div class="px-2 pt-3 mb-2">
            <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
            <input type="text" id="display-username" class="bg-[#edf2f7] border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="{{ auth()->user()->username }}" disabled readonly>
        </div>
        <div class="px-2 pt-3 mb-2">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
            <input type="text" id="display-email" class="bg-[#edf2f7] border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="{{ auth()->user()->email }}" disabled readonly>
        </div>
        <div class="flex justify-end pt-2 px-2 gap-5">
            <button type="button" data-modal-open="passwordModal" class="bg-[#5d87ff] text-white font-semibold py-2 px-5 rounded-md hover:bg-[#85a4f8] cursor-pointer">Change Password</button>
            <button type="button" data-modal-open="profileModal" class="bg-[#5d87ff] text-white font-semibold py-2 px-5 rounded-md hover:bg-[#85a4f8] cursor-pointer">Edit Profile</button>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 px-4 pb-5 sm:mb-30 bg-white">
        <div class="py-5">
            <span class="text-lg font-semibold pl-2 pb-5">Internet Connection</span>
        </div>

        <div class="px-2 mb-2">
            <label for="ssid" class="block mb-2 text-sm font-medium text-gray-900">Connection</label>
            <input type="text" id="display-ssid" class="bg-[#edf2f7] border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="{{ auth()->user()->ssid }}" disabled readonly>
        </div>

        <div class="px-2 pt-3 mb-2">
            <span class="text-md py-3">Password</span>
            <input type="text" id="display-ssid-pass" class="bg-[#edf2f7] border border-gray-300 text-gray-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="{{ auth()->user()->ssid_pass }}" disabled readonly>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" data-modal-open="connectionModal" class="bg-[#5d87ff] text-white font-semibold py-2 px-5 rounded-md hover:bg-[#85a4f8] cursor-pointer">Edit Connection</button>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 px-4 p-4 mb-10 bg-white sm:sr-only">
        <button type="button" data-modal-open="logoutModal" class="text-md py-3 bg- w-full bg-red-300 text-white rounded-md">
            Logout
        </button>
    </div>
</div>

@include('partials.modalEditProfile')
@include('partials.modalEditPassword')
@include('partials.modalEditConnection')
@endsection

@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/editProfile.js') }}"></script>
    <script src="{{ asset('assets/js/editConection.js') }}"></script>
    <script src="{{ asset('assets/js/editPassword.js') }}"></script>
    <script src="{{ asset('assets/js/logout.js') }}"></script>
@endpush