<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUUID
{
    protected static function bootHasUUID(): void
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Overriding default incrementing settings
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Overriding default key type
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}