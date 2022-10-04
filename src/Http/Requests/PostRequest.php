<?php

declare(strict_types=1);

namespace Companypost\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status'      => 'required|in:active,inactive',
            'quote'       => 'required|min:3|max:330',
            'content'     => 'required',
            'is_featured' => 'required|in:0,1',
            'tag_id'      => 'required|exists:tags,id',
        ]
        +
        ($this->isMethod('POST') ? $this->createRules() : $this->updateRules());
    }

    public function createRules(): array
    {
        return [
            'title' => 'required|unique:posts,title|min:3|max:200',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function updateRules(): array
    {
        return [
            'title' => 'required|min:3|max:120|unique:posts,title,'.$this->post.',id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'photo' => 'required',
        ];
    }
}
