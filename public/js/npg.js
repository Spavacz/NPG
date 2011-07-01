$(function() {
    /* modal z opisem produktu */
    $('.list-show-description').click(function(){
        $.getJSON(this.href, function(data) {
            var modal = $('<div id="modal-dialog" style="hidden">' + data.description + '</div>');
            modal.dialog({
                title: data.address,
                autoOpen: false,
                height: 150,
                width: 530,
                modal: true,
                close: function() {
                    $(this).dialog('destroy');
                    $(this).remove();
                }
            });
            modal.dialog('open');
        });
        return false;
    });

    /* powiekszanie miniatur w widoku produktu */
    $('.product-image-mini').click(function(){
        $('.product-image-zoom').attr('src', this.href);
        return false;
    });

    // galeria produktu
    $("a[rel^='prettyPhoto']").prettyPhoto({theme: 'facebook',slideshow:5000, autoplay_slideshow:true});
    
});