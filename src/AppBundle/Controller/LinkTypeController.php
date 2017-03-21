<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\RestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RestBundle\Normalizers\RestNormalizer;
use AppBundle\Entity\LinkType;

class LinkTypeController extends RestController
{
	public function handleGet($id)
    {
        $result = null;
        $response = null;

        if (empty($id)) {
            $result = $this->_entityManager->getRepository('AppBundle:LinkType')->findAll();
        } else {
            $result = $this->_entityManager->getRepository('AppBundle:LinkType')->find($id);
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

        $linkType = new LinkType();

        $linkType->exchangeArray($jsonContent);

        /* Will throw an exception if not valid */
        $this->_validateEntity($linkType);

        $this->_entityManager->persist($linkType);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($linkType));
        return $response;
    }

    public function handleDelete($id)
    {
        $linkType = $this->_entityManager->getRepository('AppBundle:LinkType')->find($id);

        /* Will throw an exception if not valid */
        $this->_validateEntity($linkType);

        $this->_entityManager->remove($linkType);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($linkType));

        return $response;
    }
}
