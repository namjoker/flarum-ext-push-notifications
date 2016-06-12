<?php
/**
 * Copyright (c) 2016 Blissful Systems Limited
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace BlissfulPlugins\Notifications\Listener;

use Flarum\Core\Notification\BlueprintInterface;
use Flarum\Event\NotificationWillBeSent;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;
use BlissfulPlugins\Notifications\Logger;

class PushNotification
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;
    /**
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(NotificationWillBeSent::class, [$this, 'pushNotification']);
    }

    /**
     * @param NotificationWillBeSent $event
     */
    public function pushNotification(NotificationWillBeSent $event)
    {
        Logger::debug('handleNotification');

        $blueprint = $event->blueprint;

        // Figure out which users have their preferences set to receive this
        // notification type
        $recipients = [];
        foreach ($event->users as $user) {
            if ($user->shouldAlert($blueprint::getType())) {
                array_push($recipients, $user);
            }
        }

        // If there are any receipients, send the notification
        if (count($recipients)) {
            Logger::debug('Sending ' . $blueprint::getType() . ' to ' . count($recipients) . ' receipient(s)');
            $this->sendNotification($blueprint, $recipients);
        } else {
            Logger::debug('No recipients for ' . $blueprint::getType());
        }
    }

    public function sendNotification($blueprint, $recipients)
    {
        Logger::debug('Sending ' . $blueprint::getType() . ' to ' . count($recipients) . ' receipient(s)');
    }

    /**
     * Construct an array of attributes to be stored in a notification record in
     * the database, given a notification blueprint.
     *
     * @param BlueprintInterface $blueprint
     * @return array
     */
    protected function getAttributes(BlueprintInterface $blueprint)
    {
        return [
            'type'       => $blueprint::getType(),
            'sender_id'  => ($sender = $blueprint->getSender()) ? $sender->id : null,
            'subject_id' => ($subject = $blueprint->getSubject()) ? $subject->id : null,
            'data'       => ($data = $blueprint->getData()) ? json_encode($data) : null,
            'view'       => $blueprint->getEmailView(),
            'subject'    => $blueprint->getEmailSubject()
        ];
    }
}
