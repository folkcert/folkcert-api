<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\RestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RestBundle\Normalizers\RestNormalizer;
use AppBundle\Entity\Artist;

class ArtistController extends RestController
{
	public function handleGet($id)
    {
        $result = null;
        $response = null;

        if (empty($id)) {
            $result = $this->_entityManager->getRepository('AppBundle:Artist')->findAll();
        } else {
            $result = $this->_entityManager->getRepository('AppBundle:Artist')->find($id);
        }

        if (!empty($result)) {
            $response = new JsonResponse($this->_serializeObject($result));
        } else {
            throw new NotFoundHttpException(
                $this->render(
                    'RestBundle:Rest:not-found.html.twig'
                )->getContent()
            );
        }

        return $response;
    }

    public function handlePost()
    {
        $jsonContent = json_decode($this->_request->getContent(), true);

        $artist = new Artist();

        $artist->exchangeArray($jsonContent);

        /* Will throw an exception if not valid */
        $this->_validateEntity($artist);

        $this->_entityManager->persist($artist);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($artist));
        return $response;
    }
}