<?php
    $menu = array(
                array('label' => '关键数据总览', 'url' => array('/core/default/index'), 'active'=>($this->action->id == 'index' ? true : false)),
                );
    $this->widget('zii.widgets.CBreadcrumbs', array('links' => array(
            '运营管理',
            $title,
            )));
?>