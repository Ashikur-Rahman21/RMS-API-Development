<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'table_id' => ['required', 'integer', 'exists:tables,id'],
            'status' => ['sometimes', 'string', 'in:open,booked,hold', 'max:255'],
            'date' => ['required', 'date', 'after:yesterday'],
            'start_time' => ['required'],
            'num_guest' => ['required', 'integer'],
        ];
    }
}
