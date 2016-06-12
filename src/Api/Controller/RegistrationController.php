<?php
/**
 * Copyright (c) 2016 Blissful Systems Limited
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace BlissfulPlugins\Notifications\Api\Controller;

use Flarum\Core\Guest;
use Flarum\Http\Controller\ControllerInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use BlissfulPlugins\Notifications\Logger;

class RegistrationController implements ControllerInterface
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
     * @param ServerRequestInterface $request
     * @return EmptyResponse|JsonResponse
     */
    public function handle(ServerRequestInterface $request)
    {
        $user = $request->getAttribute('actor');

        if (is_a($user, Guest::class)) {
            // Not logged in
            return new EmptyResponse(403);
        } else {
            $deviceId = array_get($request->getParsedBody(), 'deviceId');

            Logger::info('Registration ' . $deviceId . ' from ' . $user->id);

            // TODO register with notification service

            return new EmptyResponse(200);
        }
    }
}
