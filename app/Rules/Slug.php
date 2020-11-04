<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    protected $message;


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->hasUnderscores($value)) {
            $this->message = trans('validation.no_underscores');
            return false;
        }

        if ($this->startsWithDashes($value)) {
            $this->message = trans('validation.no_starting_dashes');
            return false;
        }

        if ($this->endsWithDashes($value)) {
            $this->message = trans('validation.no_ending_dashes');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    protected function hasUnderscores($value)
    {
        return preg_match('/_/', $value);
    }

    protected function startsWithDashes($value)
    {
        return preg_match('/^-/', $value);
    }

    protected function endsWithDashes($value)
    {
        return preg_match('/-$/', $value);
    }
}
