$(document).ready(function () {
    $('.fixed-action-btn').floatingActionButton({
        hoverEnabled: false,
        direction: 'bottom',
    });
});

$(".default-fixed-action-btn .btn-floating btn-large").on("click", function () {
    console.log(24242);
    $(this).css("opacity", 1);
});