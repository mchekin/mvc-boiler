<?php

namespace App\CommentsSystem\Filters;

use Mchekin\MVCBoiler\Filters\Filter;

class MessageFilter extends Filter
{
    protected $min = 1;
    protected $max = 5000;
    /**
     * @param $value
     * @return bool
     */
    public function validate($value)
    {
        $this->message = 'Message must be between '.$this->min.' to '.$this->max.' characters long';
        return !!preg_match("/^.{".$this->min.",".$this->max."}$/", $value);
    }

    /**
     * @param string $value
     * @return string
     */
    public function sanitize($value)
    {
        return strip_tags($value, '<a>');
    }
}
