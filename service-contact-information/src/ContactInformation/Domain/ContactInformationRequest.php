<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use ErrorException;
use FriendFinder\Common\Strings\ValidateStringTrait;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[Entity()]
#[Table('contact_information_requests')]
class ContactInformationRequest
{
    use ValidateStringTrait;

    #[Id()]
    #[Column(name: 'id', type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(UuidGenerator::class)]
    private ?string $id = null;

    #[Column(name: 'sender_profile_id', type: 'uuid', unique: false)]
    private string $senderProfileID;

    #[Column(name: 'receiver_profile_id', type: 'uuid', unique: false)]
    private string $receiverProfileID;

    #[Column(name: 'message', type: Types::TEXT)]
    private string $message;

    /** @var array<string, string> $socials */
    #[Column(name: 'socials', type: Types::JSON)]
    private array $socials = [];

    #[OneToOne(targetEntity: ContactInformationRequestAnswer::class, mappedBy: 'request')]
    private ?ContactInformationRequestAnswer $answer = null;

    /**
     * @param array<string, string> $socials
     *
     * @throws ErrorException when an empty ID is given.
     * @throws ErrorException when an empty profile ID is given.
     * @throws ErrorException when an empty identity ID is given.
     * @throws ErrorException when an empty message is given.
     */
    public function __construct(
        ?string $id,
        string $senderProfileID,
        string $receiverProfileID,
        string $message,
        array $socials,
        ?ContactInformationRequestAnswer $answer
    ) {
        if ($id !== null) {
            $this->validateString('ID', $id);
        }

        $this->validateString('Sender profile ID', $senderProfileID);
        $this->validateString('Receiver profile ID', $receiverProfileID);

        $this->id = $id;
        $this->senderProfileID = $senderProfileID;
        $this->receiverProfileID = $receiverProfileID;
        $this->setMessage($message);
        $this->setSocials($socials);

        if ($answer !== null) {
            $this->setAnswer($answer);
        }
    }

    public function getID(): ?string
    {
        return $this->id;
    }

    public function getSenderProfileID(): string
    {
        return $this->senderProfileID;
    }

    public function getReceiverProfileID(): string
    {
        return $this->receiverProfileID;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @throws ErrorException when an empty piece of text is given.
     */
    public function setMessage(string $text): void
    {
        $this->validateString('Message', $text);
        $this->message = $text;
    }

    /**
     * @return array<string, string>
     */
    public function getSocials(): array
    {
        return $this->socials;
    }

    public function addSocial(string $label, string $handle): void
    {
        $this->socials[$label] = $handle;
    }

    public function getAnswer(): ?ContactInformationRequestAnswer
    {
        return $this->answer;
    }

    /**
     * @throws ErrorException when an answer has already been given.
     */
    public function setAnswer(ContactInformationRequestAnswer $answer): void
    {
        if ($this->answer !== null) {
            throw new ErrorException('An answer has already been given');
        }

        $this->answer = $answer;
    }

    /**
     * @param array<string, string> $socials
     */
    private function setSocials(array $socials): void
    {
        foreach ($socials as $label => $handle) {
            $this->addSocial($label, $handle);
        }
    }
}
