var path = window.location.href; // Just grabbing a handy reference to it
$('div a').each(function() {
    if (this.href === path) {
        $(this).addClass('active');
    }
});