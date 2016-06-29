<?php
/**
 * Copyright (c) 2016 Blissful Systems Limited
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace BlissfulPlugins\Notifications;

use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\HttpMethodsClient as HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use OneSignal\Config;
use OneSignal\OneSignal;
use BlissfulPlugins\Notifications\Logger;

class OneSignalFactory
{
    /**
     * @param SettingsRepositoryInterface $settings
     */
    public static function create($settings)
    {
        $applicationId = $settings->get('BlissfulPlugins-push-notifications.application_id');
        $applicationAuthKey = $settings->get('BlissfulPlugins-push-notifications.application_auth_key');

        $config = new Config();
        $config->setApplicationId($applicationId);
        $config->setApplicationAuthKey($applicationAuthKey);

        $guzzle = new GuzzleClient();

        $client = new HttpClient(new GuzzleAdapter($guzzle), new GuzzleMessageFactory());
        return new OneSignal($config, $client);
    }
}
