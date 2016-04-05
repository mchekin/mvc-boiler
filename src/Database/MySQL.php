<?php

namespace Mchekin\MVCBoiler\Database;

use Mchekin\MVCBoiler\Entity\EntityInterface;
use PDO;

class MySQL implements DatabaseHandlerInterface
{
    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var string $query
     */
    protected $query = '';

    /**
     * @var array $values
     */
    protected $values =[];

    /**
     * @var PDO $pdo
     */
    protected $pdo;

    public function __construct(array $config)
    {
        $this->config = array_merge([
            'host' => 'localhost',
            'port' => '3306',
            'database' => '',
            'username' => '',
            'password' => '',
        ], $config);
    }

    /**
     * @param string $collection
     * @return array
     */
    public function records($collection)
    {
        // check if collection exists
        if(!$this->collectionExists($collection)) {
            throw new \PDOException('Collection does not exits');
        }

        // staring the SELECT query
        $this->query = "SELECT * FROM $collection ";
        $this->values = [];

        return $this;
    }

    /**
     * @param $fields
     * @return array
     */
    public function where(array $fields)
    {
        $this->query .= "WHERE";
        $this->values = [];

        // build WHERE key = value pairs
        foreach ($fields as $name => $value) {

            // if the value is null there is a different SQL syntax for querying it
            if (is_null($value)) {
                $this->query .= ' '.$name.' IS NULL AND ';
                continue;
            }

            // for using different operator than = , e.g.: <, >, <> and etc.
            if (is_array($value) && isset($value['operator']) && isset($value['value'])) {
                $this->query .= ' '.$name.' '.$value['operator'].' :'.$name.' AND '; // the :$name part is the placeholder
                $this->values[':'.$name] = $value['value']; // the value for the placeholder
                continue;
            }

            $this->query .= ' '.$name.' = :'.$name.' AND '; // the :$name part is the placeholder
            $this->values[':'.$name] = $value; // the value for the placeholder
        }

        // remove the last AND or and empty WHERE ( when the fields array is empty )
        $this->query = substr($this->query, 0, -5);

        return $this;
    }

    /**
     * @param $orderBy
     * @param string $order
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch($orderBy, $order = 'DESC', $offset = 0, $limit = 200)
    {
        // adding the ORDER BY clause
        $this->query .= " ORDER BY $orderBy $order";

        // adding the paging LIMIT and OFFSET clause
        $this->query .= " LIMIT :limit OFFSET :offset";

        // adding the values for OFFSET and LIMIT
        $this->values[':offset'] = $offset;
        $this->values[':limit'] = $limit;

        // preparing the statement from the constructed query
        $statement = $this->pdo()->prepare($this->query);

        // executing the statement
        $statement->execute($this->values);

        // returning the results as an associative array
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $statement = $this->pdo()->prepare($this->query);
        return $statement->execute($this->values);
    }

    /**
     * @param $collection
     * @return bool
     */
    public function collectionExists($collection)
    {
        $statement = $this->pdo()->prepare('SHOW TABLES LIKE \''.$collection.'\'');

        $statement->bindParam(':table', $collection, PDO::PARAM_STR);
        $statement->execute();

        if($statement->rowCount()>0) {
            return true;
        }

        return false;
    }

    /**
     * @param $collection
     * @return bool
     */
    public function deleteCollection($collection)
    {
        $statement = $this->pdo()->prepare('DROP TABLE '.$collection);

        $statement->bindParam(':table', $collection, PDO::PARAM_STR);
        return $statement->execute();
    }

    public function createRecord($collection, array $fields)
    {
        // check if collection exists
        if(!$this->collectionExists($collection)) {
            throw new \PDOException('Collection does not exits');
        }

        $values = [];
        $keys = array_keys($fields);

        // build the INSERT query
        $query = 'INSERT INTO '.$collection.' ('
            .implode(', ', $keys)
            .') VALUES ( '
            .':'.implode(', :', $keys).' )';

        foreach ($fields as $name => $value) {
            $values[':'.$name] = $value; // the value for the placeholder
        }

        $statement = $this->pdo()->prepare($query);
        return  $statement->execute($values);
    }

    /**
     * @param $collection
     * @param EntityInterface $entity
     * @param array $fields
     * @return bool
     */
    public function updateRecord($collection, EntityInterface $entity, array $fields)
    {
        // check if collection exists
        if(!$this->collectionExists($collection)) {
            throw new \PDOException('Collection does not exits');
        }

        // build the update query
        $query = 'UPDATE '.$collection.' SET';
        $values = [];
        foreach ($fields as $name) {
            $query .= ' '.$name.' = :'.$name.','; // the :$name part is the placeholder, e.g. :valid
            $values[':'.$name] = $entity->getField($name); // the value for the placeholder
        }
        // replace the last , with
        $query = substr($query, 0, -1);

        $query .= ' WHERE id = '.$entity->getId(). ';';

        $statement = $this->pdo()->prepare($query);

        return $statement->execute($values);
    }

    /**
     * @param $query
     * @return bool
     */
    public function rawQuery($query)
    {
        $statement = $this->pdo()->prepare($query);

        return $statement->execute();
    }

    /**
     * @return PDO
     */
    protected function pdo()
    {
        if (is_a($this->pdo, PDO::class)) {
            return $this->pdo;
        }

        $dsn = 'mysql:dbname='.$this->config['database']
            . ';host='.$this->config['host']
            .';port='.$this->config['port'];

        $this->pdo =  new PDO($dsn, $this->config['username'], $this->config['password']);
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $this->pdo;
    }
}
