<x-mail::message>
# {{$blog->title}}

Congrats! Your blog is now live at our website.

<x-mail::button :url="url('/blogs/'.$blog->id)">
View Your Blog 
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
