<?php


namespace App\Mapper;


use App\DTO\UserData;
use App\Entity\UserEntity;
use Exception;

class UserMapper
{
    /**
     * @param UserData|UserEntity $object
     * @param                     $class
     *
     * @return UserData|UserEntity
     * @throws Exception
     */
    public function map($object, string $class)
    {
        if (!$this->isValidClass($class) || !$this->isValidObject($object)) {
            throw new Exception('Unknown class');
        }
        /** @var UserData|UserEntity $user */
        $user = new $class();
        $user->setId($object->getId())
            ->setFirstName($object->getFirstName())
            ->setLastName($object->getLastName())
            ->setPassword($object->getPassword())
            ->setUsername($object->getUsername());

        return $user;
    }

    public function isValidClass(string $class)
    {
        return in_array($class, [UserEntity::class, UserData::class]);
    }

    public function isValidObject(object $object)
    {
        return $this->isValidClass(get_class($object));
    }
}
