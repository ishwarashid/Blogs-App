<x-auth-layout>
    <x-slot:heading>
        Login
    </x-slot:heading>
    @if (session('success'))
        <div id="success-message"
            class="bg-green-400 font-semibold py-4 px-16 text-white text-center my-4 absolute top-0 left-1/2 transform -translate-x-1/2 rounded-md shadow-2xl">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                document.getElementById('success-message').style.display = 'none';
            }, 3000);
        </script>
    @endif

    @if (session('error'))
        <div id="error-message"
            class="bg-red-400 font-semibold py-4 px-16 text-white text-center my-4 absolute top-0 left-1/2 transform -translate-x-1/2 rounded-md shadow-2xl">
            {{ session('error') }}
        </div>

        <script>
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 3000);
        </script>
    @endif
    @error('credentialsNotMatch')
        <p class="text-red-500 text-sm my-5">{{ $message }}</p>
    @enderror
    <form action="/login" method="POST">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" id="email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500 focus-within:ring-[#4d6474]"
                value="{{ old('email') }}" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" id="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500 focus-within:ring-[#4d6474]"
                required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-[#243642] hover:bg-[#1A1A1A] text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring">Login</button>
        </div>
    </form>
</x-auth-layout>
