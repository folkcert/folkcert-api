<?php

namespace AppBundle\Strategy;

/**
 * ThumbnailStrategyInterface
 */
interface ThumbnailStrategyInterface
{
    /**
     * Returns the thumbnail URL for the video sent via parameter
     *
     * @param $videoId string
     * @return string
     */
    public function getThumbnail($videoId);
}