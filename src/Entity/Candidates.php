<?php

namespace App\Entity;

use App\Repository\CandidatesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hcnx\DatatableProcessingBundle\Traits\DataTableServerSideParamDataTrait;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CandidatesRepository::class)
 */
class Candidates
{
    use DataTableServerSideParamDataTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"dtCandidate"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"dtCandidate"})

     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"dtCandidate"})
     */
    private $lastname;

    /**
     * @Groups({"candidate_tags_read"})

     * @ORM\OneToMany(targetEntity=Tags::class, mappedBy="candidates")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    // TODO doc
    public function getDTRowId()
    {
        return "1";
    }

    public function getDTrowClass()
    {
        return "css";
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setCandidates($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getCandidates() === $this) {
                $tag->setCandidates(null);
            }
        }

        return $this;
    }


}
