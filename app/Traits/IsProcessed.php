<?php

namespace App\Traits;

trait IsProcessed
{
    public function startProcessing($time = 300)
    {
        cache()->put($this->cacheIdentifier(), true, $time);
    }

    public function finishProcessing()
    {
        cache()->forget($this->cacheIdentifier());
    }

    public function beingProcessed()
    {
        return cache()->has($this->cacheIdentifier());
    }

    public function cacheIdentifier()
    {
        return get_class($this) . '_' . $this->id . '_processing';
    }
}
