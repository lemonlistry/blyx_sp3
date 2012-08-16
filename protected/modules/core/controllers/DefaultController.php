<?php

class DefaultController extends Controller
{

    /**
     * 关键数据总览
     */
    public function actionIndex()
    {
        $begintime = $this->getParam('begintime');
        $list = $this->getIndexData($begintime);
        $title = '关键数据总览';
        if(empty($begintime)){
            $begintime = date('Y-m-d');
        }
        $date = date('Y-m-d',strtotime($begintime)+3600*24);
        $this->render('index', array('title' => $title, 'list' => $list, 'begintime' => $begintime));
     }
     
     /*
      * 获取关键数据
      */
     protected function getIndexData($begintime){
        $date = date('Y-m-d',strtotime($begintime)+3600*24);
        $list = array();
            // 当天登入总数
        $dau_list = Yii::app()->db->createCommand()->select('server_id, COUNT(openid) AS login_num, DATE(ts) AS day')
                        ->from('dau')->where('ts  >= :time and ts < :tim',array(':time'=>$begintime,':tim' => $date))->group('server_id')->order('server_id')->queryAll();
        if(count($dau_list)){
            foreach ($dau_list as $value) {
                $list[$value['server_id']]['login_num'] = $value['login_num'];
            }
        }
        //当天注册总数
        $dau_register_day = Yii::app()->db->createCommand()->select('server_id, COUNT(openid) AS register_day_num, DATE(ts) AS day')->from('installation')
                                        ->where('ts  >= :time and ts < :tim',array(':time'=>$begintime,':tim' => $date))->group('server_id')->queryAll();
            if(count($dau_register_day)){
            foreach($dau_register_day as $value){
                $list[$value['server_id']]['register_day_num'] = $value['register_day_num'];
            }
        }
        //截止注册总数
        $install_list = Yii::app()->db->createCommand()->select('server_id, COUNT(openid) AS register_num, DATE(ts) AS day')->from('installation')
                            ->where('ts <= :time' , array(':time' => $begintime))->group('server_id')->queryAll();
                if(count($install_list)){
                foreach ($install_list as $value) {
                $list[$value['server_id']]['register_num'] = $value['register_num'];
            }
        }
         return $list;
     }
     /*
      * 关键数据导出
      */
     public function actionExport(){
        $title = '关键数据';
        $begintime= $this->getParam('begintime');
        $list = $this->getIndexData($begintime);
        header("Content-Type: application/vnd.ms-excel;charset=utf8");
        header("Content-Disposition: attachment; filename=".$title.".xls");
        $header = iconv("UTF-8","GB2312//IGNORE",'服务器')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'开服时间')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'实时在线')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'今日登录总数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'当前注册总数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'当前创建角色总数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'当前充值人数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'今日注册总数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'今日创建角色数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'今日充值人数')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'今日充值累计')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'本月充值累计')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'截止充值累计')."\t";
        $header .= iconv("UTF-8","GB2312//IGNORE",'开服天数')."\n";
        echo $header;
        if (count($list)) {
            foreach ($list as $k => $v) {
                $server = Util::getServerAttribute($k);
                echo iconv("UTF-8","GB2312//IGNORE",(isset($server->sname) ? $server->sname : ''))."\t" ; 
                echo (isset($server->create_time) ? substr($server->create_time, 0, 10) : '')."\t"; 
                echo ''."\t"; 
                echo (isset($v["login_num"]) ? $v["login_num"] : 0)."\t"; 
                echo (isset($v["register_num"]) ? $v["register_num"] : 0)."\t"; 
                echo ''."\t"; 
                echo ''."\t"; 
                echo (isset($v["register_day_num"]) ? $v["register_day_num"] : 0)."\t";
                echo ''."\t"; 
                echo ''."\t"; 
                echo ''."\t"; 
                echo ''."\t"; 
                echo ''."\t"; 
                echo (isset($server->create_time) ? (ceil((time() - strtotime($server->create_time)) / (3600*24))) : 0)."\n"; 
            }
        }
     }
}