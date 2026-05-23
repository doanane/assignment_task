<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Support Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4 py-10">

<div class="w-full max-w-md">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-700 rounded-2xl shadow-lg mb-4">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
        <p class="text-gray-500 text-sm mt-1">Join the Support Tracker team</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="John Doe">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="john@company.com">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Min. 6 chars">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Repeat password">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department" id="dept-select"
                        data-other-toggle="dept-custom"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select department</option>
                    @foreach(['SMS Support','Network Support','Database Support','Application Support','Infrastructure','Security','Management'] as $dept)
                        <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                    <option value="__other__" {{ old('department') === '__other__' ? 'selected' : '' }}>Other (specify)</option>
                </select>
                <input type="text" name="department_custom" id="dept-custom"
                       value="{{ old('department_custom') }}"
                       placeholder="Type your department..."
                       class="hidden mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0XX XXX XXXX">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                    <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="support" {{ old('role','support') === 'support' ? 'selected' : '' }}>Support Staff</option>
                        <option value="admin"   {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-lg transition-colors mt-2">
                Create Account
            </button>
        </form>

        <p class="text-sm text-center text-gray-500 mt-5">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var select = document.getElementById('dept-select');
    var input  = document.getElementById('dept-custom');
    function toggle() {
        if (select.value === '__other__') {
            input.classList.remove('hidden'); input.required = true; input.focus();
        } else {
            input.classList.add('hidden'); input.required = false; input.value = '';
        }
    }
    select.addEventListener('change', toggle);
    toggle();
});
</script>

</body>
</html>
