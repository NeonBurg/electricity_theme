var h_height = 200;
var menu_height = 50;
var top_offset;

function checkScroll() {
    var top = $(this).scrollTop();
    var elem = $('.menu-container');
    //console.log('top = ' + top);

    if(top >= h_height-menu_height) {
        elem.css('top', top_offset);
    }
    else {
        elem.css('top', h_height-top+top_offset);
    }
}

$(function() {
    $(window).scroll(function() {
        checkScroll();
    })
});

$(document).ready(function() {
    top_offset = $('.page').offset().top;
    checkScroll();
});