# Push Notifications for Flarum

Sends Flarum notifications as push notifications to mobile devices.

## Introduction

By default Flarum only sends notifications via email and displays them within
the forum. To increase engagement, this plugin adds support for sending them to
mobile devices as push notifications.

It uses the [OneSignal](http://onesignal.com/) service, which is free and
supports native push notifications on all the major mobile platforms. You'll
need a mobile application in order to receive the notifications - a sample
built in PhoneGap is available [here](https://github.com/BlissfulPlugins/flarum-ext-push-notifications-phonegap).

Note that this plugin doesn't support HTML5 push notifications, which don't
work on iOS.

## Setup

You'll need to register for a OneSignal account, create an 'app', and configure
whichever platforms you want.

Note that for Apple, you need a paid iOS developer account to test
notifications. You can test for free with Android using the emulator, although
it's a bit complicated to get everything setup correctly as you need Google
Play Services which aren't included by default. [This is a good
tutorial](https://github.com/codepath/android_guides/wiki/Genymotion-2.0-Emulators-with-Google-Play-support)
on setting that up with the GenyMotion emulator.

Next install the plugin, either editing your `compose.json` or running this:

    composer require BlissfulPlugins/flarum-ext-push-notifications

Then login as an administrator and enable the 'Push Notifications' plugin, go
to the settings and fill out the details of your OneSignal account (from App
Settings -> Keys & IDs).

Once a user has setup the app, they'll then receive all their notifications as
push notifications on their mobile device. The URL for the post or discussion
is included as an extra attribute in the notification, you can use this so
when a user clicks on a notification it opens Flarum in their browser.

## Caveats

* There isn't currently a way for users to configure which notifications are
  sent as push notifications. Flarum doesn't appear to have a way to hook into
  the notification configuration to add a new medium.
* Flarum's handling of whether a notification should be sent or not seems to
  break sometimes, so notifications aren't always sent when they should be.

## License

[MPL v2.0](https://github.com/BlissfulPlugins/flarum-ext-push-notifications/blob/master/LICENSE)
