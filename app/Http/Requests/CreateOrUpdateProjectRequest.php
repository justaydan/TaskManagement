<?php

namespace App\Http\Requests;

use App\Dto\ProjectDto;
use App\Rules\CheckProjectBelongsToUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class CreateOrUpdateProjectRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'id' => ['nullable', new RequiredIf($this->isMethod('PUT')), 'integer', 'exists:projects,id', new CheckProjectBelongsToUser()],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('project')]);
    }

    public function toDto(): ProjectDto
    {
        return new ProjectDto($this);
    }
}
