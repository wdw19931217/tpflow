<?php /*a:1:{s:58:"D:\tpflow\application\index\view\flowdesign\attribute.html";i:1522858344;}*/ ?>

<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="leipi.org">
    
    <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="/static/work/css/bootstrap/css/bootstrap-ie6.css?">
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="/static/work/css/bootstrap/css/ie.css?">
    <![endif]-->
    <link href="/static/work/css/site.css?" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/static/work/js/flowdesign/flowdesign.css"/>
<link rel="stylesheet" type="text/css" href="/static/work/js/jquery.multiselect2side/css/jquery.multiselect2side.css"/>
<link href="/static/work/css/bootstrap/css/bootstrap.css?2025" rel="stylesheet" type="text/css" />
<ul class="nav nav-tabs" id="attributeTab">
  <li <?php if($op == 'basic'): ?> class="active"<?php endif; ?>><a href="#attrBasic">常规</a></li>
  <li><a href="#attrPower">权限</a></li>
  <li><a href="#attrOperate">操作</a></li>
  <li <?php if($op == 'judge'): ?> class="active"<?php endif; ?> id="tab_attrJudge"><a href="#attrJudge">转出条件</a></li>
  <li <?php if($op == 'style'): ?> class="active"<?php endif; ?>><a href="#attrStyle">样式</a></li>
</ul>

<form class="form-horizontal" target="hiddeniframe" method="post" id="flow_attribute" name="flow_attribute" action="<?php echo url('save_attribute'); ?>">
<input type="hidden" name="flow_id" value="<?php echo htmlentities($one['flow_id']); ?>"/>
<input type="hidden" name="process_id" value="<?php echo htmlentities($one['id']); ?>"/>
  <div class="tab-content">
    <div class="tab-pane <?php if($op == 'basic'): ?>active<?php endif; ?>" id="attrBasic">

          <div class="control-group">
            <label class="control-label" for="process_name">步骤名称</label>
            <div class="controls">
              <input type="text" id="process_name" placeholder="步骤名称" name="process_name" value="<?php echo htmlentities($one['process_name']); ?>">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">步骤类型</label>
            <div class="controls">
              <label class="radio inline">
                <input type="radio" name="process_type" value="is_step" <?php if($one['process_type'] == 'is_step'): ?>checked="checked"<?php endif; ?>>正常步骤
              </label>
              <label class="radio inline">
                <input type="radio" name="process_type" value="is_one" <?php if($one['process_type'] == 'is_one'): ?>checked="checked"<?php endif; ?>>设为第一步
              </label>
            </div>
          </div>
		   <div id="current_flow">
          <div class="offset1">
          <!--未按顺序的bug 2012-12-12-->
            <select multiple="multiple" size="6" name="process_to[]" id="process_multiple" >
            <?php if(is_array($process_to_list) || $process_to_list instanceof \think\Collection || $process_to_list instanceof \think\Paginator): $i = 0; $__LIST__ = $process_to_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['id'] != $one['id']): ?>
                    <option value="<?php echo htmlentities($vo['id']); ?>" <?php if(in_array($vo['id'],$one['process_to'])): ?>selected="selected"<?php endif; ?>><?php echo htmlentities($vo['process_name']); ?></option>
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </div>
        </div><!-- current_flow end -->
<hr/>
 </div>
    <div class="tab-pane" id="attrPower">
        <div class="control-group">
            <label class="control-label" >办理人员</label>
            <div class="controls">
              <select name="auto_person" id="auto_person_id">
                <option value="0">请选择办理人员或者角色</option>
                <option value="5" <?php if($one['auto_person'] == 5): ?>selected="selected"<?php endif; ?>>指定角色</option>
                <option value="4" <?php if($one['auto_person'] == 4): ?>selected="selected"<?php endif; ?>>指定人员</option>
              </select>
              <span class="help-inline">*</span>
            </div>
            <div class="controls <?php if($one['auto_person'] == 0): ?>hide<?php endif; ?>" id="auto_unlock_id" >
              <label class="checkbox">
                <input type="checkbox" name="auto_unlock" value="1" <?php if($one['auto_unlock'] == 1): ?>checked="checked"<?php endif; ?>>允许更改
              </label>
            </div>

            <div id="auto_person_4" <?php if($one['auto_person'] != 4): ?>class="hide"<?php endif; ?>>
              <div class="control-group">
                <label class="control-label">办理人</label>
                <div class="controls">
                    <input type="hidden" name="auto_sponsor_ids" id="auto_sponsor_ids" value="<?php echo htmlentities($one['auto_sponsor_ids']); ?>">
                    <input class="input-xlarge" readonly="readonly" type="text" placeholder="指定办理人" name="auto_sponsor_text" id="auto_sponsor_text" value="<?php echo isset($one['auto_sponsor_text']) ? htmlentities($one['auto_sponsor_text']) : ''; ?>"> <a href="javascript:void(0);" class="btn" onclick="superDialog('<?php echo url('super_dialog',['op'=>'user']); ?>','auto_sponsor_text','auto_sponsor_ids');">选择</a>
                </div> 
              </div>
            </div>
            <div id="auto_person_5" <?php if($one['auto_person'] != 5): ?>class="hide"<?php endif; ?>>
              <div class="control-group">
                <label class="control-label">指定角色</label>
                <div class="controls">
                    <input type="hidden" name="auto_role_ids" id="auto_role_value" value="<?php echo isset($one['auto_role']) ? htmlentities($one['auto_role']) : ''; ?>">
                    <input class="input-xlarge" readonly="readonly" type="text" placeholder="指定角色" name="auto_role_text" id="auto_role_text" value="<?php echo isset($one['auto_role_text']) ? htmlentities($one['auto_role_text']) : ''; ?>"> <a href="javascript:void(0);" class="btn" onclick="superDialog('<?php echo url('super_dialog',['op'=>'role']); ?>','auto_role_text','auto_role_value');">选择</a>
                </div> 
              </div>
            </div>
          </div>
    </div><!-- attrPower end -->
    <div class="tab-pane" id="attrOperate">
        <div class="control-group">
          <label class="control-label" >会签方式</label>
          <div class="controls">
            <select name="is_sing" >
              <option value="1" <?php if($one['is_sing'] == 1): ?>selected="selected"<?php endif; ?>>允许会签</option>
              <option value="2" <?php if($one['is_sing'] == 2): ?>selected="selected"<?php endif; ?>>禁止会签</option>
            </select>
          </div>
        </div>
	<hr/>

        <div class="control-group">
          <label class="control-label" >回退方式</label>
          <div class="controls">
            <select name="is_back" >
              <option value="1" <?php if($one['is_back'] == 1): ?>selected="selected"<?php endif; ?>>不允许</option>
              <option value="2" <?php if($one['is_back'] == 2): ?>selected="selected"<?php endif; ?>>允许回退</option>
            </select>
          </div>
        </div>


    </div><!-- attrOperate end -->
    <div class="tab-pane  <?php if($op == 'judge'): ?>active<?php endif; ?>" id="attrJudge">

       
    <table class="table" >
      <thead>
        <tr>
          <th style="width:100px;">转出步骤</th>
          <th>转出条件设置</th>
        </tr>
      </thead>
      <tbody>

<!--模板-->
<tr id="tpl" class="hide">    
<td style="width: 100px;">@text</td>
<td>
    <table class="table table-condensed">
    <tbody>
      <tr>
        <td>
            <select id="field_@a" class="input-medium">
              <option value="">选择字段</option>
			  <?php if(is_array($from) || $from instanceof \think\Collection || $from instanceof \think\Paginator): $i = 0; $__LIST__ = $from;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
			  <option value="<?php echo htmlentities($key); ?>"><?php echo htmlentities($v); ?></option>
			  <?php endforeach; endif; else: echo "" ;endif; ?>
              <!-- 表单字段 start -->
             
              <!-- 表单字段 end -->  
            </select>
            <select id="condition_@a" class="input-small">
				<option value="=">等于</option>
				<option value="&lt;&gt;">不等于</option>
				<option value="&gt;">大于</option>
				<option value="&lt;">小于</option>
				<option value="&gt;=">大于等于</option>
				<option value="&lt;=">小于等于</option>
				<option value="include">包含</option>
				<option value="exclude">不包含</option>
            </select>
            <input type="text" id="item_value_@a" class="input-small">
            <select id="relation_@a" class="input-small">
        <option value="AND">与</option>
        <option value="OR">或者</option>
            </select>
        </td>
        <td>
            <div class="btn-group">
        <button type="button" class="btn btn-small" onclick="fnAddLeftParenthesis('@a')">（</button>
        <button type="button" class="btn btn-small" onclick="fnAddRightParenthesis('@a')">）</button>
        <button type="button" onclick="fnAddConditions('@a')" class="btn btn-small">新增</button>
            </div>
        </td>
       </tr>
       <tr>
        <td>
            <select id="conList_@a" multiple="" style="width: 100%;height: 80px;"></select>
        </td>
        <td>
            <div class="btn-group">
        <button type="button" onclick="fnDelCon('@a')" class="btn btn-small">删行</button>
        <button type="button" onclick="fnClearCon('@a')" class="btn btn-small">清空</button>
            </div>
        </td>
      </tr>
      <tr>
        <td>
            <input id="process_in_desc_@a" type="text" name="process_in_desc_@a" style="width:98%;">
            <input name="process_in_set_@a" id="process_in_set_@a" type="hidden">
        </td>
        <td>
            <span class="xc1">不符合条件时的提示</span>
        </td>
      </tr>
    </tbody>
    </table>
</td>
</tr>


  </tbody>
  <tbody id="ctbody">

  </tbody>
</table>
<input type="hidden" name="process_condition" id="process_condition">
    </div><!-- attrJudge end -->
    <div class="tab-pane  <?php if($op == 'style'): ?>active<?php endif; ?>" id="attrStyle">

        <div class="control-group">
          <label class="control-label" for="process_name">尺寸</label>
          <div class="controls">
            <input type="text" class="input-small" name="style_width" id="style_width" placeholder="宽度PX" value="<?php echo htmlentities($one['style']['width']); ?>"> X <input type="text" class="input-small" name="style_height" id="style_height" placeholder="高度PX"  value="<?php echo htmlentities($one['style']['height']); ?>">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="process_name">字体颜色</label>
          <div class="controls">
            <input type="text" class="input-small" name="style_color" id="style_color" placeholder="#000000" value="<?php echo htmlentities($one['style']['color']); ?>">
            <div class="colors" org-bind="style_color">
                <ul>
                  <li class="Black active" org-data="#000" title="Black">1</li>
                  <li class="red" org-data="#d54e21" title="Red">2</li>
                  <li class="green" org-data="#78a300" title="Green">3</li>
                  <li class="blue" org-data="#0e76a8" title="Blue">4</li>
                  <li class="aero" org-data="#9cc2cb" title="Aero">5</li>
                  <li class="grey" org-data="#73716e" title="Grey">6</li>
                  <li class="orange" org-data="#f70" title="Orange">7</li>
                  <li class="yellow" org-data="#fc0" title="Yellow">8</li>
                  <li class="pink" org-data="#ff66b5" title="Pink">9</li>
                  <li class="purple" org-data="#6a5a8c" title="Purple">10</li>
                </ul>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="process_name"><span class="process-flag badge badge-inverse"><i class="icon-star-empty icon-white" id="style_icon_preview"></i></span> 图标</label>
          <div class="controls">
            <input type="text" class="input-medium" name="style_icon" id="style_icon" placeholder="icon" value="<?php echo htmlentities($one['style']['icon']); ?>">
            <div class="colors" org-bind="style_icon">
                <ul>
                  <li class="Black active" org-data="icon-star-empty" title="Black"><i class="icon-star-empty icon-white"></i></li>
                  <li class="red" org-data="icon-ok" title="Red"><i class="icon-ok icon-white"></i></li>
                  <li class="green" org-data="icon-remove" title="Green"><i class="icon-remove icon-white"></i></li>
                  <li class="blue" org-data="icon-refresh" title="Blue"><i class="icon-refresh icon-white"></i></li>
                  <li class="aero" org-data="icon-plane" title="Aero"><i class="icon-plane icon-white"></i></li>
                  <li class="grey" org-data="icon-play" title="Grey"><i class="icon-play icon-white"></i></li>
                  <li class="orange" org-data="icon-heart" title="Orange"><i class="icon-heart icon-white"></i></li>
                  <li class="yellow" org-data="icon-random" title="Yellow"><i class="icon-random icon-white"></i></li>
                  <li class="pink" org-data="icon-home" title="Pink"><i class="icon-home icon-white"></i></li>
                  <li class="purple" org-data="icon-lock" title="Purple"><i class="icon-lock icon-white"></i></li>
                </ul>
            </div>
          </div>
        </div>
    </div><!-- attrStyle end -->
  </div>
<div>
  <hr/>
  <span class="pull-right">
      <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">取消</a>
      <button class="btn btn-primary" type="submit" id="attributeOK">确定保存</button>
  </span>
</div>
</form>
<iframe id="hiddeniframe" style="display: none;" name="hiddeniframe"></iframe>



<script type="text/javascript">

var _out_condition_data = [];
function callbackSuperDialog(selectValue){
     var aResult = selectValue.split('@leipi@');
     $('#'+window._viewField).val(aResult[0]);
     $('#'+window._hidField).val(aResult[1]);
    //document.getElementById(window._hidField).value = aResult[1];
    
}
/**
 * 弹出窗选择用户部门角色
 * showModalDialog 方式选择用户
 * URL 选择器地址
 * viewField 用来显示数据的ID
 * hidField 隐藏域数据ID
 * isOnly 是否只能选一条数据
 * dialogWidth * dialogHeight 弹出的窗口大小
 */
 
 
function superDialog(URL,viewField,hidField,isOnly,dialogWidth,dialogHeight)
{
    dialogWidth || (dialogWidth = 620)
    ,dialogHeight || (dialogHeight = 520)
    ,loc_x = 500
    ,loc_y = 40
    ,window._viewField = viewField
    ,window._hidField= hidField;
    // loc_x = document.body.scrollLeft+event.clientX-event.offsetX;
    //loc_y = document.body.scrollTop+event.clientY-event.offsetY;
    if(window.ActiveXObject){ //IE  
        var selectValue = window.showModalDialog(URL,self,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+dialogWidth+"px;dialogHeight:"+dialogHeight+"px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
        if(selectValue){
            callbackSuperDialog(selectValue);
        }
    }else{  //非IE 
        var selectValue = window.open(URL, 'newwindow','height='+dialogHeight+',width='+dialogWidth+',top='+loc_y+',left='+loc_x+',toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');  
    
    }
}
</script>

<script type="text/javascript" src="/static/work/js/jquery-1.7.2.min.js?"></script>
<script type="text/javascript" src="/static/work/css/bootstrap/js/bootstrap.min.js?"></script>
<script type="text/javascript" src="/static/work/js/jquery-ui/jquery-ui-1.9.2-min.js?" ></script>
<script type="text/javascript" src="/static/work/js/jsPlumb/jquery.jsPlumb-1.3.16-all-min.js?"></script>
<script type="text/javascript" src="/static/work/js/jquery.contextmenu.r2.js?"></script>
<!--select 2-->
<script type="text/javascript" src="/static/work/js/jquery.multiselect2side/js/jquery.multiselect2side.js?" ></script>
<!--flowdesign-->
<script type="text/javascript" src="/static/work/js/flowdesign/leipi.flowdesign.v3.js?"></script>
<script type="text/javascript" src="/static/work/js/flowdesign/attribute.js"></script>
