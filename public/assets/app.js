
(function($) {
  "use strict"; // Start of use strict
  AOS.init();

  $('.hub-slider-ingredient-slides ul').hubSlider({
    selector: $('li'),
    button: {
        next: $('.hub-slider-ingredient-arrow_next'),
        prev: $('.hub-slider-ingredient-arrow_prev')
    },
    transition: '0.7s',
    startOffset: 30,
    auto: false,
    opacity: 1,
    time: 300 // secondly
  });

  $('.hub-slider-testimoni ul').hubSlider({
      selector: $('li'),
      button: {
          next: $('.hub-slider-testimoni-arrow_next'),
          prev: $('.hub-slider-testimoni-arrow_prev')
      },
      transition: '0.7s',
      startOffset: 30,
      auto: false,
      opacity: 1,
      time: 300 // secondly
  });

  // Scroll to top button appear
  $(document).scroll(function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('.js-scroll-trigger').click(function() {
    $('.navbar-collapse').collapse('hide');
  });

  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 80
  });

  // Collapse Navbar
  var navbarCollapse = function() {
    if ($("#mainNav").offset().top > 100) {
      $("#mainNav").addClass("navbar-shrink");
    } else {
      $("#mainNav").removeClass("navbar-shrink");
    }
  };
  // Collapse now if page is not at top
  navbarCollapse();
  // Collapse the navbar when page is scrolled
  $(window).scroll(navbarCollapse);

})(jQuery); // End of use strict
