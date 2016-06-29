'use strict';

System.register('BlissfulPlugins/push-notifications/components/NotificationsSettingsModal', ['flarum/components/SettingsModal'], function (_export, _context) {
  "use strict";

  var SettingsModal, NotificationsSettingsModal;
  return {
    setters: [function (_flarumComponentsSettingsModal) {
      SettingsModal = _flarumComponentsSettingsModal.default;
    }],
    execute: function () {
      NotificationsSettingsModal = function (_SettingsModal) {
        babelHelpers.inherits(NotificationsSettingsModal, _SettingsModal);

        function NotificationsSettingsModal() {
          babelHelpers.classCallCheck(this, NotificationsSettingsModal);
          return babelHelpers.possibleConstructorReturn(this, Object.getPrototypeOf(NotificationsSettingsModal).apply(this, arguments));
        }

        babelHelpers.createClass(NotificationsSettingsModal, [{
          key: 'className',
          value: function className() {
            return 'NotificationsSettingsModal Modal--small';
          }
        }, {
          key: 'title',
          value: function title() {
            return app.translator.trans('BlissfulPlugins-push-notifications.admin.notifications_settings.title');
          }
        }, {
          key: 'form',
          value: function form() {
            return [m(
              'div',
              { className: 'Form-group' },
              m(
                'label',
                null,
                app.translator.trans('BlissfulPlugins-push-notifications.admin.notifications_settings.application_id_label')
              ),
              m('input', { className: 'FormControl', bidi: this.setting('BlissfulPlugins-push-notifications.application_id') })
            ), m(
              'div',
              { className: 'Form-group' },
              m(
                'label',
                null,
                app.translator.trans('BlissfulPlugins-push-notifications.admin.notifications_settings.application_auth_key_label')
              ),
              m('input', { className: 'FormControl', bidi: this.setting('BlissfulPlugins-push-notifications.application_auth_key') })
            )];
          }
        }]);
        return NotificationsSettingsModal;
      }(SettingsModal);

      _export('default', NotificationsSettingsModal);
    }
  };
});;
'use strict';

System.register('BlissfulPlugins/push-notifications/main', ['flarum/extend', 'flarum/app', 'BlissfulPlugins/push-notifications/components/NotificationsSettingsModal'], function (_export, _context) {
  "use strict";

  var extend, app, NotificationsSettingsModal;
  return {
    setters: [function (_flarumExtend) {
      extend = _flarumExtend.extend;
    }, function (_flarumApp) {
      app = _flarumApp.default;
    }, function (_BlissfulPluginsPushNotificationsComponentsNotificationsSettingsModal) {
      NotificationsSettingsModal = _BlissfulPluginsPushNotificationsComponentsNotificationsSettingsModal.default;
    }],
    execute: function () {

      app.initializers.add('BlissfulPlugins/push-notifications', function (app) {
        app.extensionSettings['BlissfulPlugins-push-notifications'] = function () {
          return app.modal.show(new NotificationsSettingsModal());
        };
      });
    }
  };
});