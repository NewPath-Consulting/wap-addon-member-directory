const PROFILE_API_URL = "/wp-json/wawp/v1/profiles/";
(function($) {
    var pagination = wawp_pagination_toggle.pagination;
    var dir_selector = pagination ? '.wa-pagination, .paginationjs' : '.wa-contacts-items';
    $(document).ready(function() {
        $('.wa-profile-link').click(function () {
            // make shortcode div and show that
            let user_id = $(this).attr('data-user-id');
            console.log(user_id);
            toggleDirectory();
            generateProfileShortcode(user_id);
        });
    });

    function generateProfileShortcode(userID) {
        document.body.style.cursor = 'wait';
        let div = $('<div/>', {
            class: 'wa-profile-container'
        }).appendTo('.wa-contacts');

        fetch(PROFILE_API_URL + userID)
        .then((resp) => {
            return resp.text();
        })
        .then((data) => {
            var result = JSON.parse(data);
            renderBackArrow();
            div.append(result);
            document.body.style.cursor = "default";
        })
    }

    function renderBackArrow() {
        $('<div/>', {
            class: 'back-arrow',
            text: '← Back to members'
        }).appendTo('.wa-profile-container');
        $('.back-arrow').click(function() {
            toggleDirectory();
        });
    }

    function toggleDirectory() {
        if ($(dir_selector).hasClass('hidden')) {
            $(dir_selector).removeClass('hidden');
            $('.wa-profile-container').remove()
        } else {
            $(dir_selector).addClass('hidden');
            $('.wa-profile-container').removeClass('hidden');
        }
    }
})(jQuery);