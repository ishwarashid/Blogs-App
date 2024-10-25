<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="relative">
    <header>
        <nav class="flex justify-end items-center space-x-5 border-b border-gray-500 py-6 px-16">
            <x-nav-link href='/blogs' :active="request()->is('blogs')">Blogs</x-nav-link>
            <x-nav-link href='/user/blogs' :active="request()->is('user/blogs')">Your Posts</x-nav-link>
            <x-nav-link href='/blogs/create' :active="request()->is('blogs/create')">Create</x-nav-link>
            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="bg-[#243642] hover:bg-[#1A1A1A] text-white font-bold py-2 px-3 rounded focus:outline-none focus:ring">Logout</button>

            </form>
        </nav>

        @if (request()->is('blogs'))
            <h1 class="w-full text-center text-[10vw] font-bold uppercase text-[#1A1A1A] py-4 border-b border-gray-500">
                THE BLOG</h1>
        @endif
    </header>
    <main class="py-6 px-16">
        {{ $slot }}
    </main>
</body>

</html>
