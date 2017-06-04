;(function($, page){
  $(document).ready(function(){

    /* Header Parallax */
    var navHeader  = $('.site-header .header-top');
    var lastScroll = 0;
    var viewport   = $(page).width();

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


    /* Languages menu */
    $('.main-navigation > [class$="-container"] .menu')
      .append( $('.main-navigation .open-search') );

    $('.main-navigation > [class$="-container"] .menu')
      .append( $('.main-navigation .lang-menu') );


    /* Burger menu */
    var hamburger = $('.hamburger');
    var menu      = $('.main-navigation');
    var menuItems = menu.find('.menu > .menu-item');
    var resolution= $(this).width();

    hamburger.click(function(){
      $(this).toggleClass('clicked');
      menu.slideToggle();
    });


    if( resolution < 768 ) {
      $('.menu-item-has-children > a').click(function(e){
        e.preventDefault();
      });

      menuItems.click(function(e){
        var _self = $(this);

        _self.find('.sub-menu').slideToggle(200);
        _self.siblings().find('.sub-menu').slideUp(200);

        setTimeout(function(){
          $('html,body').stop().animate({scrollTop: _self.offset().top},500);
        }, 300);
      });
    }


    /* scrollTop */
    if ( ($(window).height() + 100) < $(document).height() ) {
      $('#top-link-block').removeClass('hidden').affix({
        // how far to scroll down before link "slides" into view
        offset: {top:100}
      });
    }

    /* Nicescroll */
    $('.gallery-container').niceScroll();


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

    /* Gellery Match Height */
    $('.gallery-container .block-teaser').matchHeight();
  });
})(jQuery, window);
