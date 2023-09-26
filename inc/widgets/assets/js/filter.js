jQuery(document).ready(function () {

    let $list = jQuery('.filter_height');

    $list.each(function () {
        let $item = jQuery(this).find('label'),
            $item_target = $item.filter(function () {
                return jQuery(this).index() > 9
            });

        let $link = jQuery('<span class="show">Показать всё</span>').click(function () {
            $item_target.animate({height: 'toggle'});
            jQuery(this).remove();
            return false;
        });

        $item_target.hide().eq(0).before($link);
    });


    function getPathFromUrl() {
        var url = window.location.href;

        if (url.indexOf("?") != -1)
            url = url.split("?")[0];
        return url;
    }

    jQuery("#reset").click(function () {
        window.location.href = getPathFromUrl();
    });
});