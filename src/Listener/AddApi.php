<?php
/**
 * Copyright (c) 2016 Blissful Systems Limited
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace BlissfulPlugins\Notifications\Listener;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Event\ConfigureApiRoutes;
use Flarum\Event\PrepareApiAttributes;
use BlissfulPlugins\Notifications\Api\Controller\RegistrationController;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;
use BlissfulPlugins\Notifications\Logger;

class AddApi
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
        $events->listen(PrepareApiAttributes::class, [$this, 'addAttributes']);
        $events->listen(ConfigureApiRoutes::class, [$this, 'addRoutes']);
    }

    /**
     * @param PrepareApiAttributes $event
     */
    public function addAttributes(PrepareApiAttributes $event)
    {
        // TODO
        //if ($event->isSerializer(ForumSerializer::class)) {
        //    $event->attributes['pusherKey'] = $this->settings->get('flarum-pusher.app_key');
        //    $event->attributes['pusherCluster'] = $this->settings->get('flarum-pusher.app_cluster');
        //}
    }

    /**
     * @param ConfigureApiRoutes $event
     */
    public function addRoutes(ConfigureApiRoutes $event)
    {
        $event->post('/notifications/registration', 'notifications.registration', RegistrationController::class);
    }
}
