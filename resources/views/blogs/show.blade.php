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
            class="bg-green-400 font-semibold py-4 px-16 text-white text-center my-4 absolute top-0 left-1/2 transform -translate-x-1/2 rounded-md shadow-2xl">
            {{ session('error') }}
        </div>

        <script>
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 3000);
        </script>
    @endif

    <section>
        <div class="mb-8">
            <a href="{{ request('redirect') === 'user-blogs' ? '/user/blogs' : '/blogs' }}"
                class="inline-flex items-center text-gray-700 hover:text-gray-900 group" title="Go to blogs">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-2 group-hover:text-[#387478] transition-colors duration-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
        <div class="border-b border-gray-400">
            <div class="">
                <img src="{{ $blog->blog_image_url }}" alt="" class="w-full h-[400px] object-cover rounded-md">
            </div>
            <div class="py-4 space-y-2">
                <p class="text-[#6941C6] font-semibold">
                    {{-- @dump($blog->created_at) --}}
                    {{ $blog->created_at }}</p>
                <p class="text-xl font-semibold text-[#1A1A1A]">{{ $blog->title }}</p>
                <p class="text-[#667085]">{{ $blog->content }}</p>
            </div>
        </div>
        @can('edit-blog', $blog)
            <div class="my-5 flex justify-end space-x-4 p-4">
                <form action="/blogs/{{ $blog->id }}" method="POST">
                    @csrf
                    @method('delete')
                    <button type="submit"
                        class="text-white px-2 py-1 bg-red-500 rounded-md hover:bg-red-600">Delete</button>
                </form>
                <a href="/blogs/{{ $blog->id }}/edit"
                    class="text-white px-2 py-1 bg-blue-500 rounded-md hover:bg-blue-600">Edit</a>
            </div>
        @endcan

    </section>
</x-layout>
