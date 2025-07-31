<x-guest-layout>
    <div class="max-w-md mx-auto mt-16 p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Verifikasi Email Baru</h2>
        <p class="text-sm text-center text-gray-600 dark:text-gray-300 mb-6">
            Masukkan kode OTP yang telah dikirim ke email baru Anda.
        </p>

        <form method="POST" action="{{ route('email.otp.verify') }}" class="space-y-6">
            @csrf

            <div>
                <label for="otp_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Kode OTP
                </label>
                <input type="text" id="otp_code" name="otp_code" required autofocus
                       class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-orange-500 dark:bg-gray-700 dark:text-white focus:outline-none" />
                @error('otp_code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-md transition duration-200">
                    Verifikasi
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>