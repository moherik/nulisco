<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'title' => 'required|string|max:100',
            'slug' => 'required|string',
            'body' => 'required',
            'status' => 'string',
            'tags.*.title' => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus diisi',
            'max' => ':attribute maksimal :max karakter',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'user_id' => 'id user',
            'title' => 'judul',
            'slug' => 'slug',
            'body' => 'konten',
            'tags.*.title' => 'tag',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => (int)$this->user()->id,
            'title' => ucwords(strtolower($this->title)),
            'slug' => Str::slug($this->title) . '-' . time(),
        ]);
    }
}
