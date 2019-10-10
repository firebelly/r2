// import external dependencies
import 'jquery';
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';
import SwupDebugPlugin from '@swup/debug-plugin';
import Waypoint from 'waypoints/lib/jquery.waypoints.js';

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import singleProject from './routes/project';

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
  singleProject,
});

function _initInviewElements() {
  $('.animate-in').each(function() {
    var $elem = $(this);
    inView($elem);
  });

  $('.animate-out').each(function() {
    var $elem = $(this);
    var inview = new Waypoint.Inview({
      element: $(this)[0],
      exited: function(direction) {
        $(this).addClass('out-of-view');
      }
    });
  });

  function inView($elem) {
    var waypoint = $elem.waypoint(function(direction) {
      $elem.addClass('in-view', direction === 'down');
    },{
      offset: '85%'
    });
  }

  $('.animate-in-series').each(function() {
    var $container = $(this);
    $container.waypoint(function(direction) {
      $container.addClass('in-view', direction === 'down');
    },{
      offset: '85%'
    });

    // establish transition delays
    var animationItems = $container.find('.animation-item');
    animationItems.each(function(i) {
      $(this).css('transition-delay', 0.1 * i + 's');
    });
  });
}
_initInviewElements();

// Load Events
$(document).ready(() => routes.loadEvents());
// Reload events when swup replaces content
swup.on('contentReplaced', function() {
  routes.loadEvents();
  $('#nav-toggle.-active').removeClass('-active');
});
swup.on('animationInDone', function() {
  _initInviewElements();
});