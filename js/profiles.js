const PROFILE_API_URL = "/wp-json/wawp/v1/profiles/";
(function($) {
    var pagination = wawp_pagination_toggle.pagination;
    var dir_selector = pagination ? '.wa-pagination, .paginationjs' : '.wa-contacts-items';
    if (wawp_pagination_toggle.search) {
        dir_selector += ', .wa-contacts-search';
    }
    $(document).ready(function() {
        $('.wa-profile-link').click(function () {
            // make shortcode div and show that
            let user_id = $(this).attr('data-user-id');
            toggleDirectory();
            generateProfileShortcode(user_id);
        });
    });

    function generateProfileShortcode(userID) {
        document.body.style.cursor = 'wait';
        let div = $('<div/>', {
            class: 'wa-profile-container'
        }).appendTo('.wa-contacts');

        // hide restricted fields?

        let hideResFields = $('#hide_restricted_fields').attr('data-hide-restricted-fields');

        // console.log(hideResFields);

        // get fields from html
        let field_string = '';
        $('.profile-field').each(function() {
            let field_name = $(this).attr('data-name');
            field_string += ('&fields[]=' + encodeURI(field_name));
        });

        let url = PROFILE_API_URL + '?id=' + userID + '&hideResFields=' + hideResFields + field_string;
        console.log(url);
        fetch(url)
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
