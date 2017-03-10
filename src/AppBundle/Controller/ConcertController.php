<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\RestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use RestBundle\Normalizers\RestNormalizer;

class ConcertController extends RestController
{
    public function handleGet($id)
    {
        die($id);
        $concerts = $this->_entityManager->getRepository('AppBundle:Concert')->findAll();
        $response = new JsonResponse($this->_serializeObject($concerts));
        
        return $response;
    }
}
