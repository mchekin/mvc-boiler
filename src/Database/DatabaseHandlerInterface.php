<?php

namespace Mchekin\MVCBoiler\Database;

interface DatabaseHandlerInterface
{
    /**
     * @param $collection
     * @return bool
     */
    public function deleteCollection($collection);

    /**
     * @param $collection
     * @return bool
     */
    public function collectionExists($collection);

    /**
     * @param $query
     * @return bool
     */
    public function rawQuery($query);

    /**
     * @param $collection
     * @return DatabaseHandlerInterface
     */
    public function records($collection);

    /**
     * @param array $fields
     * @return DatabaseHandlerInterface
     */
    public function where(array $fields);

    /**
     * @param $orderBy
     * @param string $order
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch($orderBy, $order = 'DESC', $offset = 0, $limit = 200);

    /**
     * @return DatabaseHandlerInterface
     */
    public function execute();

    /**
     * @param $collection
     * @param array $fields
     * @return bool
     */
    public function createRecord($collection, array $fields);
}
