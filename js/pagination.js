(function($) {
    let contact_obj = $('.wa-contact');
    let contacts = Object.entries(contact_obj);

    $(document).ready(function() {
        $('.wa-contacts-items').hide();
        $('.wa-contacts').pagination({
            dataSource: contacts,
            pageSize: wawp_memdir_page_size.page_size,
            callback: function(data,pagination) {
                var html = template(data);
                $('.wa-pagination').html(html);
            }
        });
    });

    function template(data) {
        var html = $("<div></div>");
        html.addClass('wa-pagination page');
        $.each(data, function(index, item) {
            html.append(item[1]);
        });
        return html;
    }

})(jQuery);
