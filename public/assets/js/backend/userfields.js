define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'userfields/index',
                    add_url: 'userfields/add',
                    edit_url: 'userfields/edit',
                    del_url: 'userfields/del',
                    multi_url: 'userfields/multi',
                    table: 'userfields',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'uid',
                sortName: 'uid',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'uid', title: __('Uid')},
                        {field: 'realname', title: __('Realname')},
                        {field: 'city', title: __('City')},
                        {field: 'qq', title: __('Qq')},
                        {field: 'tuser', title: __('Tuser')},
                        {field: 'kuser', title: __('Kuser')},
                        {field: 'onlinetime', title: __('Onlinetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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