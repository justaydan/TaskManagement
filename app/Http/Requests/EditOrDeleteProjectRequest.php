<?php

namespace App\Http\Requests;

use App\Rules\CheckProjectBelongsToUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EditOrDeleteProjectRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:projects,id', new CheckProjectBelongsToUser()],
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('project')]);
    }
}
