// Tooltips
function setTooltips(){
    $('a.a_tooltip').click(function(){
        return false;
    }).tooltip();
    $('a.yaca_tooltip').tooltip();
    return true;
}

//$('.popover-test').popover();
$(function () {

    setTooltips();

    var statusCheck;
    // Update domains
    $('#ref_button').click(function(){
        var url = $(this).data('href');
        document.location.href = url;
    }).tooltip();

    // Filter action
    $('.btn-filter').click(function(){
        var form_id = $(this).parent().attr('id');
        //        alert ('id: '+ form_id);
        //        $.ajax({
        //            async: true,
        //            type: 'POST',
        //            dataType: 'json',
        //            url: 'ajax.php?action=filterAPI',
        //            data: $('#' + form_id).serialize(true),
        //            success : function (r) {
        //                $('#id_status').html('');
        //            },
        //            error: function (r) {
        //                $('#id_status').html('');
        //            }
        //        });

        $('.nav-tabs').append($('<li><a href="#newTab" data-toggle="tab">Filter<span class="close closetab">x</span></a></li>'));
        $('.tab-content').append($('<div></div>').addClass('tab-pane').attr('id', 'newTab'));
        $('#domainTabs a[href="#newTab"]').tab('show');
        return false;
    }).tooltip();

    // Pagination
    $('div.pagination a').click(function(){
        var clicked = $(this);
        var table = $(this).parents().find('ul').attr('table');
        var table_body = '#' + table + '_tbody';
        var pagination = '#' + table + '_pagination';
        //        $(table_id).html('');
        $.ajax({
            async: true,
            type: 'GET',
            dataType: 'json',
            url: 'ajax.php',
            data: {
                "action" : 'getData',
                "page" : $(this).attr('page'),
                'table' : table,
                'sort' : $(pagination).attr('sort')
            },
            success : function (r) {
                $(table_body).html(r.html);
                setTooltips();
                $(pagination).children('li[class=active]').removeClass('active');
                $(pagination).children('ul').children('li[class=active]').removeClass('active');
                clicked.parent('li').addClass('active');
            }
        });
        return false;
    });

    // Sorting
    $('.sortable').click(function(){
        var sort = $(this).attr('sort');
        var table = $(this).parents().find('table').attr('table');
        var table_id = '#' + table + '_tbody';
        var sortdir = $('#' + table +"_pagination").attr('sortdir');
        $.ajax({
            async: true,
            type: 'GET',
            dataType: 'json',
            url: 'ajax.php',
            data: {
                "action" : 'getData',
                'table' : table,
                'sort' : sort,
                'sortdir' : sortdir
            },
            success : function (r) {
                $(table_id).html(r.html);
                setTooltips();
            }
        });
        return false;
    });

    // Updating yandex Catalog and Glue
    var updating = false;
    uYacaInterval = setInterval(updateYACA, 1000);
    function updateYACA(){
        if (updating==false){
            updating = true;
            $.ajax({
                async: true,
                type: 'GET',
                dataType: 'json',
                url: 'ajax.php',
                data: {
                    "action" : 'updateYACA'
                },
                success : function (r) {
                    if (r.count == 0){
                        clearInterval(uYacaInterval);
                    }
                    updating = false;
                },
                error : function(r){
                    alert ('Возникла ошибка updateYACA: ' + r.status_text);
                    clearInterval(uYacaInterval);
                }
            });

        }
    }

    // Check status
    function checkStatus(stopFlag){
        if (stopFlag == true){
            window.clearInterval(statusCheck)
            $('#close_but').button('reset');
        }
        $.ajax({
            async: true,
            type: 'GET',
            dataType: 'json',
            url: 'ajax.php',
            data: {
                "action" : 'getStatus'
            },
            success : function (r) {
                var html = '';
                $.each(r, function(index, value){
                    html += index + ':' + value + '<br />';
                });
                $('#id_status').html(html);
            }
        });
    }

});