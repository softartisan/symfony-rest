<?php

namespace App\Validators\Persona;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePersonaValidator
{
    /**
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @Assert\NotBlank
     */
    private $age;

    public function __construct(array $requestContent)
    {
        $this->age = $requestContent['age'];
        $this->email = $requestContent['email'];
        $this->name = $requestContent['name'];
    }
}