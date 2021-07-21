(function($) {
    let contact_obj = $('.wa-contact');
    let contacts = Object.entries(contact_obj);

    console.log('page size from php: ', wawp_memdir_page_size.page_size);
    // console.log(contacts);
    $(document).ready(function() {
        $('.wa-contacts-items').hide();
        $('.wa-contacts').pagination({
            dataSource: contacts,
            pageSize: wawp_memdir_page_size.page_size,
            callback: function(data,pagination) {
                console.log(data);
                var html = template(data);
                console.log(html);
                $('.wa-pagination').html(html);
            }
        });
    });

    function template(data) {
        var html = $("<div></div>");
        html.addClass('wa-pagination page');
        $.each(data, function(index, item) {
            // console.log(item[1]);
            // html += item[1];
            html.append(item[1]);
        });
        return html;
    }

})(jQuery);
