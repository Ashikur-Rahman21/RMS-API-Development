<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'table_id' => ['sometimes', 'integer', 'exists:tables,id'],
            'status' => ['sometimes', 'string', 'in:open,booked,hold', 'max:255'],
            'date' => ['sometimes', 'date', 'after:yesterday'],
            'start_time' => ['sometimes'],
            'end_time' => ['sometimes'],
            'num_guest' => ['sometimes', 'integer'],
        ];
    }
}
