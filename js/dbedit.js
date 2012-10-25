$(function () {

    $('#btn_submit').click(function(){
        $('#result').html('ЖДИТЕ');
        $.ajax({
            async: true,
            type: 'POST',
            dataType: 'json',
            url: 'ajax.php?action=execSQL',
            data: {
                "sql" : $('#sql_text').val()
            },
            success : function (r) {
                $('#result').html(r);
            },
            error: function (r) {
                $('#result').html(r.status_text);
            }
        });
        return false;
    });
});

