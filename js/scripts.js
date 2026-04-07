/** Initialize Materialize CSS components (sidenav and dropdown menus). */
$(document).ready(function () {
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown({hover: true, coverTrigger: false});
});
