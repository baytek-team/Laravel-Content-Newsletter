<?php

namespace Baytek\Laravel\Content\Types\Newsletter\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
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
            'title' => 'required',
            'key' => 'required|unique_in_type:newsletter',
            // 'content' => 'required',
            // 'parent_id' => 'required|exists:contents,id',
            'newsletter_date' => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'key.unique_in_type' => 'This newsletter already exists. Please choose a different title or newsletter date.',
        ];
    }
}
