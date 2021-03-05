<?php

namespace LarAPI\Models\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected $uuidFieldName = 'uuid';

    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            $uuidFieldName = $model->getUuidFieldName();
            // Check if the $uuidFieldName property was set in the Model and
            // if it's property value hasn't been set
            if ($uuidFieldName && !isset($model->{$uuidFieldName})) {
                $model->{$uuidFieldName} = (string) Str::uuid(); // Generate the UUID if it's missing
            }
        });

        static::saving(function ($model) {
            $uuidFieldName = $model->getUuidFieldName();
            if (!empty($uuidFieldName)) {
                $originalUuid = $model->getOriginal($uuidFieldName);
                // What's that, trying to change the UUID huh?  Nope, not gonna happen.
                if ($originalUuid && $originalUuid !== $model->{$uuidFieldName}) {
                    $model->{$uuidFieldName} = $originalUuid;
                }
            }
        });
    }

    /**
     * Get the column name for the "uuid" field.
     *
     * @return string|null
     */
    public function getUuidFieldName(): ?string
    {
        return isset($this->uuidFieldName) ? $this->uuidFieldName : null;
    }
}
