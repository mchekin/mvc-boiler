<?php

namespace App\CommentsSystem\Filters;

use Mchekin\MVCBoiler\Filters\Filter;

class EmailFilter extends Filter
{
    protected $message = 'Invalid email value';

    /**
     * @param string $value
     * @return bool
     */
    public function validate($value)
    {
        return !!filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $value
     * @return string
     */
    public function sanitize($value)
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }
}
