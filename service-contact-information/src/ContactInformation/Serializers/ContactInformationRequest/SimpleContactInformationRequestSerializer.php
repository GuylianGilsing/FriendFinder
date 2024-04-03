<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Serializers\ContactInformationRequest;

use FriendFinder\ContactInformation\Domain\ContactInformationRequest;

use function FriendFinder\Common\Strings\generate_summary;

final class SimpleContactInformationRequestSerializer
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(ContactInformationRequest $model, string $identity): array
    {
        $profileID = $identity === $model->getSenderProfileID()
            ? $model->getReceiverProfileID()
            : $model->getSenderProfileID();

        return [
            'id' => $model->getID(),
            'identity' => $model->getSenderProfileID(),
            'profileID' => $profileID,
            'answered' => $model->getAnswer() !== null,
            'messageSummary' => generate_summary($model->getMessage(), maxLength: 32),
        ];
    }
}
