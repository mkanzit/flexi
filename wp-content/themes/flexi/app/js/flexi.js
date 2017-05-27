;(function($, page){
  $(document).ready(function(){

    /* Header Parallax */
    var navHeader  = $('.site-header .header-top');
    var lastScroll = 0;

    $(window).on('load resize orientationchange', function(){
      var viewport = $(this).width();

      if( viewport > 1024 ){
        $(page).scroll(function(){
        var pageScroll = $(this).scrollTop();

        if (pageScroll > lastScroll){
            navHeader.addClass('off-canvas');
        } else {
            navHeader.removeClass('off-canvas');
        }
        lastScroll = pageScroll;
      });
      }
    });

    /* Search form */
    var searchArea = $('.search-area');
    var showSearch = $('.open-search');
    var hideSearch = $('.close-search');

    showSearch.click(function(){
      searchArea.fadeIn();
    });

    hideSearch.click(function(){
      searchArea.fadeOut();
      searchArea.find('.search-field').val('');
    });

    $('.main-navigation .menu-top-menu-container .menu')
      .append( $('.main-navigation .open-search') );

    $('.main-navigation .menu-top-menu-container .menu')
      .append( $('.main-navigation .lang-menu') );


    /* Burger menu */
    var hamburger = $('.hamburger');
    var menu      = $('.main-navigation');
    var menuItems = menu.find('.menu-top-menu-container > .menu > .menu-item');

    hamburger.click(function(){
      $(this).toggleClass('clicked');
      menu.slideToggle();
    });

    $(window).on('load resize orientationchange', function(){
      var viewport = $(this).width();

      if( viewport < 768 ) {
        menuItems.click(function(e){
          var _self = $(this);

          if( _self.hasClass('menu-item-has-children') ) {
            e.preventDefault();
          }

          _self.find('.sub-menu').slideToggle(200);
          _self.siblings().find('.sub-menu').slideUp(200);

          setTimeout(function(){
            $('html,body').stop().animate({scrollTop: _self.offset().top},500);
          }, 300);
        });
      }
    });



    /* scrollTop */
    if ( ($(window).height() + 100) < $(document).height() ) {
      $('#top-link-block').removeClass('hidden').affix({
        // how far to scroll down before link "slides" into view
        offset: {top:100}
      });
    }

    /* Nicescroll */
    var scrollable = $('.product-gallery-container');
    scrollable.niceScroll();


    /* Form validation */
    $('form-validate').validate({
      ignore: [],
      rules: {
        name: "required",
        email: {
          required: true,
          email: true
        }
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass(errorClass).removeClass(validClass);
        $(element.form).find("label[for=" + element.id + "]")
          .addClass(errorClass);
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass(errorClass).addClass(validClass);
        $(element.form).find("label[for=" + element.id + "]")
          .removeClass(errorClass);
      },
      errorPlacement: function(error, elem){},
      submitHandler: function(form) {
        // do other things for a valid form
        form.submit();
      },
      errorClass: 'invalid',
      validClass: 'valid',
      messages: {}
    });
  });
})(jQuery, window);
