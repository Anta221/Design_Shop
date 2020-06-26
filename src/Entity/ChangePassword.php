<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;




class ChangePassword
{
    
    private $id;

    /**
     * @Assert\NotBlank
     */
    private $ancienPassword;

    /**
     * @Assert\NotBlank
     */
    private $nouveauPassword;


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAncienPassword(): ?string
    {
        return $this->ancienPassword;
    }

    public function setAncienPassword(string $ancienPassword): self
    {
        $this->ancienPassword = $ancienPassword;

        return $this;
    }

    public function getNouveauPassword(): ?string
    {
        return $this->nouveauPassword;
    }

    public function setNouveauPassword(string $nouveauPassword): self
    {
        $this->nouveauPassword = $nouveauPassword;

        return $this;
    }
}
