<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if (!Auth::check()) {
        //     return false; 
        // }

        // if ($this->isMethod('post')) {
        //     return true; 
        // }

        // if ($this->isMethod('put') || $this->isMethod('patch')) {
        //     $blog_id = $this->route('id'); 
        //     $blog = Blog::findOrFail($blog_id);
        //     return $blog && $blog->user_id === Auth::id();
        // }

        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $hasToUpdate = $this->isMethod('put') || $this->isMethod('patch');
        return [
            "title" => 'required|string|max:255',
            "content" => 'required|string',
            "image" => $hasToUpdate
                ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
