<?php

namespace Jenssegers\Mongodb\Casts;

use DateTime;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Date;
use MongoDB\BSON\UTCDateTime;
use MongoException;

class UTCDateTimeCaster implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        // Convert UTCDateTime instances.
        if ($value instanceof UTCDateTime) {
            return Date::createFromTimestampMs($value->toDateTime()->format('Uv'));
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return UTCDateTime
     * @throws MongoException
     */
    public function set($model, $key, $value, $attributes)
    {
        switch ($value){

            case $value instanceof DateTime:
                return new UTCDateTime($value);

            case $value instanceof UTCDateTime:
                return $value;

            default:
                throw new MongoException('Invalid DateTime or UTCDateTime passed', 19);

        }
    }

}
