<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Serializers\ContactInformationRequestAnswer;

use FriendFinder\ContactInformation\Domain\ContactInformationRequestAnswer;

final class DetailedContactInformationRequestAnswerSerializer
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(ContactInformationRequestAnswer $model): array
    {
        return [
            'id' => $model->getID(),
            'profileID' => $model->getIdentity(),
            'answer' => $model->getAnswer()->value,
        ];
    }
}
