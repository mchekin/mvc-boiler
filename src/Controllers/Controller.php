<?php

namespace Mchekin\MVCBoiler\Controllers;

use Mchekin\MVCBoiler\Filters\Filter;

abstract class Controller
{

    /**
     * @param Filter[] $filters
     * @param array $payload
     * @param array $errors
     * @return array
     */
    protected function filter(array $filters, array $payload, array &$errors)
    {
        /**
         * @var  string $key
         * @var  Filter $filter
         */
        // iterate over field keys and their respective filters
        foreach ($filters as $key => $filter) {

            // if no value supplied for the key or the value does not pass the filter validation
            if( !isset($payload[$key]) || !$filter->validate($payload[$key]) ) {
                $errors[$key] = $filter->getMessage();
                continue;
            }

            // sanitize the key value
            $payload[$key] = $filter->sanitize($payload[$key]);
        }

        return $payload;
    }
}
