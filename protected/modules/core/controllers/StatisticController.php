<?php

class StatisticController extends Controller
{
    
    /**
     * 日注册、登录数
     */
    public function actionIndex()
    {
        $param = $this->getParam(array('begintime', 'endtime', 'server_id'));
        $title = '日注册、登录数';
        $select = Util::getRealServerSelect();
        $date = date('Y-m-d',strtotime($param['begintime']));
        $list = array();
        $limit = (ceil((strtotime($param['endtime'])-strtotime($param['begintime']))/(3600*24))+1);
        if(!empty($param['begintime']) && !empty($param['endtime']) && !empty($param['server_id'])){
            for($i=1;$i<=$limit;$i++){
                $list[$date][] = Yii::app()->db->createCommand()->select('COUNT(DISTINCT openid) AS register_tot')->from('installation')->where('server_id = :server_id AND ts <= :date',
                        array(':server_id' => $param['server_id'], ':date' => $date))->queryAll();
                $list[$date][] = Yii::app()->db->createCommand()->select('COUNT(DISTINCT openid) AS login_tot')->from('dau')->where('server_id = :server_id AND ts LIKE CONCAT(:date,"%")',
                        array(':server_id' => $param['server_id'], ':date' => $date))->queryAll();
                $list[$date][] = Yii::app()->db->createCommand()->select('COUNT(DISTINCT openid) AS register_day')->from('installation')->where('server_id = :server_id AND ts LIKE CONCAT(:date,"%")',
                        array(':server_id' => $param['server_id'], ':date' => $date))->queryAll();
                $list[$date][] = Yii::app()->db->createCommand()->select('COUNT(DISTINCT a.openid) AS login_day')->from('dau AS a')->leftJoin('installation AS b','a.server_id = b.server_id AND a.openid = b.openid')->
                        where('a.server_id = :server_id AND a.ts LIKE CONCAT(:date,"%") AND b.ts LIKE CONCAT(:date,"%")',
                        array(':server_id' => $param['server_id'], ':date' => $date, ':date' => $date,))->queryAll();   
                $list[$date]['date'] = $date;
                $date = date('Y-m-d',(strtotime($date)+3600*24));
            }
        }
        $this->render('index', array('title' => $title, 'list' => $list, 'param' => $param, 'select' => $select));
    }
    
    /**
     * 日留存率
     */
    public function actionRetentionRate()
    {
        $title = '日留存率';
        $param = $this->getParam(array('begintime', 'endtime', 'server_id'));
        $list = $register_list = array();
        if(!empty($param['begintime']) && !empty($param['endtime']) && !empty($param['server_id'])){
            $data = $this->getRetentionRateData($param);
            $list = $data['list'];
            $register_list = $data['register_list'];
        }
        $select = Util::getRealServerSelect();
        $this->render('retention_rate', array('title' => $title, 'list' => $list, 'param' => $param, 'select' => $select,'register_list' => $register_list));
    }
    
    /**
     * 获取留存率数据
     */
    protected function getRetentionRateData($param)
    {
        $register_list = array();
        $list = Yii::app()->db->createCommand()->from('retention_rate')
                ->where('server_id = :server_id AND current_day >= :begintime AND current_day <= :endtime', 
                    array(':server_id' => $param['server_id'], ':begintime' => $param['begintime'], ':endtime' => $param['endtime']))
                ->queryAll();
        $result = Yii::app()->db->createCommand()->select('DATE(ts) AS day, count(openid) AS num')->from('installation')
                                ->where('server_id = :server_id AND ts >= :begintime AND ts <= :endtime', 
                                    array(':server_id' => $param['server_id'], ':begintime' => $param['begintime'], ':endtime' => date('Y-m-d',(strtotime($param['endtime'])+24*3600))))
                                ->group('day')
                                ->queryAll();
        if(count($result)){
            foreach ($result as $v) {
                $register_list[$v['day']] = $v['num'];
            }
        }
        return array('list' => $list, 'register_list' => $register_list);
    }
    /*
     * 日留存率导出excel
     */
    public function actionRetentionRateExport(){
        $title = '日留存率';
        $param = $this->getParam(array('begintime', 'endtime', 'server_id'));
        $list = $register_list = array();
        $data = $this->getRetentionRateData($param);
        $list = $data['list'];
        $register_list = $data['register_list'];
        header("Content-Type: application/vnd.ms-excel;charset=utf8");
        header("Content-Disposition: attachment; filename=".$title.".xls");
        $header = iconv("UTF-8","GB2312//IGNORE",'统计日期')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'服务器')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'注册玩家数')."\t";
        for($i=1;$i<=30;$i++){
            $header .= $i."\t";
        }
        $header .= "\n";
        echo $header;
           if (count($list)) {
           $begin = date('Y-m-d',strtotime($param['begintime']));
           $end = date('Y-m-d',strtotime($param['endtime'])); 
                foreach ($list as $v){
                       $value[$v['compare_day']][$v['current_day']] = $v['num'];
                }
                for($m=1;intval(strtotime($end)) - intval(strtotime($begin)) >= 0;$m++){
                          echo $end;
                          echo "\t".iconv("UTF-8","GB2312//IGNORE",Util::getServerAttribute($param['server_id'],'sname'));
                          echo "\t" . (isset($register_list[$end]) ? $register_list[$end] : 0);
                          for($i=1;$i<31;$i++){
                              echo isset($value[$end][date('Y-m-d',(strtotime($end)+($i*24*3600)))]) ?
                              "\t".round((($value[$end][date('Y-m-d',(strtotime($end)+($i*24*3600)))]/($register_list[$end]))*100),2).
                              '%' : "\t0";
                          }
        echo "\n";
        $end = date('Y-m-d',strtotime($param['endtime'])-3600*24*$m);
                }
         }
        Yii::app()->end();
    }
}