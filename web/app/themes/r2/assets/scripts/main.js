// import external dependencies
import 'jquery';
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';
import Waypoint from 'waypoints/lib/jquery.waypoints.js';

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import about from './routes/about';

const swup = new Swup({
  plugins: [new SwupBodyClassPlugin()]
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
});

// Resize Vars
var transitionElements = [],
    resizeTimer,
    breakpointIndicatorString,
    breakpoint_xl = false,
    breakpoint_nav = false,
    breakpoint_lg = false,
    breakpoint_md = false,
    breakpoint_sm = false,
    breakpoint_xs = false;

// Disabling transitions on certain elements on resize
function _disableTransitions() {
  $.each(transitionElements, function() {
    $(this).css('transition', 'none');
  });
}
function _enableTransitions() {
  $.each(transitionElements, function() {
    $(this).attr('style', '');
  });
}

/**
 * Called in quick succession as window is resized
 */
function resize() {
  // Check breakpoint indicator in DOM ( :after { content } is controlled by CSS media queries )
  breakpointIndicatorString = window.getComputedStyle(
    document.querySelector('#breakpoint-indicator'), ':after'
  ).getPropertyValue('content')
  .replace(/['"]+/g, '');

  // Determine current breakpoint
  breakpoint_xl = breakpointIndicatorString === 'xl';
  breakpoint_nav = breakpointIndicatorString === 'nav' || breakpoint_xl;
  breakpoint_lg = breakpointIndicatorString === 'lg' || breakpoint_nav;
  breakpoint_md = breakpointIndicatorString === 'md' || breakpoint_lg;
  breakpoint_sm = breakpointIndicatorString === 'sm' || breakpoint_md;
  breakpoint_xs = breakpointIndicatorString === 'xs' || breakpoint_sm;

  // Disable transitions when resizing
  _disableTransitions();

  // Functions to run on resize end
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function() {
    // Re-enable transitions
    _enableTransitions();
  }, 250);
}

// Fire Resize Functions
$(window).resize(resize);