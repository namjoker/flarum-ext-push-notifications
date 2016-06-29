var gulp = require('flarum-gulp');

gulp({
  modules: {
    'BlissfulPlugins/push-notifications': 'src/**/*.js'
  }
});
