<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . ($this->article ? decode($this->article) : 'NULL') . ',id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'slug' => 'slug',
            'meta_title' => 'meta judul',
            'meta_description' => 'meta deskripsi',
            'content' => 'konten',
            'excerpt' => 'ringkasan',
            'featured_image' => 'gambar utama',
            'thumbnail' => 'thumbnail',
            'is_featured' => 'artikel unggulan',
            'status' => 'status',
            'published_at' => 'tanggal publikasi',
            'categories' => 'kategori',
            'tags' => 'tag',
        ];
    }
}
