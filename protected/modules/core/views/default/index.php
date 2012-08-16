<div id="page_body">
<div id="page_title">
    <?php
        require dirname(__FILE__) . '/_menu.php';
    ?>
</div>

<div class="main-box">
    <div class="main-body">
        <aside class="span5">
            <?php
                $this->widget('zii.widgets.CMenu', array('items' => $menu, 'activeCssClass' => 'selected',
                    'htmlOptions' => array('class' => 'left-menu',)));
            ?>
        </aside>
        <div class="main-container prepend5">
            <?php
                $form = $this->beginWidget('ActiveForm', array('id' => 'statistic_user_form', 'method' => 'get', 'action' => $this->createUrl('/core/default')));
            ?>
            <header>
                <div class="right">
                    <label>开始时间:</label>
                    <?php
                        echo Html5::timeField('begintime', $begintime);
                    ?>
                    <input type="submit" value='查询' />
                    <a id="data_export" data-url=<?php echo $this->createUrl('/core/default/export'); ?> href="javascript:void(0);">数据导出</a>
                </div>
            </header>
            <?php $this->endWidget(); ?>
            <div class="main-content">
                <div class="grid-view">
                    <table>
                        <thead>
                            <tr>
                                <th width="80px;">服务器</th>
                                <th width="80px;">开服时间</th>
                                <th width="85px;">实时在线</th>
                                <th width="100px;">今日登录数(DAU)</th>
                                <th width="80px;">当前注册总数</th>
                                <th width="105px;">当前创建角色总数</th>
                                <th width="80px;">当前充值人数</th>
                                <th width="80px;">今日注册总数</th>
                                <th width="95px;">今日创建角色数</th>
                                <th width="80px;">今日充值人数</th>
                                <th width="80px;">今日充值累计</th>
                                <th width="80px;">本月充值累计</th>
                                <th width="80px;">截止充值累计</th>
                                <th width="80px;">开服天数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (count($list)) {
                                    foreach ($list as $k => $v) {
                                        $server = Util::getServerAttribute($k);
                            ?>
                                        <tr>
                                            <td><?php echo isset($server->sname) ? $server->sname : '' ; ?></td>
                                            <td><?php echo isset($server->create_time) ? substr($server->create_time, 0, 10) : ''; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo isset($v["login_num"]) ? $v["login_num"] : 0 ; ?></td>
                                            <td><?php echo isset($v["register_num"]) ? $v["register_num"] : 0; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo isset($v["register_day_num"]) ? $v["register_day_num"] : 0; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo ''; ?></td>
                                            <td><?php echo isset($server->create_time) ? (ceil((time() - strtotime($server->create_time)) / (3600*24))) : 0; ?></td>
                                            <td></td>
                                        </tr>
                                <?php
                                    }
                                ?>
                            <?php
                                } else {
                            ?>
                                    <tr>
                                        <td colspan="22">暂无数据!</td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
jQuery(function($) {
    $("#statistic_user_form").submit(function(){
        if($.trim($("#begintime").val()) == ''){
            Dialog.alert('请输入参数');
            return false;
        }
    });
    $("#data_export").click(function(){
        var begintime = $.trim($("#begintime").val());
        if(begintime == ''){
            Dialog.alert('请输入参数');
            return false;
        }else{
            var url = $(this).data('url');
            if(url.indexOf('?') > 0){
                url += '&';
            }else{
                url += '?';
            }
            url += 'begintime=' + begintime;
            location.href = url;
        }
    });
});
</script>