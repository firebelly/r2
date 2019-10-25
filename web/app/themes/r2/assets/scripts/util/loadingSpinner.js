// Loading Spinner

// Shared vars among modules
import appState from './appState';

let $body = $('body'),
    $loadingSpinner = '<div id="loading-spinner"><span></span><span></span><span></span></div>';

export let loadingTimer;

const loadingSpinner = {

  show($parent) {
     if (!$('#loading-spinner').length) {
      if(typeof $parent !== 'undefined' && $parent.length) {
        $parent.append($loadingSpinner);
      } else {
        $body.append($loadingSpinner);
      }
     }

    // Set a timeout to show the loading spinner if it takes too long to load
     loadingTimer = setTimeout(function() {
       $body.addClass('loading');
       $('#loading-spinner').velocity({ opacity: 1 }, { display: 'block' });
     }, 200);
  },

  hide() {
    clearTimeout(loadingTimer);
    if (!$('#loading-spinner').length) {
      return;
    }

    $('#loading-spinner').velocity({ opacity: 0 }, {
      display: 'none',
      complete: function() {
        $body.removeClass('loading');
      }
    });
  }

};

export default loadingSpinner