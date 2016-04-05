<?php

namespace App\CommentsSystem\Filters;

use Mchekin\MVCBoiler\Filters\Filter;

class NameFilter extends Filter
{
    protected $min = 2;
    protected $max = 255;

    /**
     * @param string $value
     * @return bool
     */
    public function validate($value)
    {
        $this->message = 'Text must be between '.$this->min.' to '.$this->max.' characters long';
        return !!preg_match("/^.{".$this->min.",".$this->max."}$/", $value);
    }

    /**
     * @param string $value
     * @return string
     */
    public function sanitize($value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }
}
