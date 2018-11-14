function rotate(){
  setTimeout(function(){
    $('.carousel').carousel('next');
    rotate();
  }, 10000);
}
(function($){
  $(function(){

    $('.button-collapse').sideNav();
    $('.carousel.carousel-slider').carousel({fullWidth: true});
    rotate();
  }); // end of document ready
})(jQuery); // end of jQuery name space
