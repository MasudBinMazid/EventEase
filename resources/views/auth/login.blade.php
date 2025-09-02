<x-guest-layout>
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-2xl font-semibold tracking-tight">
            Welcome back to <span class="text-cyan-600">EventEase</span>
        </h1>
        <p class="mt-1 text-sm text-gray-500">Please log in to buy tickets.</p>
    </div>

    <!-- Status / Errors -->
    @if (session('status'))
        <div class="mt-4 rounded-md bg-green-50 p-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Card -->
    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <!-- Heroicon: envelope -->
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M2.94 6.34 10 10.88l7.06-4.54A2 2 0 0 0 16.5 4h-13a2 2 0 0 0-.56 2.34Z"/>
                        <path d="M18 7.7 10.4 12a1 1 0 0 1-1 0L2 7.7V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.7Z"/>
                    </svg>
                </span>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded-lg border-gray-300 pl-10 pr-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="you@example.com"
                />
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <!-- Heroicon: lock-closed -->
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 8V6a5 5 0 1 1 10 0v2h1a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a1 1 0 0 1 1-1h1Zm2-2a3 3 0 1 1 6 0v2H7V6Z" clip-rule="evenodd"/>
                    </svg>
                </span>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="block w-full rounded-lg border-gray-300 pl-10 pr-10 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="••••••••"
                />
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input id="remember_me" name="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    Forgot your password?
                </a>
            @endif
        </div>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            Log in
        </button>
    </form>

    <!-- Sign up CTA -->
    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-gray-600">
            New to EventEase?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-700">
                Create an account
            </a>
        </p>
    @endif

    <!-- Small footer -->
    <p class="mt-6 text-center text-xs text-gray-400">
        Secure login · We never share your email.
    </p>
</x-guest-layout>
