<?php


namespace App\Controller;

use App\Exception\InvalidDataException;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class User
 * @package App\Controller
 * @Route("/api")
 */
class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function index()
    {
        return new JsonResponse($this->userService->getAllUsers());
    }

    /**
     * @Route("/user/{id}", methods={"GET"})
     * @param string $id
     *
     * @return JsonResponse
     */
    public function getSingleUser(string $id)
    {
        $response = null;
        try {
            $response = new JsonResponse($this->userService->getUser($id));
        } catch (Exception $e) {
            $response = new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return $response;
    }

    /**
     * @Route("/user", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createUser(Request $request)
    {
        $response = null;
        try {
            $userData = $this->userService->getUserFromRequest($request, 'registration');
            $response = new JsonResponse($this->userService->createUser($userData));
        } catch (InvalidDataException $exception) {
            $response = new JsonResponse(['error' => $exception->getRawData()], $exception->getCode());
        } catch (Exception $exception) {
            $response = new JsonResponse(['error' => $exception->getMessage()], $exception->getCode());
        }
        return $response;
    }

    /**
     * @Route("/user/{id}", methods={"PUT"})
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function updateUser(Request $request, string $id)
    {
        $response = null;
        try {
            $user = $this->userService->getUser($id, true);
            $userData = $this->userService->getUserFromRequest($request, 'update');
            $response = new JsonResponse($this->userService->updateUser($user, $userData));
        } catch (InvalidDataException $exception) {
            $response = new JsonResponse(['error' => $exception->getRawData()], $exception->getCode());
        } catch (Exception $exception) {
            $response = new JsonResponse(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $response;
    }

    /**
     * @Route("/user/{id}", methods={"DELETE"})
     * @param string $id
     * @return JsonResponse|null
     */
    public function removeUser(string $id)
    {
        $response = null;
        try {
            $response = new JsonResponse($this->userService->removeUser($id));
        } catch (Exception $exception) {
            $response = new JsonResponse(['error' => $exception->getMessage()], $exception->getCode());
        }
        return $response;
    }
}
