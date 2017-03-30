<?php

namespace AppBundle\Strategy;

use AppBundle\Strategy\ThumbnailStrategyInterface;

/**
 * ThumbnailStrategyVimeo
 */
class ThumbnailStrategyVimeo implements ThumbnailStrategyInterface
{
    /**
     * {inheritDoc}
     */
    public function getThumbnail($videoId)
    {
        $apiUrl = 'https://vimeo.com/api/v2/video/' . $videoId . '.json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        $data = json_decode($response);

        return $data[0]->thumbnail_large;
    }
}
