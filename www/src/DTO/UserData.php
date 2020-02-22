<?php

namespace App\DTO;

use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

class UserData implements JsonSerializable
{
    /**
     * @Assert\Blank(message="id must be empty.",groups={"registration"})
     * @Assert\NotBlank(message="id is required.", groups={"update"})
     */
    private $id;

    /**
     * @Assert\NotBlank(message="This field is required.", groups={"registration", "update"})
     */
    private $username;

    /**
     * @Assert\Length(min="8", minMessage="Password must be 8 symbols or more", groups={"registration"})
     * @Assert\Length(min="8", minMessage="Password must be 8 symbols or more", groups={"update"}, allowEmptyString=true)
     */
    private $password;

    /**
     * @Assert\NotBlank(message="This field is required.", groups={"registration", "update"})
     * @Assert\Length(max="50", groups={"registration", "update"})
     */
    private $firstName;

    /**
     * @Assert\NotBlank(message="This field is required.", groups={"registration", "update"})
     * @Assert\Length(max="50", groups={"registration", "update"})
     */
    private $lastName;

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'username'  => $this->getUsername(),
            'firstName' => $this->getFirstName(),
            'lastName'  => $this->getLastName(),
        ];
    }
}
