export default {
  init() {
    // Set up Global Vars
    const $siteHeader = $('#site-header');
    const $siteNav = $('#site-nav');

    // JavaScript to be fired on all pages
    _initActiveToggle();
    _initSiteNav();
    _initFormFunctions();
    _initInviewElements();

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
      $('body').attr('data-st', st);
      $('body').addClass('no-scroll');
      $('body').css('top', -st);
    }

    function _enableScroll() {
      $('body').removeClass('no-scroll');
      $('body').css('top', '');
      $(window).scrollTop($('body').attr('data-st'));
      $('body').attr('data-st', '');
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
      $('#site-header').append('<button id="nav-toggle" class="nav-toggle" aria-hidden="true" data-active-toggle="#site-nav"></button>');

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
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
