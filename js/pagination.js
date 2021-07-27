(function($) {
    let contact_obj = $('.wa-contact');
    let contacts = Object.entries(contact_obj);

    // console.log(contacts);
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
        console.log('search: ', search);
    });

    function template(data) {
        var html = $("<div></div>");
        html.addClass('wa-pagination page');
        $.each(data, function(index, item) {
            html.append(item[1]);
        });
        return html;
    }

    function __showSearch() {
        console.log('showSearch()');
        $('.wa-pagination').hide();
        $('.paginationjs').hide();
        $('wa-contacts-items').show();
    }

    // console.log('search: ', search);

})(jQuery);

function showSearch() {
    __showSearch();
}
