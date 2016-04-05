<?php

namespace App\CommentsSystem\Filters;

use Mchekin\MVCBoiler\Filters\Filter;

class CommentIdFilter extends Filter
{
    protected $min = 1;
    protected $max = 5000;
    /**
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        $this->message = 'Comment id must be an integer';
        return !!filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * @param string $value
     * @return string
     */
    public function sanitize($value)
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
}
