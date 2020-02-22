<?php


namespace App\Service;


use App\DTO\UserData;
use App\Entity\UserEntity;
use App\Exception\InvalidDataException;
use App\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    private $entityManager;
    private $userMapper;
    private $formService;

    public function __construct(EntityManagerInterface $entityManager, UserMapper $mapper, FormService $formService)
    {
        $this->entityManager = $entityManager;
        $this->userMapper = $mapper;
        $this->formService = $formService;
    }

    private function getUserRepository()
    {
        return $this->entityManager->getRepository(UserEntity::class);
    }

    /**
     * @param UserData $userData
     *
     * @return UserData
     * @throws Exception
     */
    public function createUser(UserData $userData)
    {
        if ($this->getUserRepository()->findOneBy(['username' => $userData->getUsername()])) {
            throw new Exception('The username must be unique.', Response::HTTP_BAD_REQUEST);
        }
        $user = $this->userMapper->map($userData, UserEntity::class);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->userMapper->map($user, UserData::class);
    }


    /**
     * @param $id
     *
     * @param bool $returnEntity
     * @return UserData|UserEntity
     * @throws Exception
     */
    public function getUser($id, $returnEntity = false)
    {
        if (!$id) {
            throw new Exception('Invalid ID', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        /** @var UserEntity $user */
        $user = $this->getUserRepository()->find($id);
        if (!$user) {
            throw new Exception('User not found', Response::HTTP_NOT_FOUND);
        }

        return $returnEntity ? $user : $this->toUserData($user);
    }

    /**
     * @return UserData[]
     */
    public function getAllUsers()
    {
        return array_map([$this, 'toUserData'], $this->getUserRepository()->findAll());
    }

    /**
     * @param UserEntity $user
     * @param UserData $userData
     * @return UserData
     * @throws Exception
     */
    public function updateUser(UserEntity $user, UserData $userData)
    {
        if (
            $userData->getUsername() !== $user->getUsername() &&
            $this->getUserRepository()->findOneBy(['username' => $userData->getUsername()])
        ) {
            throw new Exception('The username must be unique.', Response::HTTP_BAD_REQUEST);
        }
        if ($userData->getPassword()) {
            $user->setPassword($userData->getPassword());
        }
        $user->setFirstName($userData->getFirstName())
            ->setLastName($userData->getLastName())
            ->setUsername($userData->getUsername());
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->toUserData($user);
    }

    /**
     * @param $id
     * @return UserData
     * @throws Exception
     */
    public function removeUser($id)
    {
        $user = $this->getUser($id, true);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->toUserData($user);
    }

    /**
     * @param $user
     * @return UserData
     * @throws Exception
     */
    public function toUserData($user)
    {
        return $this->userMapper->map($user, UserData::class);
    }


    /**
     * @param $user
     * @return UserEntity
     * @throws Exception
     */
    public function toUserEntity($user)
    {
        return $this->userMapper->map($user, UserEntity::class);
    }

    /**
     * @param Request $request
     * @param $validationsGroup
     * @param UserData $userData
     * @return UserData
     * @throws InvalidDataException
     */
    public function getUserFromRequest(Request $request, $validationsGroup, UserData $userData = null)
    {
        $userData = $userData ?? new UserData();
        $form = $this->formService->createUserForm($userData, $request, ['validation_groups' => [$validationsGroup]]);
        if (!($form->isSubmitted() && $form->isValid())) {
            throw new InvalidDataException($this->formService->getFormErrors($form), Response::HTTP_BAD_REQUEST);
        }
        return $userData;
    }

}
