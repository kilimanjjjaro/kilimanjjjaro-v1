
// FANCYBOX

$("[data-fancybox]").fancybox({
    buttons : [
        'fullScreen',
        'close'
    ],
    protect : true,
    lang : 'es',
        i18n : {
            'es' : {
                CLOSE       : 'Cerrar',
                NEXT        : 'Siguiente',
                PREV        : 'Anterior',
                ERROR       : 'La solicitud no pudo ser cargada, <br/> Por favor, intente más tarde.',
                PLAY_START  : 'Iniciar presentación',
                PLAY_STOP   : 'Detener presentación',
                FULL_SCREEN : 'Pantalla completa',
                THUMBS      : 'Miniaturas'
            }
    }

});


// IMAGE HOVER

(function($) {
    'use strict';
    $(document).ready(function($) {
        var imageFormatExcerptEl = $('main div.image-header, main div.image-footer');
        imageFormatExcerptEl.on('mouseenter', function() {
            var $this = $(this);
            $this.parent().prev().addClass('hover');
        });
        imageFormatExcerptEl.on('mouseleave', function(){
            var $this = $(this);
            $this.parent().prev().removeClass('hover');
        });
    })
}(jQuery));


// TOGGLE MENU

function openNav() {
    document.getElementById("toggle-menu").style.width = "100%";
}

function closeNav() {
    document.getElementById("toggle-menu").style.width = "0%";
}


// PRELOADER

$(window).on('load', function() {
    $('#status').fadeOut();
    $('#preloader').delay(350).fadeOut(1200);
    $('body').delay(350).css({'overflow':'visible'});
})


// BACK TO TOP

if ($('.back-to-top').length) {
    var scrollTrigger = 1000, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('.back-to-top').addClass('show');
            } else {
                $('.back-to-top').removeClass('show');
            }
        };
    backToTop();
    $(window).on('scroll', function () {
        backToTop();
    });
    $('.back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 800);
    });
}