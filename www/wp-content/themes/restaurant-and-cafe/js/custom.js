jQuery(document).ready(function($){

    // testimonial slider
    $(document).ready(function() {

    /** Variables from Customizer for testimonial settings */

    if( restaurant_and_cafe_data.pager == '1' ){
        var testimonial_control = true;
    }else{
        testimonial_control = false;
    }
    
    $("#testimonial-slider").lightSlider({
        item: 1,
        mode: restaurant_and_cafe_data.animation, 
        speed: restaurant_and_cafe_data.speed, //ms'
        auto: restaurant_and_cafe_data.auto,
        pause: restaurant_and_cafe_data.a_speed,
        controls: testimonial_control,
 
        thumbItem:7,
        gallery: true,
        galleryMargin: 40,
        thumbMargin: 35,
        enableDrag:false,
 
        responsive : [
            {
                breakpoint:991,
                settings: {
                    thumbItem:5,
                    thumbMargin: 25
                  }
            },
            {
                breakpoint:600,
                settings: {
                    thumbItem:3,
                    thumbMargin: 5
                  }
            }
        ],
 
        });
    });


    // scrolling down
    $(document).ready(function(){
        $(".btn-scroll-down > span").click(function() {
            $('html, body').animate({
                scrollTop: $("#next_section").offset().top
            }, 1000);
        });
    });

    // responsive menu
    $('#responsive-menu-button').sidr({
          name: 'sidr-main',
          source: '#site-navigation',
          side: 'right'
    });


    $('.tabs li').click(function(){
           id = $(this).attr('id').split('-').pop();
           $('.tab-content').hide();
           $('#content-'+id).show();
           $('.tabs li').removeClass('active');
           $(this).addClass('active');
           //console.log(id); 
    });


});