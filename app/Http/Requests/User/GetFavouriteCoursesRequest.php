<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class GetFavouriteCoursesRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => 'integer|min:1|max:100',
            'sort_by' => 'string',
            'sort_order' => 'string|in:asc,desc'
        ];
    }
}
