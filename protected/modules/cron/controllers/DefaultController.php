<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        set_time_limit(500);
        $this->sendNotice();
        $this->retentionRate();
    }
    
    /**
     * 在线公告
     */
    protected function sendNotice(){
        Yii::import('service.models.Notice');
        $list = Notice::model()->findAllByAttributes(array('status' => 1));
        if(count($list)){
            foreach ($list as $v) {
                if(time() >= $v->send_time){
                    Service::sendOnlineNotice($v->server_id, $v->interval_time, $v->play_times, $v->content);
                    $model = $this->loadModel($v->id, 'Notice');
                    $model->status = 3;
                    $model->save();
                }
            }
        }
    }
    
    /**
     * retentionRate 计划任务
     */
    protected function retentionRate(){
        $cache_day = Yii::app()->cache->get('cache_day');
        $date = date('Y-m-d');
        if($cache_day != $date){
            Yii::app()->cache->set('cache_day', $date, 86400);
            $list = array();
            $tmp_date = date('Y-m-d', strtotime('-1 day'));
            for ($i = 1; $i < 31; $i++) {
                $day = date('Y-m-d', strtotime('-' . $i .' day'));
                $res = Yii::app()->db->createCommand()->select('a.server_id,COUNT(a.openid) AS num')->from('dau AS a')
                            ->leftJoin('installation AS b', 'a.server_id = b.server_id AND a.openid = b.openid')
                            ->where("b.ts LIKE '{$day}%'  AND a.ts LIKE '{$tmp_date}%'")
                            ->group('server_id')
                            ->queryAll();
                if(count($res)){
                    foreach ($res as $value) {
                        Yii::app()->db->createCommand()->insert('retention_rate', array('server_id' => $value['server_id'], 
                                'current_day' => $tmp_date, 'compare_day' => $day, 'num' => $value['num']));
                    }
                }
            }
        }
    }
    
    /**
     * 生成历史留存率数据
     */
    public function actionGenerateRetentionRateData(){
        set_time_limit(300);
        $date = date('Y-m-d');
        $form_day = '2012-06-06';
        $list = array();
        $num = (strtotime($date) - strtotime($form_day)) / (3600 * 24);
        for ($j = 0; $j < $num; $j++) {
            if($form_day < $date){
                for ($i = 1; $i < 31; $i++) {
                    $day = date('Y-m-d', strtotime('-' . $i .' day'));
                    $res = Yii::app()->db->createCommand()->select('a.server_id,COUNT(a.openid) AS num')->from('dau AS a')
                                ->leftJoin('installation AS b', 'a.server_id = b.server_id AND a.openid = b.openid')
                                ->where("b.ts LIKE '{$day}%'  AND a.ts LIKE '{$form_day}%'")
                                ->group('server_id')
                                ->queryAll();
                    if(count($res)){
                        foreach ($res as $value) {
                            Yii::app()->db->createCommand()->insert('retention_rate', array('server_id' => $value['server_id'], 
                                    'current_day' => $form_day, 'compare_day' => $day, 'num' => $value['num']));
                        }
                    }
                } 
                $form_day = date('Y-m-d', (strtotime($form_day) + 86400));
            }
        }
    }
    
}