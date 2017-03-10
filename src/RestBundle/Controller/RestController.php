<?php
namespace RestBundle\Controller;

use RestBundle\Entity\RestEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use \RestBundle\Normalizers\RestNormalizer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RestController extends Controller
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_entityManager;

    /**
     * @var Symfony\Component\Serializer\Serializer
     */
    protected $_serializer;

    /**
     * @var Symfony\Component\Validator\Validator\Validator
     */
    protected $_entityValidator;

    public function indexAction(Request $request, $id)
    {
        $this->_entityManager = $this->getDoctrine()->getManager();
        $this->_serializer = $this->_createSerializer();
        $this->_entityValidator = $this->get('validator');

        $response = null;
        try {
            switch ($request->getMethod()) {
                case 'GET':
                    $response = $this->handleGet($id);
                break;
                   
                case 'POST':
                    $response = $this->handlePost($request);
                break;

                case 'PUT':
                    $response = $this->handlePut($request);
                break;

                case 'DELETE':
                    $response = $this->handleDelete($request);
                break;

                default:
                    # code...
                break;
            }
        } catch(BadRequestHttpException $e) {
            $response = new JsonResponse(
                json_decode($e->getMessage()),
                Response::HTTP_BAD_REQUEST
            );
        } catch(NotFoundHttpException $e) {
            $response = new JsonResponse(
                json_decode($e->getMessage()),
                Response::HTTP_NOT_FOUND
            );
        }

        return $response;
    }

    /**
     * Initializes the serializer Object an returns it
     *
     * @return Symfony\Component\Serializer\Serializer
     */
    protected function _createSerializer()
    {
        $encoders = array(new JsonEncoder());

        $normalizer = new RestNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $normalizers = array($normalizer);

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer;
    }

    /**
     * Serializes an object to JSON
     *
     * @param stdClass|array $object
     *
     * @return array
     */
    protected function _serializeObject($object) 
    {
        $jsonContent = $this->_serializer->serialize($object, 'json');

        return json_decode($jsonContent);
    }

    /**
     * Validates an entity and throws a Bad Request
     * exception in case an error is found
     *
     * @param RestEntity $entity
     *
     * @return array
     * @throws NotFoundHttpException
     */
    protected function _validateEntity($entity)
    {
        if ($entity instanceof RestEntity) {
            $errors = $this->_entityValidator->validate($entity);
        
            if (count($errors) > 0) {
                $it = $errors->getIterator();
                throw new BadRequestHttpException(
                    $this->render(
                        'RestBundle:Rest:bad-request.html.twig',
                        array('errors' => iterator_to_array($it))
                    )->getContent()
                );
            }
        } else {
            throw new NotFoundHttpException(
                $this->render(
                    'RestBundle:Rest:not-found.html.twig'
                )->getContent()
            );
        }

        return $errors;
    }
}
