<?php
 
    function syncCreditsByMemberGroupId ($memberGroupId,$memberId) {
    /*
     * 根据用户组调整同步积分 如果未获得用户id 则直接输出会员组积分
     */
        $DB_member = M('member');
        $DB_memberGroup = M('member_group');
        $resMemberGroup = $DB_memberGroup -> where("`id` = $memberGroupId") -> field('credits') ->find();
        $groupCredits = $resMemberGroup['credits'];

        if (empty($memberId)) {
            //如果未获得会员id 则返回会员组默认积分并退出
            return($groupCredits);
        }
        
    }
    function  syncMemberGroupByCredits ($credits,$memberId) {
    /*
     * 根据用户积分同步用户组 未获得用户id 返回积分所属级别id
     */
        $DB_member = M('member');
        $DB_memberGroup = M('member_group');
        $resMemberGroup = $DB_memberGroup -> where("`credits` < $credits") -> order('credits desc') -> field('id') ->find();
        $groupId = $resMemberGroup['id'];
        if (empty($memberId)) {
            //如果未获得会员id 则返回相应会员组id
            return($groupId);
        }        

    }
    function  syncCreditsLog ($memberId,$type,$changes,$remark) {
    /*
     * 记录用户等级积分变动
     */
        $DB_logCredits = M('log_credits');

        $data['member_id'] = $memberId;
        $data['type'] = $type;
        $data['changes'] = $changes;
        $data['remark'] = $remark;
        $data['changetime'] = time();

        $addRes = $DB_logCredits -> add($data);
        return($addRes);
    }
    function getOrderStatusName ($statusNum) {
    /*
     * 通过订单状态号获得状态名
     */
        $statusList = C('order_status');
        return $statusList[$statusNum];
    }


    /**
     * 检测当前登陆管理员是否为超级管理员
     * @param  $uid
     * @return bool
     *
     * chenyunlong@tieson.cn
     */
    function is_administrator($uid = null){
        $uid = is_null($uid) ? is_login() : $uid;
        return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
    }

    /**
     * 检测用户是否登陆
     * @return integer 0-未登录，大于0-当前登录用户ID
     *
     * chenyunlong@tieson.cn
     */
    function is_login(){
        $user = session(C('ADMIN_USER_KEY'));
        if (empty($user)) {
            return 0;
        } else {
            return ($user['id']>0)? $user['id'] : 0;
        }
    }


    /**
     * 获取和设置动态配置参数(持久到DB中) dbCnfig
     * DB Config
     * @param string|array $name 配置变量
     * @param mixed $value 配置值
     * @return mixed
     */
    function DBC($name=null, $value=null) {

        // 无参数时获取所有
        if (empty($name)) {
            $_db_config = M("config")->select();
            return $_db_config;
        }

        // 优先执行设置获取或赋值
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                $name = strtolower($name);
                $configObj = M("config")->where("c_key='$name'")->find();
                if (is_null($value)){
                    return isset($configObj['c_value']) ? $configObj['c_value'] : null;
                }

                if(!empty($configObj)){
                    $configObj['c_value'] = $value;
                    M("config")->where("c_key='$name'")->save($configObj);
                }else{
                    $configObj['c_key'] = $name;
                    $configObj['c_value'] = $value;
                    M("config")->add($configObj);
                }
                return;
            }
        }
        return null; // 避免非法参数
    }

