<?php

namespace AppBundle\Strategy;

use AppBundle\Strategy\ThumbnailStrategyInterface;

/**
 * ThumbnailStrategyYoutube
 */
class ThumbnailStrategyYoutube implements ThumbnailStrategyInterface
{
    /**
     * {inheritDoc}
     */
    public function getThumbnail($videoId)
    {
        return 'https://img.youtube.com/vi/' . $videoId . '/0.jpg';
    }
}