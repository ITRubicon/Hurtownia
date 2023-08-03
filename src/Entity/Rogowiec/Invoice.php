<?php

namespace App\Entity\Rogowiec;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[Table(name: 'rogowiec_invoice')]
#[Index(name: "source_idx", fields: ["source"])]
class Invoice
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $source = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $docDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $saleDate = null;

    #[ORM\Column(type: 'string', length: 30)]
    private ?string $currency = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $netValue = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $grossValue = null;
}