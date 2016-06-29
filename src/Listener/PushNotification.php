<?php
/**
 * Copyright (c) 2016 Blissful Systems Limited
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace BlissfulPlugins\Notifications\Listener;

use Flarum\Core\User;
use Flarum\Core\Discussion;
use Flarum\Core\Post;
use Flarum\Core\Notification\BlueprintInterface;
use Flarum\Event\NotificationWillBeSent;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Likes\Notification\PostLikedBlueprint;
use Flarum\Mentions\Notification\UserMentionedBlueprint;
use Flarum\Subscriptions\Notification\NewPostBlueprint;
use Illuminate\Contracts\Events\Dispatcher;
use BlissfulPlugins\Notifications\Logger;
use BlissfulPlugins\Notifications\OneSignalFactory;

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
        $body = $this->getNotificationBody($blueprint);
        $url = $this->getNotificationUrl($blueprint);

        // Convert receipients to a list of tags
        $tags = [];
        foreach ($recipients as $recipient) {
            array_push($tags, [
                'key' => 'userId',
                'relation' => '=',
                'value' => $recipient->id
            ]);
        }

        // Send notification
        $api = OneSignalFactory::create($this->settings);
        $api->notifications->add([
            'contents' => [
                'en' => $body
            ],
            'data' => [
                'url' => $url
            ],
            'tags' => $tags
        ]);
    }

    /**
     * Returns the notification body/message for a given blueprint.
     * TODO i18n
     *
     * @param BlueprintInterface $blueprint
     * @return string
     */
    protected function getNotificationBody(BlueprintInterface $blueprint)
    {
        $sender = 'Somebody';
        if (is_a($blueprint->getSender(), User::class)) {
            $sender = $blueprint->getSender()->username;
        }

        $discussion = 'a discussion';
        if (is_a($blueprint->getSubject(), Discussion::class)) {
            $discussion = $blueprint->getSubject()->title;
        } elseif (is_a($blueprint->getSubject(), Post::class)) {
            $discussion = $blueprint->getSubject()->discussion->title;
        }

        if (is_a($blueprint, PostLikedBlueprint::class)) {
            return $sender . " liked your post in " . $discussion;
        } elseif (is_a($blueprint, UserMentionedBlueprint::class)) {
            return $sender . " mentioned you in " . $discussion;
        } elseif (is_a($blueprint, NewPostBlueprint::class)) {
            return $sender . " replied to " . $discussion;
        } else {
            return $blueprint->getEmailSubject();
        }
    }

    /**
     * Returns the url for the given blueprint.
     *
     * @param BlueprintInterface $blueprint
     * @return string or null
     */
    protected function getNotificationUrl(BlueprintInterface $blueprint)
    {
        $discussionId = null;
        if (is_a($blueprint->getSubject(), Discussion::class)) {
            $discussionId = $blueprint->getSubject()->id;
        } elseif (is_a($blueprint->getSubject(), Post::class)) {
            $discussionId = $blueprint->getSubject()->discussion->id;
        }

        if ($discussionId) {
            return app('Flarum\Forum\UrlGenerator')->toRoute('discussion', ['id' => $discussionId]);
        }
    }
}
