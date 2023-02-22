<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\PlanningRepository;
use App\Entity\Facility;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $weekday = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $hourtime = null;

    #[ManyToOne(targetEntity: Facility::class, inversedBy: 'plannings')]
    #[JoinColumn(name: 'facility_id', referencedColumnName: 'id', onDelete:"CASCADE")]
    private ?Facility $facility = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekday(): ?\DateTimeImmutable
    {
        return $this->weekday;
    }

    public function setWeekday(\DateTimeImmutable $weekday): self
    {
        $this->weekday = $weekday;

        return $this;
    }

    public function getHourtime(): ?\DateTimeImmutable
    {
        return $this->hourtime;
    }

    public function setHourtime(\DateTimeImmutable $hourtime): self
    {
        $this->hourtime = $hourtime;

        return $this;
    }

    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    public function setFacility(?Facility $facility): self
    {
        $this->facility = $facility;

        return $this;
    }
}