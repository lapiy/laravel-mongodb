<?php

namespace Jenssegers\Mongodb\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MongoDB\BSON\ObjectID;
use MongoException;

class ObjectIDCaster implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Jenssegers\Mongodb\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, $key, $value, $attributes)
    {
        return (string) $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return string
     * @throws MongoException
     */
    public function set($model, $key, $value, $attributes)
    {
        switch ($value){

            case is_string($value):
                return new ObjectID($value);

            case $value instanceof ObjectID:
                return new ObjectID((string) $value);

            default:
                throw new MongoException('Invalid object ID passed', 19);

        }
    }
}
