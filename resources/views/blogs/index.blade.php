<x-layout>

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

    <div class="flex justify-between">
        <h2 class="text-3xl font-bold">Recent Blog Posts</h2>
        <form action="/blogs" method="GET" class="mb-6">
            <input type="text" name="search" placeholder="Search blogs..."
                class="border p-2 rounded focus:border focus:border-gray-600 focus:outline-none" />
            <button type="submit" class="bg-[#243642] hover:bg-[#1A1A1A] text-white py-2 px-4 rounded">Search</button>
        </form>
    </div>
    @if (isset($noBlogs))
    <div class="flex justify-center mt-16">
        <p class="text-gray-600 font-bold text-2xl">{{ $noBlogs }}</p>
    </div>
    @else
        <section class="py-10 space-y-4">
            @foreach ($blogs as $blog)
                @include('blogs._blog_card', ['blog' => $blog])
            @endforeach
        </section>
        <div>
            {{
                $blogs->links()
            }}
        </div>
    @endif

</x-layout>


