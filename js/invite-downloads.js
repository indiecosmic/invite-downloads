/**
 * Created by Mattias on 2013-10-27.
 */
(function (inviteDownloads, $, undefined) {

    $("form#invite_downloads_form").submit(function (event) {
        $('#loading').css('visibility', 'visible');
        $("#invite_downloads_form .error-message").hide();

        var $form = $(event.target);
        var data = $form.serialize();
        var url = $form.attr('action');
        var method = $form.attr('method');
        $.ajax({
            type: method,
            url: url,
            data: data
        })
            .done(function (data) {
                $('#loading').css('visibility', 'hidden');
                if (data.success == 'true') {
                    $(".invite_downloads_inner").hide('fast', function () {
                        $(".invite_downloads_inner").html(data.content);
                        $(".invite_downloads_inner").show('slow');
                    });
                } else {
                    $("#invite_downloads_form .error-message").text(data.error).show();
                }
            });

        event.preventDefault();
    });

}(window.inviteDownloads = window.inviteDownloads || {}, jQuery));