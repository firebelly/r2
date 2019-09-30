import jQueryBridget from 'jquery-bridget';
import Isotope from 'isotope-layout';
import Flickity from 'flickity-fade';

export default {
  init() {
    // Set up isotope to be used with jQuery
    jQueryBridget( 'isotope', Isotope, $ );
    jQueryBridget( 'flickity', Flickity, $ );
    // Set up Global Vars
    const $body = $('body');
    const $siteHeader = $('#site-header');
    const $siteNav = $('#site-nav');
    var $siteOverlay = '<div id="site-overlay"></div>';

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

    transitionElements = [$siteNav];

    // JavaScript to be fired on all pages
    _initActiveToggle();
    _initSiteNav();
    _initFormFunctions();
    _initInviewElements();
    _initFilters();
    _initCarousels();
    _initTeamModal();

    // Keyboard-triggered functions
    $(document).keyup(function(e) {
      // Escape key
      if (e.keyCode === 27) {
        _closeTeamModal();
        if ($body.is('.filters-open')) {
          _closeFilters();
        }
        // _hideSiteOverlay();
        // _closeSiteNav();
      }

      // Left Arrow
      if (e.keyCode === 37) {
        if ($body.is('.modal-open')) {
          // _changeProjectModal('previous');
        }
      }

      // Right Arrow
      if (e.keyCode === 39) {
        if ($body.is('.modal-open')) {
          // _changeProjectModal('next');
        }
      }
    });

    function _scrollBody(element, offset, duration, delay) {
      var headerOffset = $siteHeader.outerHeight();
      if (typeof offset === "undefined" || offset === null) {
        offset = headerOffset;
      }
      if (typeof duration === "undefined" || duration === null) {
        duration = 300;
      }

      if ($(element).length) {
        isAnimating = true;
        element.velocity("scroll", {
          duration: duration,
          delay: delay,
          offset: -offset,
          complete: function(elements) {
            isAnimating = false;
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
      $(document).on('click', '.smooth-scroll', function(e) {
        e.preventDefault();
        _scrollBody($($(this).attr('href')));
      });
    }

    function _showSiteOverlay(callback) {
      // Check if there is already an overlay on the page
      if (!$('#site-overlay').length) {
        $body.append($siteOverlay);
      }

      // Check if it's already active, if not animate showing it
      if (!$('#site-overlay').is('.-active')) {
        // Fade in the overlay
        $('#site-overlay').velocity(
          { opacity: 1 }, {
          display: "block",
            // on complete, fade in the lightbox
            complete: function() {
              $('#site-overlay').addClass('-active');
              if(typeof callback !== 'undefined') {
                callback();
              }
            }
        });
      }
    }

    function _hideSiteOverlay(callback) {
      if (!$('#site-overlay').length) {
        return;
      }

      $('#site-overlay').velocity(
        { opacity: 0 }, {
        display: "none",
        complete: function() {
          $('#site-overlay').removeClass('-active');
          if(typeof callback !== 'undefined') {
            callback();
          }
        }
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

    function _initInviewElements() {
      $('.animate-in').each(function() {
        var $elem = $(this);
        $elem.waypoint(function(direction) {
          $elem.addClass('in-view', direction === 'down');
        },{
          offset: '85%'
        });
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

      // Isotope Functionality
      var $filterGrid = $('.filter-grid').isotope({
        itemSelector: '.grid-item',
        layoutMode: 'fitRows',
        stagger: 25,
        percentPosition: true,
        hiddenStyle: {
          opacity: 0,
        },
        visibleStyle: {
          opacity: 1,
        }
      });

      // store filter for each group
      var filters = [];

      // change is-checked class on buttons
      $('.filter-group').on( 'click', 'button', function(event) {
        var $target = $(event.currentTarget);
        $target.toggleClass('is-checked');
        var isChecked = $target.hasClass('is-checked');
        var filter = $target.attr('data-filter');
        if (isChecked) {
          addFilter(filter);
        } else {
          removeFilter(filter);
        }
        // filter isotope
        // group filters together, inclusive
        $filterGrid.isotope({ filter: filters.join('') });
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
    }

    function _openFilters($filters) {
      var $container = $filters.find('.filters-container');
      $filters.addClass('-active');
      $body.addClass('filters-open');
      $container.velocity('slideDown', { duration: 250, easing: 'easeOutSine' });
    }

    function _closeFilters($filters) {
      var $container = $filters.find('.filters-container');
      $filters.removeClass('-active');
      $body.removeClass('filters-open');
      $container.velocity('slideUp', { duration: 250, easing: 'easeOutSine' });
    }

    function _initCarousels() {
      $('.header-carousel').flickity({
        prevNextButtons: false,
        fade: true,
        autoPlay: true,
        draggable: false,
        cellSelector: '.image'
      });
    }

    function _initTeamModal() {
      $('.team-member.principle').on('click', function(e) {
        var $target = $(e.target);
        if (!$target.is('a')) {
          _openTeamModal($(this));
        }
      });

      $(document).on('click', '#team-modal .modal-close, body.modal-open #site-overlay', function() {
        _closeTeamModal();
      });
    }

    function _openTeamModal($member) {
      if (!$('.team-modal').length) {
        $('body').append('<div id="team-modal" class="team-modal"><button class="modal-close"><svg aria-hidden="true" role="presentation"><use xlink:href="#icon-close-condensed"/></svg></button><div class="modal-content"><div class="member-meta"><div class="member-info"></div></div><div class="member-bio"></div></div></div>');
      }

      var $teamModal = $('#team-modal');

      // Clear Modal Content
      $teamModal.find('.member-meta').html('');
      $teamModal.find('.member-bio').html('');

      // Get Member Data
      var memberPhoto = $member.attr('data-photo');
      var memberInfo = $member.find('.member-info');
      var memberBio = $member.find('.member-bio').html();

      $teamModal.find('.member-meta').append('<div class="member-photo" style="background-image:url('+ memberPhoto +');"></div>');
      memberInfo.clone().appendTo('#team-modal .member-meta');
      $teamModal.find('.member-bio').html(memberBio);

      $body.addClass('modal-open');
      _showSiteOverlay();
      $teamModal.velocity('stop').velocity({
          translateX: [0, '100%'],
          translateZ: 0,
          skewX: [0, '-10deg'],
        }, {
          duration: 400,
          display: 'block',
          complete: function() {
            $teamModal.addClass('-active');
          }
        }
      )
      _disableScroll();
    }

    function _closeTeamModal() {
      $('#team-modal').removeClass('-active').velocity({
          translateX: '110%',
          translateZ: 0,
          skewX: ['10deg', [1,.92,.43,1.35], 0]
        }, {
          duration: 350,
          display: 'none',
          complete: function() {
            $('#team-modal').find('.member-bio').scrollTop(0);
          }
        }
      );
      _hideSiteOverlay();
      $body.removeClass('modal-open');
      _enableScroll();
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

// Fire Resize Functions