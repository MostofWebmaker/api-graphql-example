<?php

declare(strict_types=1);

namespace App\Model\Advertisement\Command\Advertisement;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteAdvertisementRequestCommand
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
	public ?int $id = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}