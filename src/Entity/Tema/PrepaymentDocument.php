<?php

namespace App\Entity\Tema;

use App\Repository\WzDocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: WzDocumentRepository::class)]
#[Table(name: 'tema_prepayment_document')]
#[Index(name: "source_issuedate_idx", fields: ["source", "issueDate"])]
class PrepaymentDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $source = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $doc_id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $vin = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $customerId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $netValue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $grossValue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

}