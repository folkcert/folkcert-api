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
        $result = null;
        $response = null;

        if (empty($id)) {
            $result = $this->_entityManager->getRepository('AppBundle:Concert')->findAll();
        } else {
            $result = $this->_entityManager->getRepository('AppBundle:Concert')->find($id);
        }

        if (!empty($result)) {
            $response = new JsonResponse($this->_serializeObject($result));
        } else {
            /* Throw new exception */
        }

        return $response;
    }
}
