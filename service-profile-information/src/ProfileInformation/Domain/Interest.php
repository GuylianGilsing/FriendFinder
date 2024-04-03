<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use ErrorException;

use function FriendFinder\Common\Strings\string_is_blank;

#[Entity()]
#[Table('profile_interests')]
class Interest
{
    /**
     * The maximum numbers of characters an interest could have.
     */
    public const MAX_LENGTH = 32;

    #[Id()]
    #[Column(name: 'id', type: Types::BIGINT, unique: true)]
    #[GeneratedValue()]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Profile::class, inversedBy: 'interests', cascade: ['remove'])]
    #[JoinColumn(name: 'profile_id', referencedColumnName: 'identity')]
    private Profile $profile;

    #[Column(name: 'text', type: Types::STRING, length: 255)]
    private string $text;

    /**
     * @throws ErrorException when an empty piece of text is given.
     * @throws ErrorException when an piece of text that is longer than 32 characters is given.
     */
    public function __construct(?int $id, Profile $profile, string $text)
    {
        $this->id = $id;
        $this->profile = $profile;

        $this->setText($text);
    }

    public function getID(): int
    {
        return $this->id ?? 0;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @throws ErrorException when an empty piece of text is given.
     * @throws ErrorException when an piece of text that is longer than 32 characters is given.
     */
    public function setText(string $text): void
    {
        if (string_is_blank($text)) {
            throw new ErrorException('Given text can\'t be blank');
        }

        if (strlen($text) > self::MAX_LENGTH) {
            throw new ErrorException('Given text can\'t be longer than '.self::MAX_LENGTH.' characters');
        }

        $this->text = $text;
    }
}
