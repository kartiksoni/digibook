(function($) {
  showSuccessToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Success',
      text: msg,
      showHideTransition: 'slide',
      icon: 'success',
      loader: false,
      position: 'top-center'
    })
  };
  showInfoToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Info',
      text: msg,
      showHideTransition: 'slide',
      icon: 'info',
      loader: false,
      loaderBg: '#46c35f',
      position: 'top-center'
    })
  };
  showWarningToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Warning',
      text: msg,
      showHideTransition: 'slide',
      icon: 'warning',
      loader: false,
      loaderBg: '#57c7d4',
      position: 'top-center'
    })
  };
  showDangerToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Danger',
      text: msg,
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      loader: false,
      position: 'top-center'
    })
  };
  showToastPosition = function(position) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Positioning',
      text: 'Specify the custom position object or use one of the predefined ones',
      position: String(position),
      icon: 'info',
      stack: false,
      loader: false,
      loaderBg: '#f96868'
    })
  }
  showToastInCustomPosition = function() {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Custom positioning',
      text: 'Specify the custom position object or use one of the predefined ones',
      icon: 'info',
      position: {
        left: 120,
        top: 120
      },
      stack: false,
      loader: false,
      loaderBg: '#f96868'
    })
  }
  resetToastPosition = function() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-center top-left top-center mid-center'); // to remove previous position class
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "center": ""
    }); //to remove previous position style
  }
})(jQuery);