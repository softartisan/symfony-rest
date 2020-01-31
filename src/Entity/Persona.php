<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonaRepository")
 */
class Persona
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *      message="name shouldn't be blank.",
     *      groups={"create"}
     *
     * )
     * @Assert\Regex(
     *      "/^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$/",
     *      message="Invalid format for name, format example 'Sebastián Canio'",
     *      groups={"create", "update"}
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="age shouldn't be blank.", groups={"create"})
     * @Assert\GreaterThan(1, groups={"create", "update"})
     * @Assert\LessThan(100, groups={"create", "update"})
     * @Assert\Type("integer", groups={"create", "update"})
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(
     *     message="email shouldn't be blank.",
     *     groups={"create"}
     * )
     * @Assert\Length(
     *     min="10",
     *     minMessage="This value '{{ value }}' is too short. It should have 10 characters or more.",
     *     max="50",
     *     maxMessage="This value '{{ value }}' is too long. It should have 20 characters or less.",
     *     groups={"create", "update"}
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     groups={"create", "update"}
     * )
     * @Assert\Type("string", groups={"create", "update"})
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge($age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function toArray() : array
    {
        return [
            "age" => $this->age,
            "email" => $this->email,
            "name" => $this->name,
            "id" => $this->id
        ];
    }
}
