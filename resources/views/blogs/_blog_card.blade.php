<a href="{{ isset($redirect) ? '/blogs/' . $blog->id . '?redirect=' . $redirect : '/blogs/' . $blog->id }}">
    <div class="grid grid-cols-3 shadow-lg gap-3 py-4">
        <div class="flex justify-center items-center">
            <img src="{{ $blog->blog_image_url }}" alt="" class="w-full h-48 object-cover rounded-md">
        </div>
        <div class="col-span-2 px-4 space-y-2">
            <p class="text-[#6941C6] font-semibold">
                {{ $blog->created_at}}</p>
            <p class="text-xl font-semibold text-[#1A1A1A]">{{ $blog->title }}</p>
            <p class="text-[#667085]">
                {{ substr($blog->content, 0, 200) }}
                <a href="{{ isset($redirect) ? '/blogs/' . $blog->id . '?redirect=' . $redirect : '/blogs/' . $blog->id }}"
                    class="text-[#C11574]">See more...</a>
            </p>
        </div>
    </div>
</a>
