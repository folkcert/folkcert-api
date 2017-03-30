<?php

namespace AppBundle\Services;

use AppBundle\Entity\LinkType;
use AppBundle\Strategy\ThumbnailStrategyYoutube;
use AppBundle\Strategy\ThumbnailStrategyVimeo;
use AppBundle\Strategy\ThumbnailStrategyDailyMotion;

/**
 * LinkThumbnailService
 */
class LinkThumbnailService
{
    public function __construct()
    {

    }

    public function getThumbnail($linkType, $videoId)
    {
        $thumbnailStrategy = null;
        switch ($linkType) {
            case LinkType::LINK_TYPE_YOUTUBE:
                $thumbnailStrategy = new ThumbnailStrategyYoutube();
            break;

            case LinkType::LINK_TYPE_VIMEO:
                $thumbnailStrategy = new ThumbnailStrategyVimeo();
            break;

            case LinkType::LINK_TYPE_DAILYMOTION:
                $thumbnailStrategy = new ThumbnailStrategyDailyMotion();
            break;
        }

        return $thumbnailStrategy->getThumbnail($videoId);
    }
}