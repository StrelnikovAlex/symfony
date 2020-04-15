<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\tickets", inversedBy="tags")
     */
    private $ticket_id;

    public function __construct()
    {
        $this->ticket_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|tickets[]
     */
    public function getTicketId(): Collection
    {
        return $this->ticket_id;
    }

    public function addTicketId(tickets $ticketId): self
    {
        if (!$this->ticket_id->contains($ticketId)) {
            $this->ticket_id[] = $ticketId;
        }

        return $this;
    }

    public function removeTicketId(tickets $ticketId): self
    {
        if ($this->ticket_id->contains($ticketId)) {
            $this->ticket_id->removeElement($ticketId);
        }

        return $this;
    }
}
