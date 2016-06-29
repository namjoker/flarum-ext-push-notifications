import SettingsModal from 'flarum/components/SettingsModal';

export default class NotificationsSettingsModal extends SettingsModal {
  className() {
    return 'NotificationsSettingsModal Modal--small';
  }

  title() {
    return app.translator.trans('BlissfulPlugins-push-notifications.admin.notifications_settings.title');
  }

  form() {
    return [
      <div className="Form-group">
        <label>{app.translator.trans('BlissfulPlugins-push-notifications.admin.notifications_settings.application_id_label')}</label>
        <input className="FormControl" bidi={this.setting('BlissfulPlugins-push-notifications.application_id')}/>
      </div>,

      <div className="Form-group">
        <label>{app.translator.trans('BlissfulPlugins-push-notifications.admin.notifications_settings.application_auth_key_label')}</label>
        <input className="FormControl" bidi={this.setting('BlissfulPlugins-push-notifications.application_auth_key')}/>
      </div>
    ];
  }
}
