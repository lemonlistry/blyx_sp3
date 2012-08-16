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
            <header>
                <div class="right">
                    <?php echo Html5::link('添加资源绑定', array('/passport/resource/addresourcebind'), array('class' => 'js-dialog-link')); ?>
                </div>
            </header>
            <div class="main-content">
                <div class="grid-view">
                    <table>
                        <thead>
                            <tr>
                                <th class="span4">编号</th>
                                <th class="span6">资源名称</th>
                                <th class="span16">绑定路径</th>
                                <th class="span5">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if (count($list)) {
                                    foreach ($list as $k => $v) {
                                        $resource = Resource::model()->findByAttributes(array('id' => $v->resource_id));
                                       
                            ?>
                                        <tr> 
                                            <td><?php echo $v->id; ?></td>
                                            <td><?php echo $resource->name; ?></td>
                                            <td><?php echo $v->path; ?></td>
                                            <td>
                                                <?php
                                                    echo Html5::link('编辑', array('/passport/resource/updateresourcebind', 'id' => $v->id), array('class' => 'js-dialog-link')) . ' ' .
                                                    Html5::link('删除', array('/passport/resource/deleteresourcebind', 'id' => $v->id), array('class' => 'js-confirm-link', 'data-title' => "您确定要删除当前资源绑定吗？"));
                                                ?>
                                            </td>
                                        </tr>
                                <?php 
                                    } 
                                ?>
                                    <tr>
                                        <td colspan="6"> <div class="pager"><?php $this->widget('CLinkPager', array('pages'=>$pages));?> </div></td>
                                    </tr>
                            <?php 
                                } else { 
                            ?>
                                    <tr>
                                        <td colspan="6">暂无数据!</td>
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

