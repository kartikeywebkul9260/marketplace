<?php

namespace Webkul\Marketplace\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;

class Configuration
{
    /**
     * After marketplace configuration update
     */
    public function afterUpdate()
    {
        if (request()->get('marketplace')) {
            ResponseCache::forget('marketplace');
        }
    }
}
