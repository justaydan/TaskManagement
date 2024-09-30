<?php

namespace App\Http\Requests;

use App\Constant\StatusConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class TaskRequest extends FormRequest
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
            'status' => ['required', 'string', Rule::in([StatusConstant::PENDING, StatusConstant::COMPLETED, StatusConstant::IN_PROGRESS])]
        ];
    }

    protected function prepareForValidation(): void
    {
        // Ensure the route parameters are available before validation
        $this->merge([
            'project' => $this->route('project'),
            'task' => $this->route('task'),
        ]);
    }
}
