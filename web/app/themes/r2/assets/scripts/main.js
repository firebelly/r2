// import external dependencies
import 'jquery';
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';
import SwupDebugPlugin from '@swup/debug-plugin';
import Waypoint from 'waypoints/lib/jquery.waypoints.js';
import Velocity from 'velocity-animate';

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import about from './routes/about';

const swup = new Swup({
  plugins: [
    new SwupBodyClassPlugin(),
    new SwupScrollPlugin({
      animateScroll: false
    }),
    // new SwupDebugPlugin()
  ],
  linkSelector:
    'a[href^="' +
    window.location.origin +
    '"]:not([href*="wp-admin"]):not([data-no-swup]), a[href^="/"]:not([href*="wp-admin"]):not([data-no-swup]), a[href^="#"]:not([href*="wp-admin"]):not([data-no-swup])'
});

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About page
  about,
});

// Load Events
$(document).ready(() => routes.loadEvents());
// Reload events when swup replaces content
swup.on('contentReplaced', function() {
  routes.loadEvents();
  $('#nav-toggle.-active').removeClass('-active');
});