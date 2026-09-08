$(document).ready(function () {

    $('.menu-link').on('click', function (e) {
        let href = $(this).attr('href');


        if (href && href !== '#') {

            window.location.href = href;
        }
    });


    let currentUrl = window.location.href;
    $('.menu-link').each(function () {
        if (this.href === currentUrl) {
            $('.menu-link').removeClass('active');
            $(this).addClass('active');
        }
    });
});