import jQueryBridget from 'jquery-bridget';
import Velocity from 'velocity-animate';
import Isotope from 'isotope-layout';
import Masonry from 'masonry-layout';
import ImagesLoaded from 'imagesloaded';
import Player from '@vimeo/player/dist/player.js';

// Shared vars among modules
import appState from '../util/appState';
import siteOverlay from '../util/siteOverlay';
import loadingSpinner from '../util/loadingSpinner';

export default {
  init() {
    // Set up libraries to be used with jQuery
    jQueryBridget( 'isotope', Isotope, $ );
    jQueryBridget( 'masonry', Masonry, $ );
    jQueryBridget( 'player', Player, $ );

    // Set up Global Vars
    const $body = $('body');
    const $siteHeader = $('#site-header');
    const $siteNav = $('#site-nav');

    // Resize Vars
    var transitionElements = [],
        resizeTimer,
        $filtersMasonry,
        $customCursor,
        breakpointIndicatorString,
        breakpoint_xl = false,
        breakpoint_nav = false,
        breakpoint_lg = false,
        breakpoint_md = false,
        breakpoint_sm = false,
        breakpoint_xs = false;

    transitionElements = [$siteNav];

    // JavaScript to be fired on all pages
    _initCustomCursor();
    _initSmoothScroll();
    _initActiveToggle();
    _initHoverPairs();
    _initSiteNav();
    _initFormFunctions();
    _initFilters();
    _initTeamModal();
    _initHeaderVideo();

    // Keyboard-triggered functions
    $(document).keyup(function(e) {
      // Escape key
      if (e.keyCode === 27) {
        if (appState.modalOpen) {
          _closeTeamModal();
        }
        if ($body.is('.filters-open')) {
          _closeFilters($('.filters.-active'));
        }
      }
    });

    function _initCustomCursor() {
      if (!$('.js-cursor').length) {
        return;
      }

      $customCursor = $('<div class="cursor"></div>').appendTo($body);

      var lastMousePosition = { x: 0, y: 0 };

        // Update the mouse position
        function onMouseMove(evt) {
          lastMousePosition.x = evt.clientX;
          lastMousePosition.y = evt.clientY;
          requestAnimationFrame(update);
        }

        function update() {
          // Get the element we're hovered on
          var hoveredEl = document.elementFromPoint(lastMousePosition.x, lastMousePosition.y);

          // Check if the element or any of its parents have a .js-cursor class
          if ($(hoveredEl).parents('.js-cursor').length || $(hoveredEl).hasClass('js-cursor')) {
            $body.addClass('-cursor-active');

            if ($(hoveredEl).is('.previous')) {
              $customCursor.addClass('previous');
            } else {
              $customCursor.removeClass('previous');
            }

            if ($(hoveredEl).is('.next')) {
              $customCursor.addClass('next');
            } else {
              $customCursor.removeClass('next');
            }
          } else {
            $body.removeClass('-cursor-active');
          }

          // now draw object at lastMousePosition
          $customCursor.css({
            'transform': 'translate3d(' + lastMousePosition.x + 'px, ' + lastMousePosition.y + 'px, 0)'
          });
        }

        // Listen for mouse movement
        document.addEventListener('mousemove', onMouseMove, false);
        // Make sure a user is still hovered on an element when they start scrolling
        document.addEventListener('scroll', update, false);
    }

    function _scrollBody(element, offset, duration, delay) {
      var headerOffset = $siteHeader.outerHeight();
      if (typeof offset === "undefined" || offset === null) {
        offset = headerOffset;
      }
      if (typeof duration === "undefined" || duration === null) {
        duration = 300;
      }

      if ($(element).length) {
        appState.isAnimating = true;
        element.velocity("scroll", {
          duration: duration,
          delay: delay,
          offset: -offset,
          complete: function(elements) {
            appState.isAnimating = false;
          }
        }, "easeOutSine");
      }
    }

    function _disableScroll() {
      var st = $(window).scrollTop();
      $body.attr('data-st', st);
      $body.addClass('no-scroll');
      $body.css('top', -st);
    }

    function _enableScroll() {
      $body.removeClass('no-scroll');
      $body.css('top', '');
      $(window).scrollTop($body.attr('data-st'));
      $body.attr('data-st', '');
    }

    function _getUrlParameter(name) {
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function _initSmoothScroll() {
      $body.on('click', '.smooth-scroll', function(e) {
        e.preventDefault();
        _scrollBody($($(this).attr('href')));
      });
    }

    function _initActiveToggle() {
      $(document).on('click', '[data-active-toggle].-active', function(e) {
        if ($(this).attr('data-active-toggle') !== '') {
          $(this).removeClass('-active');
          $($(this).attr('data-active-toggle')).removeClass('-active');
        }
      });
      $(document).on('click', '[data-active-toggle]:not(.-active)', function(e) {
        if ($(this).attr('data-active-toggle') !== '') {
          $(this).addClass('-active');
          $($(this).attr('data-active-toggle')).addClass('-active');
        }
      });
    }

    function _initHoverPairs() {
      $(document).on('mouseenter', '[data-hover-pair]', function(e) {
        var hoverPair = $(this).attr('data-hover-pair');
        $('[data-hover-pair="'+hoverPair+'"]').addClass('-hover');
      }).on('mouseleave', '[data-hover-pair]', function(e) {
        var hoverPair = $(this).attr('data-hover-pair');
        $('[data-hover-pair="'+hoverPair+'"]').removeClass('-hover');
      });
    }


    function _initSiteNav() {
      if (!$('#nav-toggle').length) {
        $('#site-header').append('<button id="nav-toggle" class="nav-toggle" aria-hidden="true" data-active-toggle="#site-nav"></button>');
      }

      $(document).on('click', '#nav-toggle', function() {
        if ($(this).is('.-active')) {
          $('body').addClass('nav-open');
        } else {
          $('body').removeClass('nav-open');
        }
      });
    }

    function _initFormFunctions() {
      $('.input-wrap input, .input-wrap textarea,.input-wrap button[type="submit"], .select-wrap select').on('focus', function() {
        $(this).closest('.input-wrap').addClass('-focus');
      }).on('blur', function() {
        $(this).closest('.input-wrap').removeClass('-focus');
        if($(this).val()) {
          $(this).closest('.input-wrap').addClass('-filled');
        } else {
          $(this).closest('.input-wrap').removeClass('-filled');
        }
      });

      // Select functionality
      $('.select-wrap select').on('change', function() {
        $(this).closest('.select-wrap').addClass('-filled');
      });
    }

    function _initFilters() {
      // Toggleing Filters
      $('.filters-toggle').on('click', function() {
        var $filters = $(this).closest('.filters');

        if ($filters.is('.-active')) {
          _closeFilters($filters);
        } else {
          _openFilters($filters);
        }
      });
      // Close Filters Button
      $('.filters-close').on('click', function() {
        var $filters = $(this).closest('.filters');

        if ($filters.is('.-active')) {
          _closeFilters($filters);
        } else {
          _openFilters($filters);
        }
      });

      // Masonry Layout for filters
      $filtersMasonry = $('.filters-container .-inner').masonry({
        itemSelector: '.filter-group',
        columnWidth: '.filter-group',
        containerStyle: null,
        transitionDuration: 0
      });

      // Isotope Functionality
      var $filterGrid = $('.filter-grid').isotope({
        itemSelector: '.grid-item',
        layoutMode: 'fitRows',
        percentPosition: true,
        hiddenStyle: {
          opacity: 0,
        },
        visibleStyle: {
          opacity: 1,
        }
      });

      // check if there are any active filters
      function anyActiveFilters() {
        if ($('.filters-container .filter.is-checked').length) {
          return true;
        } else {
          return false;
        }
      }

      // store filter for each group
      var filters = [];

      // change is-checked class on buttons
      $('.filter-group').on( 'click', 'button.filter', function(event) {
        var groupId = $(this).closest('.filter-group').attr('id');
        var $target = $(event.currentTarget);
        $target.toggleClass('is-checked');
        var isChecked = $target.hasClass('is-checked');
        var filter = $target.attr('data-filter');
        if (isChecked) {
          addFilter(filter);
          var $activeSibling = $target.closest('.filter-group').find('.filter.is-checked').not($target);
          var activeSiblingFilter = $activeSibling.attr('data-filter');
          $activeSibling.removeClass('is-checked');
          removeFilter(activeSiblingFilter);
        } else {
          removeFilter(filter);
        }
        // filter isotope
        // group filters together, inclusive
        $filterGrid.isotope({ filter: filters.join('') });

        // Update filter toggle status
        _checkFilterGroupStatus(groupId);

        if (anyActiveFilters) {
          $('button.clear-filters').removeClass('hidden');
        } else {
          $('button.clear-filters').addClass('hidden');
        }
      });

      function addFilter(filter) {
        if (filters.indexOf(filter) == -1) {
          filters.push(filter);
        }
      }

      function removeFilter(filter) {
        var index = filters.indexOf(filter);
        if (index != -1) {
          filters.splice(index, 1);
        }
      }

      // Clear All Filters
      $('button.clear-filters').on('click', function() {
        $(this).addClass('hidden');
        $('.filters-container .group-label, .filters-toggle').removeClass('-active');
        $('.filters-container .filter.is-checked').removeClass('is-checked');
        filters = [];
        $filterGrid.isotope({ filter: '*' });
      });
    }

    function _openFilters($filters) {
      var $container = $filters.find('.filters-container');
      $filters.addClass('-active');
      $body.addClass('filters-open');
      $container.velocity({
          opacity: 1
        },{
          display: 'block',
          duration: 250,
          easing: 'easeOut',
          complete: function() {
            $filtersMasonry.masonry();
          }
      });
    }

    function _closeFilters($filters) {
      var $container = $filters.find('.filters-container');
      $filters.removeClass('-active');
      $body.removeClass('filters-open');
      $container.velocity('fadeOut', { duration: 250, easing: 'easeOut' });
      $container.velocity({
          opacity: 0
        },{
          display: 'none',
          duration: 250,
          easing: 'easeOut',
      });
    }

    function _checkFilterGroupStatus(groupId) {
      if ($('#' + groupId).find('button.is-checked').length) {
        $('#' + groupId + ' .group-label, .toggle-container .filters-toggle[data-group=' + groupId + ']').addClass('-active');
      } else {
        $('#' + groupId + ' .group-label, .toggle-container .filters-toggle[data-group=' + groupId + ']').removeClass('-active');
      }
    }

    function _initTeamModal() {
      $('.team-member.with-modal').on('click', function(e) {
        var $target = $(e.target);
        if (!$target.is('a')) {
          _openTeamModal($(this));
        }
      });

      $(document).on('click', '#team-modal .modal-close, #site-overlay', function() {
        if (appState.modalOpen) {
          _closeTeamModal();
        }
      });
    }

    function _openTeamModal($member) {
      if (!$('.team-modal').length) {
        $('body').append('<div id="team-modal" class="team-modal"><button class="modal-close"><svg aria-hidden="true" role="presentation"><use xlink:href="#icon-close-condensed"/></svg></button><div class="modal-content"><h4 class="modal-member-name"></h4><div class="member-meta"><div class="member-info"></div></div><div class="member-bio"></div></div></div>');
      }

      var $teamModal = $('#team-modal');

      // Clear Modal Content
      $teamModal.find('.modal-member-name').html('');
      $teamModal.find('.member-meta').html('');
      $teamModal.find('.member-bio').html('');

      // Get Member Data
      var memberName = $member.find('.member-name').text();
      var memberPhoto = $member.attr('data-photo');
      var memberInfo = $member.find('.member-info');
      var memberBio = $member.find('.member-bio').html();

      $teamModal.find('.modal-member-name').html('/ ' + memberName);
      $teamModal.find('.member-meta').append('<div class="member-photo" style="background-image:url('+ memberPhoto +');"></div>');
      memberInfo.clone().appendTo('#team-modal .member-meta');
      $teamModal.find('.member-bio').html(memberBio);

      $body.addClass('modal-open');
      siteOverlay.show();
      $teamModal.velocity('stop').velocity({
          opacity: [1, 0],
          translateY: [0, 50],
          // translateX: [0, '100%'],
          translateZ: 0,
          // skewX: [0, '-10deg'],
        }, {
          duration: 400,
          display: 'block',
          complete: function() {
            $teamModal.addClass('-active');
          }
        }
      )
      _disableScroll();
      appState.modalOpen = true;
    }

    function _closeTeamModal() {
      appState.modalOpen = false;
      $('#team-modal').removeClass('-active').velocity({
          opacity: [0, 1],
          translateY: [50, 0],
          // translateX: '110%',
          translateZ: 0,
          // skewX: ['10deg', [1,.92,.43,1.35], 0]
        }, {
          duration: 350,
          display: 'none',
          complete: function() {
            $('#team-modal').find('.member-bio').scrollTop(0);
          }
        }
      );
      siteOverlay.hide();
      $body.removeClass('modal-open');
      _enableScroll();
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
    function _resize() {
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

      // Reset inline styles for navigation for medium breakpoint
      if (breakpoint_nav) {
        $('#site-nav, .nav-toggle').removeClass('-active');
        $('body').removeClass('nav-open');
      }

      // Functions to run on resize end
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        // Re-enable transitions
        _enableTransitions();
      }, 250);
    }

    $(window).resize(_resize);
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};