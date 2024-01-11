layui.use(['jquery', 'form', 'layer', 'drawer'], function () {
  var $ = layui.jquery;
  var form = layui.form;
  var layer = layui.layer;
  var drawer = layui.drawer;
  var table = layui.table;
  var treeTable = layui.treeTable;

  // 监听弹出层的打开
  $('body').on('click', '[data-open]', function () {
    let title = $(this).attr('data-title') ?? '';
    let url = $(this).attr('data-open') ?? '';
    let w = $(this).attr('data-width') ?? '';
    HuiAdminShow(title, url, w);
  });

  // 监听弹出层的全屏打开
  $('body').on('click', '[data-open-full]', function () {
    let title = $(this).attr('data-title') ?? '';
    let url = $(this).attr('data-open-full') ?? '';
    HuiAdminOpenFull(title, url);
  });

  // 监听提示框弹出
  $('body').on('click', '[data-confirm]', function () {
    let title = $(this).attr('data-title') ?? '';
    let url = $(this).attr('data-confirm') ?? '';
    let reload = $(this).attr('data-reload') ?? 0;
    let tableId = $(this).attr('data-table-id') ?? '';
    let tableType = $(this).attr('data-table-type') ?? 1;
    let data = $(this).attr('data-data') ?? "";
    HuiAdminConfirm(url, title, reload, data, tableId, tableType);
  });

  /**
   * 右侧抽屉方式打开弹框
   * @param title
   * @param url
   * @param w
   * @param reload
   * @returns {boolean}
   * @constructor
   */
  window.HuiAdminShow = function (title, url, w) {
    if (title == null || title == '') {
      title = false;
    }
    if (url == null || url == '') {
      layer.msg("url地址不能为空");
      return false;
    }
    if (w == null || w == '' || w === undefined) {
      var window_width = $(window).width();
      if (window_width < 700) {
        w = window_width;
      } else if (window_width >= 700 && window_width < 1200) {
        w = 700;
      } else {
        w = window_width * 0.55;
      }
    }

    drawer.open({
      legacy: false,
      title: [title, 'font-size:16px;color:#2d8cf0'],
      offset: 'r',
      area: w + 'px',
      closeBtn: 1,
      url: url,
      end: function () {
        form.render('select');
      }
    });
  }
  /*满屏（全屏）打开窗口*/
  window.HuiAdminOpenFull = function (title, url, reload = 0) {
    var index = layer.open({
      type: 1,
      title: title,
      content: url,
      skin: 'skin-layer',
      area: ['96%', '96%'],
      closeBtn: 11,
      end: function () {

      }
    });
    //layer.full(index);
  }

  /**
   * 弹出层询问框提示
   * @param url   访问的url地址
   * @param msg   询问提示词
   * @param reload    执行后是否刷新：1=刷新
   * @param data    传递的数据
   * @param tableId   要刷新的数据表格id，reload=1生效
   * @param tableType   要刷新的数据表格类型（1=普通数据表格，2=树形表格），reload=1生效
   * @returns {boolean}
   * @constructor
   */
  window.HuiAdminConfirm = function (url, msg = '真的要这样操作么？', reload = 0, data = {}, tableId = '', tableType = 1) {
    if (url == null || url === '') {
      layer.msg("url地址不能为空");
      return false;
    }
    layer.confirm(msg, {skin: 'skin-layer-hui'}, function (index) {
      var loading = layer.load(0);
      $.post(url, data, function (res) {
        layer.close(loading);
        if (res.code === 1 || res.code === 200) {
          layer.msg(res.msg, {icon: 1, time: 1500}, function () {
            if (parseInt(reload) === 1) {
              //刷新数据表格
              if (window.hasOwnProperty('refreshTable') && (tableId != '' || tableId != null)) {
                refreshTable(tableId, tableType);
              }
            }
          });
        } else {
          layer.msg(res.msg, {icon: 2});
        }
      });
    });
  }

  /* 监听状态设置开关 */
  form.on('switch(switchStatus)', function (data) {
    var that = $(this);
    var primaryKey = that.attr('data-primary-key') ?? 'id';
    var url = that.attr('data-href');
    var field = that.attr('data-field') ?? 'status';
    if (!url) {
      layer.msg('请设置data-href参数');
      return false;
    }
    let postData = {};
    postData[primaryKey] = this.value;
    postData[field] = data.elem.checked ? 1 : 0;
    $.post(url, postData, function (res) {
      if (res.code !== 200) {
        return layui.popup.failure(res.msg, function () {
          that.trigger('click');
          form.render('checkbox');
        });
      }
      return layui.popup.success('操作成功');
    });
  });

  /**
   * 刷新数据表格
   * @param tableId 数据表格id
   * @param type  刷新类型：1=普通数据表格；2=树形表格
   */
  window.refreshTable = function (tableId, type = 1) {
    if (type === 1) {
      table.reload(tableId);
    } else {
      treeTable.reloadData(tableId);
    }
  }

});

