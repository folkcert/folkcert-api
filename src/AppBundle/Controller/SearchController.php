<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\RestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use RestBundle\Normalizers\RestNormalizer;

class SearchController extends RestController
{
    public function handleGet()
    {
        $filters = $this->_request->query->get('filters');
        $order = $this->_request->query->get('order');

        $keyword = $filters['keyword'];

        $concerts = $this->get('search_scores')->search('AppBundle:Concert', $keyword);

        $artists = $this->get('search_scores')->search('AppBundle:Artist', $keyword);

        $result = array(
            'concerts' => array(
                'count' => sizeof($concerts),
                'data' => $concerts
            ),
            'artists' => array(
                'count' => sizeof($artists),
                'data' => $artists
            )
        );

        $response = new JsonResponse($this->_serializeObject($result));

        return $response;
    }
}
