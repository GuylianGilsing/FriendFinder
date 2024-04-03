<?php

declare(strict_types=1);

namespace FriendFinder\Common\Events\ProfileInformation;

use DateTimeImmutable;
use DateTimeInterface;
use ErrorException;

use function FriendFinder\Common\JSON\parse_array_to_json;

final class ProfileInformationUpdatedEvent
{
    private ProfileInformationUpdatedEventType $type;
    private DateTimeImmutable $createdAt;

    /** @var array<string, mixed> $payload */
    private array $payload;

    /**
     * @param array<string, mixed> $payload
     *
     * @throws ErrorException when no valid payload is given.
     * @throws ErrorException when the given payload is not valid JSON.
     */
    public function __construct(ProfileInformationUpdatedEventType $type, array $payload)
    {
        $this->type = $type;
        $this->setPayload($payload);
        $this->createdAt = new DateTimeImmutable();
    }

    public function getType(): ProfileInformationUpdatedEventType
    {
        return $this->type;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @throws ErrorException when no valid payload is given.
     * @throws ErrorException when the given payload is not valid JSON.
     */
    public function setPayload(array $payload): void
    {
        $keyCount = count(array_keys($payload));
        $valueCount = count(array_values($payload));

        if ($keyCount === 0 && $valueCount === 0) {
            throw new ErrorException('No valid payload is given');
        }

        $parsedPayload = parse_array_to_json($payload);

        // This probably won't ever happen, but it's still important to catch it when it happens for some ungodly reason
        if ($parsedPayload === null) {
            throw new ErrorException('Given payload is not valid JSON');
        }

        $this->payload = $payload;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function toJSONString(): ?string
    {
        return parse_array_to_json([
            'type' => $this->type->value,
            'data' => $this->payload,
            'createdAt' => $this->createdAt->format(DateTimeInterface::RFC3339_EXTENDED),
        ]);
    }
}
