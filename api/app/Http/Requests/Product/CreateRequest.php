<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use App\Models\Product\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:10|max:255',
            'article' => 'required|string|regex:/^[a-z0-9]+$/i|unique:products,article',
            'status' => ['required', 'string', Rule::in(array_keys(Product::statusList()))],
        ];
    }
}
