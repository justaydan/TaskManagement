<?php

namespace App\Http\Requests;

use App\Dto\TaskDto;
use App\Rules\CheckProjectBelongsToUser;
use App\Rules\CheckTaskBelongsToProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class CreateOrUpdateTaskRequest extends FormRequest
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
            'project_id' => ['required', 'string', 'exists:projects,id', new CheckProjectBelongsToUser()],
            'task_id' => ['nullable', new RequiredIf($this->isMethod('PUT')), 'exists:tasks,id', new CheckTaskBelongsToProject($this->project_id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'project_id' => $this->route('project'),
            'task_id' => $this->route('task'),
        ]);
    }

    public function toDto()
    {
        return new TaskDto($this);
    }
}
