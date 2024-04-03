<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use ErrorException;
use FriendFinder\Common\Authorization\IdentityAwareInterface;
use FriendFinder\Common\Strings\ValidateStringTrait;
use FriendFinder\ContactInformation\Enums\ContactInformationRequestAnswerOption;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[Entity()]
#[Table('contact_information_request_answers')]
class ContactInformationRequestAnswer implements IdentityAwareInterface
{
    use ValidateStringTrait;

    #[Id()]
    #[Column(name: 'id', type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(UuidGenerator::class)]
    private ?string $id = null;

    #[Column(name: 'identity', type: 'uuid', unique: false)]
    private string $identity;

    #[Column(name: 'answer', type: Types::STRING, length: 100, enumType: ContactInformationRequestAnswerOption::class)]
    private ContactInformationRequestAnswerOption $answerOption;

    #[OneToOne(targetEntity: ContactInformationRequest::class, inversedBy: 'answer')]
    #[JoinColumn(name: 'request_id', referencedColumnName: 'id')]
    private ?ContactInformationRequest $request = null;

    /**
     * @throws ErrorException when an empty ID is given.
     * @throws ErrorException when an empty identity ID is given.
     */
    public function __construct(
        ?string $id,
        string $identity,
        ContactInformationRequestAnswerOption $answerOption,
        ?ContactInformationRequest $request
    ) {
        if ($id !== null) {
            $this->validateString('ID', $id);
        }

        $this->validateString('Identity', $identity);

        $this->id = $id;
        $this->identity = $identity;
        $this->answerOption = $answerOption;

        if ($request !== null) {
            $this->setContactInformationRequest($request);
        }
    }

    public function getID(): ?string
    {
        return $this->id;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getAnswer(): ContactInformationRequestAnswerOption
    {
        return $this->answerOption;
    }

    public function getContactInformationRequest(): ContactInformationRequest
    {
        return $this->request;
    }

    /**
     * @throws ErrorException when a contact information request has already been set.
     */
    public function setContactInformationRequest(ContactInformationRequest $request): void
    {
        if ($this->request !== null) {
            throw new ErrorException('Answer is already attached to a contact information request');
        }

        $this->request = $request;
    }
}
