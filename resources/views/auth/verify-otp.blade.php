<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white text-center">Verifikasi OTP</h2>

        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Masukkan kode OTP yang telah dikirim ke email <strong>{{ session('otp_register.email') }}</strong>
        </p>


        <form method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="otp_code" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Kode OTP
                </label>
                <input id="otp_code" type="text" name="otp_code" required autofocus
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-orange-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" />
                @error('otp_code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-md transition">
                    Verifikasi
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
