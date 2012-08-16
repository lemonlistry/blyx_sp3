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
        <div class="main-container prepend5" style="overflow: auto;">
            <?php 
                $form = $this->beginWidget('ActiveForm', array('id' => 'retentionrate_form', 'method' => 'get', 'action' => $this->createUrl('/core/statistic/retentionrate')));
            ?>
            <header>
                <div class="right">
                    <?php 
                        echo Html5::dropDownList('server_id', $param['server_id'], $select, array('class'=>'span3'));
                    ?>
                    <label>开始时间:</label>
                    <?php 
                        echo Html5::timeField('begintime', $param['begintime']);
                    ?>
                    <label>结束时间:</label>
                    <?php 
                        echo Html5::timeField('endtime', $param['endtime']);
                    ?>
                    <input type="submit" value='查询' />
                    <a id="data_export" data-url=<?php echo $this->createUrl('/core/statistic/retentionrateexport'); ?> href="javascript:void(0);">数据导出</a>
                </div>
            </header>
            <?php $this->endWidget(); ?>
            <div class="main-content">
                <div class="grid-view">
                    <table>
                        <thead>
                            <tr>
                                <th width="80px;">统计日期</th>
                                <th width="80px;">服务器</th>
                                <th width="80px;">新注册玩家数</th>
                                <?php 
                                    for ($i = 1; $i < 31; $i++) {
                                         echo '<th width="50px;">' . $i . '</th>';
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
<tr>
                            <?php 
                                if (count($list)) {
                                    $begin = date('Y-m-d',strtotime($param['begintime']));
                                    $end = date('Y-m-d',strtotime($param['endtime'])); 
                                    foreach ($list as $v){
                                         $value[$v['compare_day']][$v['current_day']] = $v['num'];
                                    }
                                    for($m=1;intval(strtotime($end)) - intval(strtotime($begin)) >= 0;$m++){
                                        echo '<td>'.$end.'</td>';
                                        echo '<td>'.Util::getServerAttribute($param['server_id'],'sname').'</td><td>';
                                        echo isset($register_list[$end]) ? $register_list[$end] : 0 . '</td>';
                                        for($i=1;$i<31;$i++){
                                            echo isset($value[$end][date('Y-m-d',(strtotime($end)+($i*24*3600)))]) ?
                                            '<td>'.round((($value[$end][date('Y-m-d',(strtotime($end)+($i*24*3600)))]/($register_list[$end]))*100),2).
                                            '%</td>' : '<td>0</td>';
                                        }
                                        ?>
</tr>
                                        <?php 
                                        $end = date('Y-m-d',strtotime($param['endtime'])-3600*24*$m);
                                    }
                            ?>
                            <?php 
                                } else { 
                            ?>
                                    <tr>
                                        <td colspan="32">暂无数据!</td>
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
    $("#retentionrate_form").submit(function(){
        if($.trim($("#server_id").val()) == '' ||  $.trim($("#begintime").val()) == '' ||  $.trim($("#endtime").val()) == ''){
            Dialog.alert('请输入参数');
            return false;
        }
    });
    $("#data_export").click(function(){
        var server_id = $.trim($("#server_id").val());
        var begintime = $.trim($("#begintime").val());
        var endtime = $.trim($("#endtime").val());
        if(server_id == '' ||  begintime == '' || endtime == ''){
            Dialog.alert('请输入参数');
            return false;
        }else{
            var url = $(this).data('url');
            if(url.indexOf('?') > 0){
                url += '&';
            }else{
                url += '?';
            }
            url += 'server_id=' + server_id + '&begintime=' + begintime + '&endtime=' + endtime;
            location.href = url;
        }
    });
});
</script>