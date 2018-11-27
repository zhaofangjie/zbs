define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'room/config/index',
                    add_url: 'room/config/add',
                    edit_url: 'room/config/edit',
                    del_url: 'room/config/del',
                    multi_url: 'room/config/multi',
                    table: 'room_config',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'logoimage', title: __('Logoimage'), formatter: Table.api.formatter.image},
                        {field: 'icoimage', title: __('Icoimage'), formatter: Table.api.formatter.image},
                        {field: 'bgimage', title: __('Bgimage'), formatter: Table.api.formatter.image},
                        {field: 'ewmimage', title: __('Ewmimage'), formatter: Table.api.formatter.image},
                        {field: 'state', title: __('State'), searchList: {"0":__('State 0'),"1":__('State 1'),"2":__('State 2')}, formatter: Table.api.formatter.normal},
                        {field: 'pwd', title: __('Pwd')},
                        {field: 'regaudit', title: __('Regaudit'), searchList: {"0":__('Regaudit 0'),"1":__('Regaudit 1')}, formatter: Table.api.formatter.normal},
                        {field: 'msgblock', title: __('Msgblock'), searchList: {"0":__('Msgblock 0'),"1":__('Msgblock 1')}, formatter: Table.api.formatter.normal},
                        {field: 'msgaudit', title: __('Msgaudit'), searchList: {"0":__('Msgaudit 0'),"1":__('Msgaudit 1')}, formatter: Table.api.formatter.normal},
                        {field: 'msglog', title: __('Msglog'), searchList: {"0":__('Msglog 0'),"1":__('Msglog 1')}, formatter: Table.api.formatter.normal},
                        {field: 'logintip', title: __('Logintip'), searchList: {"0":__('Logintip 0'),"1":__('Logintip 1')}, formatter: Table.api.formatter.normal},
                        {field: 'loginguest', title: __('Loginguest'), searchList: {"0":__('Loginguest 0'),"1":__('Loginguest 1')}, formatter: Table.api.formatter.normal},
                        {field: 'tserver', title: __('Tserver')},
                        {field: 'vserver', title: __('Vserver')},
                        {field: 'livetype', title: __('Livetype'), searchList: {"0":__('Livetype 0'),"1":__('Livetype 1')}, formatter: Table.api.formatter.normal},
                        {field: 'online', title: __('Online')},
                        {field: 'defvideo', title: __('Defvideo')},
                        {field: 'rebots', title: __('Rebots')},
                        {field: 'defkf', title: __('Defkf')},
                        {field: 'defvideonick', title: __('Defvideonick')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});