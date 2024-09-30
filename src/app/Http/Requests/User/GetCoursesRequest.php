<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class GetCoursesRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search_field' => 'string|required_with:       ',
            'search_text' => 'string|required_with:search_field',
            'per_page' => 'integer|min:1|max:100',
            'sort_by' => 'string',
            'sort_order' => 'string|in:asc,desc',
            'release_date' => 'date|required_with:date_comparison',
            'date_comparison' => 'string|required_with:release_date|in:' . implode(',', [
                self::EQUAL,
                self::GREATER_THAN,
                self::LESS_THAN,
                self::GREATER_THAN_OR_EQUAL,
                self::LESS_THAN_OR_EQUAL,
                self::NOT_EQUAL,
            ]),
        ];
    }
}
