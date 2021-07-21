<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class BelongsToTeam implements Rule
{
    public $resource;

    public $team;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($resource, $team)
    {
        $this->resource = $resource;
        $this->team = $team;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return optional($this->resource::find($value))->team_id === $this->team->id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The {$this->resourceName()} does not belong to your team.";
    }

    public function resourceName()
    {
        return Str::studly(class_basename($this->resource));
    }
}
