$.fn.disabled = function() {
	return $(this).addClass('disabled').attr('disabled', 'disabled');
};

$.fn.enabled = function() {
	return $(this).removeClass('disabled').removeAttr('disabled');
};

$.fn.readonly = function() {
	return $(this).addClass('readonly').attr('readonly', 'readonly');
};

$.fn.writable = function() {
	return $(this).removeClass('readonly').removeAttr('readonly');
};
(function(document, $, todo) {
    let dbData;
    let $table = $('#todo-list');
    let tempRow = $('#template-row').html();
    let labels = {
        create: '新規登録',
        edit: '編集',
        delete: '削除',
        status: '状態変更',
    };

    function loadList() {
        $.ajax({
            type: 'get',
            url: '/api/list.php',
            dataType: 'json'
        }).done(function (json) {
            createTable(json.data)
            dbData = json.data;
        }).fail(function (res) {
            console.error(res);
        });
    }

    function createTable(records) {
        $table.find('tbody').html("");
        $.each(records, function(i, record) {
            let $base = $(tempRow);
            $base.attr("data-id", record.id);
            $base.find('.status').prop("checked", !!record.status);
            $base.find('.title').val(record.title);
            $table.find('tbody').append($base);
        });
        // 新規用
        let $base = $(tempRow);
        $base.attr("data-id", 0);
        $base.find('.fa-checkbox').remove();
        $base.find('.w-toggle').append('<i class="fa fa-plus">');
        $base.find('.btn').remove();
        $table.find('tbody').append($base);
        $base.find(".input").focus();
    }

    function _exec(type, data, reload) {
        reload = reload === undefined ? true : reload;
        $.ajax({
            url: '/api/' + type + '.php',
            type: 'POST',
            data: data,
            dataType: "json"
        }).done(function(json) {
            if (json.result) {
                if (json.message) alert(json.message);
                if (reload) loadList();
            }
            else {
                alert(json.message);
            }
        }).fail(function() {
            alert(labels[type] + "に失敗しました");
        });
    }

    function padding(str, char, num) {
        return str.toString().padStart(num, char)
    }
    function clock() {
        let date = new Date();
        $('#clock').html(padding(date.getHours(), "0", 2) + ':' + padding(date.getMinutes(), "0", 2) + ':' + padding(date.getSeconds(), "0", 2));
    }

    // イベント登録
    
    // 登録 or 編集
    $(document).on('change', '.input', function() {
        let id = $(this).parents(".todo").data("id");
        let type = id ? 'edit' : 'create';
        _exec(type, {
            id: id,
            title: $(this).val()
        });
    });

    // 削除
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).parents("tr").data("id");
        _exec('delete', {id: id});
    });

    // ステータス変更
    $(document).on('change', '.status', function() {
        let status = $(this).prop("checked") ? 1 : 0;
        let id = $(this).parents("tr").data("id");
        _exec('status', {
            id: id,
            status: status,
        }, false);
    });
    loadList();
    setInterval(function() {
        clock();
    }, 500);
    clock();
})(document, window.jQuery, window.todo = {});
