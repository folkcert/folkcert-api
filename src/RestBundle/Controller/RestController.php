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
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $_request;

    public function indexAction(Request $request, $id)
    {
        $this->_entityManager = $this->getDoctrine()->getManager();
        $this->_serializer = $this->_createSerializer();
        $this->_entityValidator = $this->get('validator');
        $this->_request = $request;

        /* Current Route */
        $route = $this->_request->get('_route');

        /* Current Method */
        $method = $this->_request->getMethod();

        /* Current User */
        $userToken = $this->_request->headers->get('x-user-token');
        $userToken = 'notTheRealOne';

        $response = null;
        try {

            /* Will throw an exception if not authorized */
            $this->_validateCredentials($userToken, $route, $method);

            switch ($method) {
                case Request::METHOD_GET:
                    $response = $this->handleGet($id);
                break;
                   
                case Request::METHOD_POST:
                    $response = $this->handlePost();
                break;

                case Request::METHOD_PUT:
                    $response = $this->handlePut();
                break;

                case Request::METHOD_DELETE:
                    $response = $this->handleDelete($id);
                break;

                case Request::METHOD_OPTIONS:
                    $response = new Response('', 200);
                break;

                default:
                    # code...
                break;
            }
        } catch (BadRequestHttpException $e) {
            $response = new JsonResponse(
                json_decode($e->getMessage()),
                Response::HTTP_BAD_REQUEST
            );
        } catch (NotFoundHttpException $e) {
            $response = new JsonResponse(
                json_decode($e->getMessage()),
                Response::HTTP_NOT_FOUND
            );
        } catch (UnauthorizedHttpException $e) {
            $response = new JsonResponse(
                json_decode($e->getMessage()),
                Response::HTTP_UNAUTHORIZED
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

    /**
     * Validates if the user has access to the resource
     *
     * @param string $userToken
     * @param string $resource
     * @param string $method
     *
     * @return boolean
     * @throws UnauthorizedHttpException
     */
    protected function _validateCredentials($userToken, $resource, $method)
    {
        $canAccessResource = $this->get('credentials_service')->canAccessResource(
            $userToken,
            $resource,
            $method
        );

        if ($canAccessResource === false) {
            throw new UnauthorizedHttpException(
                '',
                $this->render(
                    'RestBundle:Rest:unauthorized.html.twig'
                )->getContent()
            );
        }

        return $canAccessResource;
    }
}
