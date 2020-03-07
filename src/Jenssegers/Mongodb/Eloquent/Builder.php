<?php

namespace Jenssegers\Mongodb\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Jenssegers\Mongodb\Helpers\QueriesRelationships;
use MongoDB\Driver\Cursor;
use MongoDB\Model\BSONDocument;

class Builder extends EloquentBuilder
{
    use QueriesRelationships;

    /**
     * The methods that should be returned from query builder.
     * @var array
     */
    protected $passthru = [
        'toSql',
        'insert',
        'insertGetId',
        'pluck',
        'count',
        'min',
        'max',
        'avg',
        'sum',
        'exists',
        'push',
        'pull',
    ];

    /**
     * {@inheritdoc}
     */
    public function update(array $values, array $options = [])
    {
        return $this->toBase()->update($this->addUpdatedAtColumn($values), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function chunkById($count, callable $callback, $column = '_id', $alias = null)
    {
        return parent::chunkById($count, $callback, $column, $alias);
    }

    /**
     * {@inheritdoc}
     */
    public function raw($expression = null)
    {
        // Get raw results from the query builder.
        $results = $this->query->raw($expression);

        // Convert MongoCursor results to a collection of models.
        if ($results instanceof Cursor) {
            $results = iterator_to_array($results, false);

            return $this->model->hydrate($results);
        } // Convert Mongo BSONDocument to a single object.
        elseif ($results instanceof BSONDocument) {
            $results = $results->getArrayCopy();

            return $this->model->newFromBuilder((array) $results);
        } // The result is a single object.
        elseif (is_array($results) && array_key_exists('_id', $results)) {
            return $this->model->newFromBuilder((array) $results);
        }

        return $results;
    }

    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->query->getConnection();
    }
}
