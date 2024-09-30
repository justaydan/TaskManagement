<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckTaskBelongsToProject implements ValidationRule
{
    public function __construct(private int $projectId)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Task::query()->find($value)->project_id !== $this->projectId) {
            $fail('Task does not belong to this project.');
        }
    }
}
