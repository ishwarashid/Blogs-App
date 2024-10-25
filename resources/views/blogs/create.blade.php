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
            <a href="javascript:history.back()" class="inline-flex items-center text-gray-700 hover:text-gray-900 group"
                title="Go Back">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-2 group-hover:text-[#387478] transition-colors duration-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>
        <div class="container mx-auto mt-8">
            <h1 class="text-3xl font-bold mb-6">Create a New Blog Post</h1>

            <form action="/blogs" method="POST" enctype="multipart/form-data"
                class="bg-white shadow-md rounded pt-6 pb-8 mb-4">
                @csrf

                <div class="flex justify-center">
                    <div class="mb-8 w-[200px] ring-2 ring-gray-600" id="imagePreviewContainer" style="display:none;">
                        <img id="imagePreview" class="w-full h-[200px] object-cover rounded-md shadow-md">
                    </div>
                </div>


                <div class="mb-8">
                    <label for="image" class="block font-medium leading-6 text-gray-900">Upload Image</label>
                    <div class="mt-2">
                        <label
                            class="flex items-center justify-center w-full px-4 py-6 bg-white text-[#243642] rounded-lg shadow-md tracking-wide uppercase border border-gray-300 cursor-pointer hover:bg-gray-100 hover:text-gray-600">
                            <i class="fa-solid fa-upload text-[24px]"></i>
                            <span id="fileLabel" class="ml-2 text-base leading-normal">Select an image</span>
                            <input type="file" name="image" id="image" accept="image/*" class="hidden"
                                onchange="previewImage(event)">
                        </label>
                        @error('image')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="mb-8">
                    <label for="title" class="block font-medium leading-6 text-gray-900">Title</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-md ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-600">
                            <input type="text" name="title" id="title"
                                class="block flex-1 border-0 bg-transparent py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:leading-6 focus:outline-none"
                                placeholder="Blog Title" value="">
                        </div>
                        @error('title')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="content" class="block font-medium leading-6 text-gray-900">Content</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-md ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-600">
                            <textarea name="content" id="content" rows="5" required
                                class="block flex-1 border-0 bg-transparent py-2 px-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:leading-6 focus:outline-none"
                                placeholder="Write your blog content here..."></textarea>
                        </div>
                        @error('content')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-14">
                    <button type="submit"
                        class="bg-[#243642] hover:bg-[#1A1A1A] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Blog
                    </button>
                </div>
            </form>
        </div>
    </section>


    <script>
        function previewImage(event) {
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const fileLabel = document.getElementById('fileLabel');

            const file = event.target.files[0];
            if (file) {
                fileLabel.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                fileLabel.textContent = "Select an image";
                imagePreviewContainer.style.display = 'none';
            }
        }
    </script>
</x-layout>
