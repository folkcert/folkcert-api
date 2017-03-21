<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\RestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RestBundle\Normalizers\RestNormalizer;
use AppBundle\Entity\Genre;

class GenreController extends RestController
{
	public function handleGet($id)
    {
        $result = null;
        $response = null;

        if (empty($id)) {
            $result = $this->_entityManager->getRepository('AppBundle:Genre')->findAll();
        } else {
            $result = $this->_entityManager->getRepository('AppBundle:Genre')->find($id);
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

        $genre = new Genre();

        $genre->exchangeArray($jsonContent);

        /* Will throw an exception if not valid */
        $this->_validateEntity($genre);

        $this->_entityManager->persist($genre);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($genre));
        return $response;
    }

    public function handleDelete($id)
    {
        $genre = $this->_entityManager->getRepository('AppBundle:Genre')->find($id);

        /* Will throw an exception if not valid */
        $this->_validateEntity($genre);

        $this->_entityManager->remove($genre);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($genre));

        return $response;
    }
}
