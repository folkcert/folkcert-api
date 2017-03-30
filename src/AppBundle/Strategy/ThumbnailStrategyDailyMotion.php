<?php

namespace AppBundle\Strategy;

use AppBundle\Strategy\ThumbnailStrategyInterface;

/**
 * ThumbnailStrategyDailyMotion
 */
class ThumbnailStrategyDailyMotion implements ThumbnailStrategyInterface
{
    /**
     * {inheritDoc}
     */
    public function getThumbnail($videoId)
    {
        $apiUrl = 'https://api.dailymotion.com/video/' . $videoId . '?fields=thumbnail_large_url';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        return $data->thumbnail_large_url;
    }
}


