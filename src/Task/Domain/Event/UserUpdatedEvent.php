<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Event;

use Assert\Assertion;
use Assert\AssertionFailedException;
use MicroModule\Base\Domain\Event\AbstractEvent;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class UserUpdatedEvent
 *
 * @package Micro\Tracker\Task\Domain\Event
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class UserUpdatedEvent extends AbstractEvent
{
    /**
     * Constructor
     */
    public function __construct(
		ProcessUuid $processUuid,
		Uuid $uuid,
		protected User $user,
		?Payload $payload = null
	)
    {
		parent::__construct($processUuid, $uuid, $payload);
        
    }

    /**
     * Return User value object.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Initialize event from data array.
     */
    public static function deserialize(array $data): static
    {
		Assertion::keyExists($data, 'process_uuid');
		Assertion::keyExists($data, 'uuid');
		Assertion::keyExists($data, 'user');
		$event = new static(
			ProcessUuid::fromNative($data['process_uuid']),
			Uuid::fromNative($data['uuid']),
			User::fromNative($data['user'])
		);

		if (isset($data['payload'])) {
			$event->setPayload(Payload::fromNative($data['payload']));
		}

        return $event;
    }

    /**
     * Convert event object to array.
     */
    public function serialize(): array
    {
		$data = [
			'process_uuid' => $this->getProcessUuid()->toNative(),
			'uuid' => $this->getUuid()->toNative(),
			'user' => $this->getUser()->toNative()
		];

		if ($this->payload !== null) {
			$data['payload'] = $this->payload->toNative();
		}

        return $data;
    }
}
