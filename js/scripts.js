$(document).ready(function () {
    $('.collapsible').collapsible(); // TODO delete if the more static variant of getting started is better
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown({hover: true, coverTrigger: false});

    $('#query-data-wrap-toggle').click(() => {
        $('#query-data-wrap-toggle').toggleClass('wrap-active');
        $('#query-data-wrap').toggleClass('wrap');
    });
});
