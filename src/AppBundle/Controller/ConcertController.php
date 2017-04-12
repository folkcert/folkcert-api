<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use RestBundle\Controller\RestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use RestBundle\Normalizers\RestNormalizer;
use AppBundle\Entity\Concert;

class ConcertController extends RestController
{
    public function handleGet($id)
    {
        $result = null;
        $response = null;

        $filters = $this->_request->query->get('filters');

        if (empty($id)) {
            $result = $this->_entityManager->getRepository('AppBundle:Concert')->getAll($filters);
        } else {
            $result = $this->_entityManager->getRepository('AppBundle:Concert')->find($id);
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

        $concert = new Concert();

        $this->_populateConcertFields($concert, $jsonContent);

        $this->_entityManager->persist($concert);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($concert));
        return $response;
    }

    public function handlePut()
    {
        $jsonContent = json_decode($this->_request->getContent(), true);

        $concert = $this->_entityManager->getRepository('AppBundle:Concert')->find($jsonContent['id']);

        $this->_populateConcertFields($concert, $jsonContent);

        $this->_entityManager->persist($concert);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($concert));
        return $response;
    }

    public function handleDelete($id)
    {
        $concert = $this->_entityManager->getRepository('AppBundle:Concert')->find($id);

        /* Will throw an exception if not valid */
        $this->_validateEntity($concert);

        $this->_entityManager->remove($concert);
        $this->_entityManager->flush();

        $response = new JsonResponse($this->_serializeObject($concert));

        return $response;
    }

    /**
     * Populates a concert Entity with the jsonContent and its fields
     * @param Concert $concert
     * @param array $jsonContent
     * @return Concert
     */
    private function _populateConcertFields(Concert $concert, $jsonContent)
    {
        $linkThumbnailService = $this->get('link_thumbnail_service');
        $concert->exchangeArray($jsonContent);

        /* Will throw an exception if not valid */
        $this->_validateEntity($concert);

        /* Concert Genre */
        $concert->setGenre(
            $this->_entityManager->getReference('AppBundle:Genre', $concert->getGenre()->getId())
        );

        /* Concert Artist */
        $concert->setArtist(
            $this->_entityManager->getReference('AppBundle:Artist', $concert->getArtist()->getId())
        );

        /* Links */
        foreach ($concert->getLinks() as $key => $link) {
            $link->setConcert($concert);
            $link->setLinkType(
                $this->_entityManager->getReference('AppBundle:LinkType', $link->getLinkType()->getId())
            );

            /* Set link Thumbnail */
            $linkThumnbail = $linkThumbnailService->getThumbnail($link->getLinkType()->getId(), $link->getLinkCode());
            $link->setThumbnail($linkThumnbail);
        }

        return $concert;
    }
}
