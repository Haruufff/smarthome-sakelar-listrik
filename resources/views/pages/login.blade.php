<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Smarhome - IoT</title>
</head>
<body>
    <div class="min-h-screen bg-[#edf2f7] flex justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="py-12 px-5 bg-white rounded-lg shadow-xl z-20 2xl:w-150 max-2xl:w-100 max-md:w-full justify-center items-center">
            <h1 class="text-3xl font-extrabold text-center mb-5 cursor-pointer">Smarthome</h1>

            @if (session()->has('loginError'))
                <div class="bg-red-100 p-3 w-full my-5 rounded-lg">
                    <div class="flex space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-none fill-current text-red-500 h-4 w-4">
                            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.597 17.954l-4.591-4.55-4.555 4.596-1.405-1.405 4.547-4.592-4.593-4.552 1.405-1.405 4.588 4.543 4.545-4.589 1.416 1.403-4.546 4.587 4.592 4.548-1.403 1.416z" />
                        </svg>
                        <div class="leading-tight flex flex-col space-y-2">
                            <div class="text-sm font-medium text-red-700">Login Error!</div>
                            <div class="flex-1 leading-snug text-sm text-red-600">{{ session('loginError') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="">
                <form action="/login" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label for="username" class="appearance-none">Username</label>
                            <div class="flex items-center mt-1">
                                <div class="appearance-none absolute border-r border-gray-300 px-3 max-sm:py-2.5 sm:py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z">
                                    </svg>
                                </div>
                                <input type="text" id="username" name="username" class="appearance-none rounded-md relative block w-full px-3 py-2 pl-12 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#5d87ff] focus:border-[#5d87ff] focus:z-10 sm:text-sm" placeholder=" Type your username" required value="{{ old('username') }}">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="appearance-none">Password</label>
                            <div class="flex items-center mt-1">
                                <div class="appearance-none absolute border-r border-gray-300 px-3 max-sm:py-2.5 sm:py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                      </svg>
                                </div>
                                <input type="password" id="password" name="password" class="appearance-none rounded-md relative block w-full px-3 py-2 pl-12 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-[#5d87ff] focus:border-[#5d87ff] focus:z-10 sm:text-sm" placeholder=" Type your password" required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" value="submit" class="appearance-none relative w-full flex justify-center py-2 px-4 border border-transparent text-lg font-semibold rounded-md text-white bg-[#5d87ff]">Log In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>