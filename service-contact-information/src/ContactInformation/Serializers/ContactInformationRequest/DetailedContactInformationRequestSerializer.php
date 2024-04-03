<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Serializers\ContactInformationRequest;

use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Serializers\ContactInformationRequestAnswer\DetailedContactInformationRequestAnswerSerializer;

final class DetailedContactInformationRequestSerializer
{
    public function __construct(
        private readonly DetailedContactInformationRequestAnswerSerializer $detailedRequestAnswerSerializer,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(ContactInformationRequest $model, string $identity): array
    {
        $serializedAnswer = null;
        $socials = null;

        if ($model->getAnswer() !== null) {
            $serializedAnswer = $this->detailedRequestAnswerSerializer->serialize($model->getAnswer());
            $socials = $model->getSocials();
        }

        $profileID = $identity === $model->getSenderProfileID()
            ? $model->getReceiverProfileID()
            : $model->getSenderProfileID();

        return [
            'id' => $model->getID(),
            'identity' => $model->getSenderProfileID(),
            'profileID' => $profileID,
            'message' => $model->getMessage(),
            'socials' => $socials,
            'answer' => $serializedAnswer,
        ];
    }
}
