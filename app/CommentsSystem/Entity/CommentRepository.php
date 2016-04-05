<?php

namespace App\CommentsSystem\Entity;

use Mchekin\MVCBoiler\Database\DatabaseHandlerInterface;

class CommentRepository
{
    /**
     * @var DatabaseHandlerInterface
     */
    protected $db;

    /**
     * @var string
     */
    protected $collection;

    /**
     * PasswordRepository constructor.
     * @param DatabaseHandlerInterface $db
     * @param $collection
     */
    public function __construct(DatabaseHandlerInterface $db, $collection)
    {
        $this->db = $db;
        $this->collection = $collection;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Comment[]
     */
    public function mainComments($limit = 200, $offset = 0)
    {
        return $this->commentsByParentCommentId(null, $limit, $offset);
    }

    /**
     * @param null $parentId
     * @param int $limit
     * @param int $offset
     * @return Comment[]
     */
    public function commentsByParentCommentId($parentId = null, $limit = 200, $offset = 0)
    {
        $comments = [];

        $records = $this->db
            ->records($this->collection)
            ->where(['parent_id' => $parentId])
            ->fetch($orderBy = 'id', $order = 'DESC', $offset, $limit);

        foreach ( $records as $record ) {
            $comments[] = new Comment($record);
        }

        return $comments;
    }

    /**
     * @param array $fields
     * @return Comment
     */
    public function saveComment(array $fields)
    {
        $comment = new Comment($fields);
        return $this->db->createRecord($this->collection, $comment->getInsertFields());
    }

    /**
     * @param array $fields
     * @param string $interval
     * @return bool
     */
    public function isSpam(array $fields, $interval = '-5 hour')
    {
        // initializing the query fields
        $fields = array_merge($fields, [
            'created_at' => [
                'operator' => '>',
                'value' => strtotime($interval),
            ]
        ]);

        // checking if similar records already exist
        $records = $this->db
            ->records($this->collection)
            ->where($fields)
            ->fetch($orderBy = 'id');

        if (count($records) > 0) {
            return true;
        }

        return false;
    }
}
