# HuiCMF 2.0 后台管理系统

1. 默认使用单页面（page）非iframe开发
2. 后台UI默认使用Pear Admin Layui 4.0版本
3. Layui默认使用最新版：2.9.3版本

## 后台开发说明

### 1、模板

> 注意：
> 
> 在ui模板中不要出现相同的id，不要使用window.****=function()；


1、在页面中使用，一定要区分id值，不能和其他页面有重复的id值

例如数据表格页：

```html

<table class="layui-hide" id="ruleTable" lay-filter="ruleTable"></table>
```

```javascript
var ruleInit = treeTable.render({
  elem: '#ruleTable',

});
```

```html

<script type="text/html" id="optionTpl">
    <a class="layui-btn layui-btn-xs layui-btn-normal" data-open="/app/admin/rule/add?id={{d.id}}"
       data-title="添加下级" data-reload="1" permission="app.admin.rule.add">添加下级</a>
    <a class="layui-btn layui-btn-xs layui-btn-success" data-open="/app/admin/rule/edit?id={{d.id}}"
       data-title="编辑" data-reload="1">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" data-confirm="/app/admin/rule/delete"
       data-data="id={{d.id}}" data-title="确定要删除吗？" data-reload="1">删除</a>
</script>
```

```javascript
[
  {fixed: "right", title: "操作", width: 190, align: "center", toolbar: "#optionTpl"}
]
```

以上代码中：js中的 `elem`值和html中的`id`值一致，且其值不能和其他页面中有相同的id值，否则页面无法展示会显示空白。
最好的命名规则为：当前规则名称+Table，例如上面的：ruleTable，意为：角色表格。

