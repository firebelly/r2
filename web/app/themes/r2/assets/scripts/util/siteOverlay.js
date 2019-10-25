// Site Overlay

// Dependencies
import Velocity from 'velocity-animate';

// Shared vars among modules
import appState from './appState';

let $body = $('body'),
    $siteOverlay = '<div id="site-overlay"></div>';

const siteOverlay = {

  // Show site overlay
  show(callback) {
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
          complete: function() {
            $('#site-overlay').addClass('-active');
            // on complete, run optional callback
            if(typeof callback !== 'undefined') {
              callback();
            }
          }
      });
    }
  },

  hide(callback) {
    // Check if the overlay is there
    if (!$('#site-overlay').length) {
      return;
    }

    $('#site-overlay').velocity(
      { opacity: 0 }, {
      display: "none",
      complete: function() {
        $('#site-overlay').removeClass('-active');
        // on complete, run optional callback
        if(typeof callback !== 'undefined') {
          callback();
        }
      }
    });
  }

};

export default siteOverlay