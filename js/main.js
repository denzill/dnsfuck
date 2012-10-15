$(function () {
    //$('.popover-test').popover();
    var statusCheck;
    $('#ref_button').click(function(){
        $('#id_status').html('');
        statusCheck = window.setInterval(checkStatus,1000,false);
        $('#close_but').button('loading');
        $.ajax({
            async: true,
            type: 'GET',
            dataType: 'json',
            url: 'ajax.php',
            data: {
                "action" : 'updateDomains'
            },
            success : function (r) {
                checkStatus(true);
                $('#id_status').html('');
                $('#id_status').append(r.status_text);
            },
            error: function (r) {
                checkStatus(true);
                $('#id_status').html('');
                $('#id_status').append(r);
            }
        });
    }).tooltip();

    $('.btn-filter').click(function(){
        alert ('id: '+$(this).parent().attr('id'));


        $('.nav-tabs').append($('<li><a href="#newTab" data-toggle="tab">Filter</a></li>'));
        $('.tab-content').append($('<div></div>').addClass('tab-pane').attr('id', 'newTab'));
        $('#domainTabs a[href="#newTab"]').tab('show');
        return false;
    }).tooltip();


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

    $('div.pagination a').click(function(){
        var table = $(this).parents().find('ul').attr('table');
        var table_id = '#' + table + '_tbody';
        $(table_id).html('');
        $.ajax({
            async: true,
            type: 'GET',
            dataType: 'json',
            url: 'ajax.php',
            data: {
                "action" : 'getData',
                "page" : $(this).attr('page'),
                'table' : table,
                'sort' : $(table_id).attr('sort')
            },
            success : function (r) {
                $(table_id).html(r.html);
            }
        });
        return false;
    });

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
            }
        });
        return false;
    });

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

    var updating = false;
    uYacaInterval = setInterval(updateYACA, 1000);
});