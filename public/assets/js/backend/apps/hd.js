define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'apps/hd/index',
                    add_url: 'apps/hd/add',
                    edit_url: 'apps/hd/edit',
                    del_url: 'apps/hd/del',
                    multi_url: 'apps/hd/multi',
                    table: 'apps_hd',
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
                        {field: 'ktime', title: __('Ktime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'ptime', title: __('Ptime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'sp', title: __('Sp')},
                        {field: 'kcj', title: __('Kcj')},
                        {field: 'cw', title: __('Cw')},
                        {field: 'zsj', title: __('Zsj')},
                        {field: 'zyj', title: __('Zyj')},
                        {field: 'pcj', title: __('Pcj')},
                        {field: 'lx', title: __('Lx')},
                        {field: 'yld', title: '赢利点'},
                        {field: 'z', title: __('Z')},
                        {field: 'username', title: __('Username')},
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