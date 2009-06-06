<?php
/**
 * Description of Rbac
 *
 * @author oleksiy
 */
class Rbac {

    public function init()
    {
        
    }

    /**
     *
     * @param string $controller controller.id
     * @param string $action action.id
     * @param numeric $user user ID
     * @param array $fakeGET fake _GET array for evaluating businness rules
     * @return boolean true if user has access, false otherwise
     */
    public function checkAccess($controller, $action, $user = null, array $fakeGET = null)
    {
        $allow = false;
        
        if(!$user){
            $user = Yii::app()->user->id;
        }

        $sql = 'SELECT DISTINCT p.* FROM permissions p, users u, roles r, users_has_roles ur, roles_has_permissions rp
                WHERE p.id=rp.permissions_id
                    AND r.id=rp.roles_id
                    AND ur.roles_id=r.id
                    AND ur.users_id=u.id
                    AND u.id=:uid
                    AND p.controller=:controller
                    AND p.action=:action
                ORDER BY p.bizrule ASC';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":uid", $user, PDO::PARAM_INT);
        $command->bindParam(":controller", $controller, PDO::PARAM_STR);
        $command->bindParam(":action", $action, PDO::PARAM_STR);
        $permissions = $command->queryAll();


        if($fakeGET){ // lets fake GET request params for evaluating business rule
            $save_GET = $_GET;
            $_GET = $fakeGET;
        }

        if(!empty($permissions)){
            foreach($permissions as $p){
                if(empty($p['bizrule']) || @eval($p['bizrule'])){
                    $allow = true;
                    break;
                }
            }
        }

        if($fakeGET){ // restore original _GET
            $_GET = $save_GET;
        }

        return $allow;
    }

    /**
     *
     * @param mixed $searchCriteria can be a string or an array with RBAC search criterias
     * @param numeric $user user.id
     * @return boolean true if user satisfies rbac criteria $searchCriteria
     */
    public function checkAccessEx($searchCriteria, $user = null)
    {
        $from = array();
        $where = array();

        if(!$user){
            $user = Yii::app()->user->id;
        }

        $from[] = 'users u';
        $where[] = 'u.id='.intval($user);
        $this->parseSearchCriteria($searchCriteria, &$from, &$where);

        $sql = 'SELECT u.id FROM '.join($from, ', ').' WHERE '.join($where, ' AND ');
        $access = Yii::app()->db->createCommand($sql)->queryScalar();
        return(!empty($access));
    }

    private function parseSearchCriteria($conditions, &$from, &$where)
    {
        if(!is_array($conditions)){
            $conditions = array($conditions);
        }
        foreach($conditions as $c){
            $c .= ',';
            if(eregi('(users|roles|permissions)=', $c, $regs)){
                $talias = $regs[1]{0};
                $from[] = $regs[1].' '.$talias;
            }
            $or = array();
            if(preg_match_all('/(\w+):(.*?),/', $c, $regs)){
                for($i=0; $i<count($regs[0]); $i++){
                    if(is_numeric($regs[2][$i])){
                        $or[] = $talias.'.'.$regs[1][$i].'='.$regs[2][$i];
                    } else {
                        $value = ereg_replace('[\'\"]', '', $regs[2][$i]);
                        $or[] = $talias.'.'.$regs[1][$i].' LIKE \''.$value.'\'';
                    }
                }
            }
            if(count($or)){
                $where[] = '('.join($or, ' OR ').')';
            }
        }

        $addUsersHasRoles = false;
        $addRolesHasPermissions = false;
        if(ereg('users', join($from, ' ')) && ereg('permissions', join($from, ' '))){
            $from[] = 'roles r';
            $addUsersHasRoles = true;
            $addRolesHasPermissions = true;
        } else {
            if(ereg('users', join($from, ' ')) && ereg('roles', join($from, ' '))){
                $addUsersHasRoles = true;
            }
            if(ereg('permissions', join($from, ' ')) && ereg('roles', join($from, ' '))){
                $addRolesHasPermissions = true;
            }
        }
        if($addRolesHasPermissions){
            $from[] = 'roles_has_permissions rp';
            $where[] = 'r.id=rp.roles_id';
            $where[] = 'p.id=rp.permissions_id';
        }
        if($addUsersHasRoles){
            $from[] = 'users_has_roles ur';
            $where[] = 'u.id=ur.users_id';
            $where[] = 'r.id=ur.roles_id';
        }

        $from = array_unique($from);
        $where = array_unique($where);
    }

}
?>
