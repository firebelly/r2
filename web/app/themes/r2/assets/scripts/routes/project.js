import jQueryBridget from 'jquery-bridget';
import Cocoen from 'cocoen';
import Player from '@vimeo/player/dist/player.js';
import Flickity from 'flickity-fade';

import loadingSpinner from '../util/loadingSpinner';

export default {
  init() {
    // Set up libraries to be used with jQuery
    jQueryBridget( 'flickity', Flickity, $ );
    jQueryBridget( 'cocoen', Cocoen, $ );
    jQueryBridget( 'player', Player, $ );

    // Init
    _initCarousels();
    _initCocoen();
    _initHeaderVideo();
    _initProjectVideo();

    function _initCarousels() {
      if ($('.header-carousel').length) {
        var $headerCarousel = $('.header-carousel').removeClass('is-hidden');
        $headerCarousel[0].offsetHeight;
        $headerCarousel.flickity({
          prevNextButtons: false,
          fade: true,
          autoPlay: true,
          draggable: false,
          cellSelector: '.image'
        });
      }
    }

    function _initCocoen() {
      $('.cocoen').cocoen();

      if ($('.cocoen-drag').length) {
        $('.cocoen-drag').append('<svg class="slider" aria-hidden="true" role="presentation"><use xlink:href="#slider"/></svg>');
      }
    }

    function _initHeaderVideo() {
      if (!$('#header-video').length) {
        return;
      }

      loadingSpinner.show($('.header-video'));
      var $headerVideo = $('#header-video');

      var options = {
        background: true
      };

      if ($headerVideo[0].hasAttribute('data-url')) {
        options['url'] = $headerVideo.attr('data-url');
      } else {
        options['id'] = $headerVideo.attr('data-id');
      }

      var player = new Player('header-video', options);

      // Show thumbnail when video finishes
      player.on('play', function() {
        loadingSpinner.hide();
        $headerVideo.removeClass('is-hidden');
      });
    }

    function _initProjectVideo() {
      if (!$('.project-video').length) {
        return;
      }

      var $projectVideo = $('#project-video');
      var $container = $projectVideo.closest('.project-video-container');

      var options = {
          responsive: true
      };

      if ($projectVideo[0].hasAttribute('data-url')) {
        options['url'] = $projectVideo.attr('data-url');
      } else {
        options['id'] = $projectVideo.attr('data-id');
      }

      var player = new Player('project-video', options);

      $('.project-video-play').on('click', function() {
        $container.addClass('playing');
        player.play();
      });

      // Show thumbnail when video finishes
      player.on('ended', function() {
        $container.removeClass('playing');
      });
    }

  },
};
