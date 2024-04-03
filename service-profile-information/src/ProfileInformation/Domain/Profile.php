<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use ErrorException;
use FriendFinder\Common\Serialization\JSONSerializableInterface;
use FriendFinder\Common\Strings\ValidateStringTrait;

use function FriendFinder\Common\Strings\string_is_blank;

#[Entity()]
#[Table('profiles')]
class Profile implements JSONSerializableInterface
{
    use ValidateStringTrait;

    #[Id()]
    #[Column(name: 'identity', type: 'uuid', unique: true)]
    private string $identity;

    #[Column(name: 'display_name', type: Types::STRING, length: 255)]
    private string $displayName;

    #[Column(name: 'date_of_birth', type: Types::DATE_IMMUTABLE)]
    private DateTimeImmutable $dateOfBirth;

    #[OneToMany(targetEntity: Interest::class, mappedBy: 'profile', cascade: ['all'], orphanRemoval: true)]
    private Collection $interests;

    /**
     * @param array<Interest> $interests
     *
     * @throws ErrorException when an empty ID is given.
     * @throws ErrorException when an empty display name is given.
     * @throws ErrorException when a date of birth that does not belong to someone that is 18 years or older is given.
     */
    public function __construct(
        string $identity,
        string $displayName,
        DateTimeImmutable $dateOfBirth,
        array $interests = []
    ) {
        $this->validateString('Identity', $identity);

        $this->identity = $identity;
        $this->setDisplayName($displayName);
        $this->setDateOfBirth($dateOfBirth);
        $this->interests = new ArrayCollection($interests);
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @throws ErrorException when an empty display name is given.
     */
    public function setDisplayName(string $name): void
    {
        if (strlen($name) === 0 || string_is_blank($name)) {
            throw new ErrorException('Given display name can\'t be empty');
        }

        $this->displayName = $name;
    }

    public function getDateOfBirth(): DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    /**
     * @throws ErrorException when a date of birth that does not belong to someone that is 18 years or older is given.
     */
    public function setDateOfBirth(DateTimeImmutable $date): void
    {
        $date18YearsAgo = (new DateTimeImmutable())->modify('-18 year');

        if ($date->getTimestamp() > $date18YearsAgo->getTimestamp()) {
            throw new ErrorException('Given date is not that of someone that is 18 years or older');
        }

        $this->dateOfBirth = $date;
    }

    /**
     * @return array<Interest>
     */
    public function getInterests(): array
    {
        return $this->interests->toArray();
    }

    public function hasInterest(string $text): bool
    {
        foreach ($this->interests as $interest) {
            if ($interest->getText() === $text) {
                return true;
            }
        }

        /** @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter */
        $searchPredicate = static fn ($index, $interest) => $interest->getText() === $text;

        return $this->interests->findFirst($searchPredicate) !== null;
    }

    /**
     * @throws ErrorException when an empty piece of text is given.
     * @throws ErrorException when an piece of text that is longer than 32 characters is given.
     * @throws ErrorException when an interest that has already been added to this profile is passed.
     */
    public function addInterest(string $text): void
    {
        if (string_is_blank($text)) {
            throw new ErrorException('Interest can\'t be blank');
        }

        if (strlen($text) > Interest::MAX_LENGTH) {
            throw new ErrorException('Interest can\'t be longer than '.Interest::MAX_LENGTH.' characters');
        }

        if ($this->hasInterest($text)) {
            throw new ErrorException('Interest "'.$text.'" has already been added to your profile');
        }

        $this->interests->add(new Interest(0, $this, $text));
    }

    /**
     * @return array<string, mixed>
     */
    public function toJSONArray(): array
    {
        $interests = [];

        foreach ($this->getInterests() as $interest) {
            $interests[] = $interest->getText();
        }

        return [
            'identity' => $this->identity,
            'displayName' => $this->displayName,
            'dateOfBirth' => $this->dateOfBirth->format(DateTimeInterface::RFC3339_EXTENDED),
            'interests' => $interests,
        ];
    }
}
