$(document).ready(function() {
    function toggleDropdown() {
        if ($(window).width() < 640) { // Tailwind's sm breakpoint
            $('.dropdown-button').off('click').on('click', function() {
                $(this).next('.dropdown-menu').toggle();
            });
        } else {
            $('.dropdown-menu').hide();
        }
    }

    toggleDropdown();
    $(window).resize(toggleDropdown);
});