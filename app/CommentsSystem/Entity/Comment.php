<?php

namespace App\CommentsSystem\Entity;

use Mchekin\MVCBoiler\Entity\EntityInterface;

class Comment implements EntityInterface
{
    /**
     * All comment fields
     * @var array $fields
     */
    protected $fields = [
        'id' => null,
        'parent_id' => null,
        'email' => '',
        'name' => '',
        'message' => '',
        'created_at' => null,
    ];

    /**
     * Keys used for creating new database record
     * @var array $insertFields
     */
    protected $insertFields = [
        'parent_id',
        'email',
        'name',
        'message',
    ];

    /**
     * Password constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        // filtering out irrelevant or illegal keys
        $data = array_intersect_key($data, $this->fields);

        // making sure all keys are present, having at least their default value
        $this->fields = array_merge($this->fields, $data);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->fields['id'];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getField($name)
    {
        if (!isset($this->fields[$name])) {
            throw new \InvalidArgumentException('No field named '.$name);
        }

        return $this->fields[$name];
    }

    /**
     * @return mixed
     */
    public function parentId()
    {
        return $this->getField('parent_id');
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->getField('email');
    }

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->getField('name');
    }

    /**
     * @return mixed
     */
    public function message()
    {
        return $this->getField('message');
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Return only fields used for saving to database
     *
     * @return array
     */
    public function getInsertFields()
    {
        return array_intersect_key($this->fields, array_flip($this->insertFields));
    }
}
