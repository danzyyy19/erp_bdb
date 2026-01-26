<x-guest-layout>
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-800 p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Selamat Datang</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
            @csrf

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-5 h-5 text-zinc-400"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="email@example.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-zinc-400"></i>
                    </div>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                        autocomplete="current-password"
                        class="w-full pl-11 pr-12 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="••••••••">
                    <!-- Eye Icon Toggle -->
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                        <i data-lucide="eye" class="w-5 h-5" x-show="!showPassword"></i>
                        <i data-lucide="eye-off" class="w-5 h-5" x-show="showPassword" x-cloak></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative">
                        <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                        <div
                            class="w-5 h-5 rounded-md border-2 border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all flex items-center justify-center">
                            <i data-lucide="check" class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100"></i>
                        </div>
                    </div>
                    <span
                        class="text-sm text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-200 transition-colors">
                        Ingatkan Saya
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-200 flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                <span>Masuk</span>
            </button>
        </form>
    </div>
</x-guest-layout>