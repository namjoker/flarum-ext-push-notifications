import { extend } from 'flarum/extend';
import app from 'flarum/app';

import NotificationsSettingsModal from 'BlissfulPlugins/push-notifications/components/NotificationsSettingsModal';

app.initializers.add('BlissfulPlugins/push-notifications', app => {
  app.extensionSettings['BlissfulPlugins-push-notifications'] = () => app.modal.show(new NotificationsSettingsModal());
});
