<?php

namespace App\Http\Requests;

use App\Rules\CheckTaskBelongsToProject;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class DeleteTaskRequest extends FormRequest
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
//        dd($this->project_id);
        return [
            'project_id' => 'required|string|exists:projects,id',
            'task_id' => ['nullable', new RequiredIf(in_array($this->method(), ['DELETE', 'PUT'])), 'exists:tasks,id', new CheckTaskBelongsToProject($this->project_id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'project_id' => $this->route('project'),
            'task_id' => $this->route('task'),
        ]);
    }
}
