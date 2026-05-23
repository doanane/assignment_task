<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Support Tracker') | Npontu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { DEFAULT: '#1d4ed8', dark: '#1e3a8a', light: '#dbeafe' } }
                }
            }
        }
    </script>
    <style>
        .badge-done    { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800; }
        .badge-pending { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

{{-- Navigation --}}
<nav class="bg-blue-700 shadow-md">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <span class="text-white font-bold text-lg tracking-tight">Support&nbsp;Tracker</span>
                <div class="hidden sm:flex gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                              {{ request()->routeIs('dashboard') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        Daily&nbsp;View
                    </a>
                    <a href="{{ route('reports') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                              {{ request()->routeIs('reports') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-600 hover:text-white' }}">
                        Reports
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-blue-200 text-xs">{{ auth()->user()->department ?? ucfirst(auth()->user()->role) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-3 py-1.5 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Mobile nav --}}
        <div class="sm:hidden pb-3 flex gap-2">
            <a href="{{ route('dashboard') }}" class="text-blue-100 hover:text-white text-sm px-2 py-1">Daily View</a>
            <a href="{{ route('reports') }}"   class="text-blue-100 hover:text-white text-sm px-2 py-1">Reports</a>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="max-w-6xl mx-auto mt-4 px-4">
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700 ml-4 font-bold">&times;</button>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="max-w-6xl mx-auto mt-4 px-4">
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    @yield('content')
</main>

<script>
// "Other" dropdown logic: show a text input when Other is selected
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-other-toggle]').forEach(function (select) {
        var targetId = select.dataset.otherToggle;
        var input    = document.getElementById(targetId);
        function toggle() {
            if (select.value === '__other__') {
                input.classList.remove('hidden');
                input.required = true;
                input.focus();
            } else {
                input.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }
        select.addEventListener('change', toggle);
        toggle(); // run on load in case browser restores a value
    });
});
</script>
</body>
</html>
