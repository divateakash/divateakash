<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CachedRelationships
{
    public function cachedRelation($relation, $time = null)
    {
        $time = $time ?? now()->addMinutes(10);

        $relationForeignKey = $this->{$relation}()->getForeignKeyName();
        $relationId = $this->{$relationForeignKey};

        $key = "{$relation}.{$relationId}";

        return Cache::remember($key, $time, fn() => $this->{$relation});
    }
}
