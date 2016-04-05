<?php

namespace Mchekin\MVCBoiler\Entity;

interface EntityInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param $name
     * @return mixed
     */
    public function getField($name);

    /**
     * @return array
     */
    public function getFields();

    /**
     * @return array
     */
    public function getInsertFields();
}
