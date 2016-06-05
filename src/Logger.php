<?php
/**
 * Copyright (c) 2016 Blissful Systems Limited
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace BlissfulPlugins\Notifications;

class Logger
{
    public static $log_file = 'storage/logs/notifications.log';

    public static function log($level, $message)
    {
        $timestamp = date(DATE_RFC2822);
        error_log($timestamp . ' ' . $level . ' ' . $message . "\n", 3, self::$log_file);
    }

    public static function debug($message)
    {
        self::log('debug', $message);
    }

    public static function info($message)
    {
        self::log('info', $message);
    }

    public static function error($message)
    {
        self::log('error', $message);
    }
}
