<x-auth-layout>
    <x-slot:heading>
        Welcome
    </x-slot:heading>
    <div class="flex flex-col space-y-4 mt-10">
        <a href="/register"
            class="bg-[#243642] hover:bg-[#1A1A1A] text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring text-center">Create
            a new Account</a>
        <div class="flex items-center px-16">
            <div class="w-full h-[2px] bg-gray-300"></div>
            <p class="text-[#243642] mx-3">OR</p>
            <div class="w-full h-[2px] bg-gray-300"></div>
        </div>
        <a href="/login"
            class="bg-[#243642] hover:bg-[#1A1A1A] text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring text-center">Already
            has an Account?</a>
    </div>

</x-auth-layout>
