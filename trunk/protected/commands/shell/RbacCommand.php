<?php

class RbacCommand extends CConsoleCommand
{
    private $_helpText = "
USAGE
  rbac search <users|roles|permissions> [search criteria1] [search criteria2] ..
  rbac create <role|permission> [controller.id]
  rbac delete <search criteria>
  rbac edit <search criteria>
  rbac grant <search criteria> <search criteria>
  rbac revoke <search criteria> <search criteria>

  rbac applist controllers
  rbac applist actions <controller.id>
  rbac missing <permissions|roles|controllers>

DESCRIPTION
  This set of commands allows to operate with permissions and roles used by
  application.filters.RbacFilter filter class. You can
  create/update/delete roles and permissions, grant users with roles
  (and revoke), add and remove permissions from roles. You can also list
  controllers and actions with missing permissions and/or roles in database.

RBAC SEARCH CRITERIA
  <tablename>=<field>:<value>,<field:value>...
  * tablename: one of users, roles, permissions
  * field: table field name (id, name, description)
  * value: field value to search for, can be a number, string
    (enclosed in quotes) or '%' char. You can also use that character in string
    wildcards.

  Some examples:
  >> rbac search users users=id:1,id:2
  will return two users with id=1 and id=2

  >> rbac search roles permissions=controller:auth.% permissions=action:update
  will return all roles with permissions for controller with ID starting with
  the string 'auth.' _AND_ action ID equal to 'update'. Compare to the following:

  >> rbac search roles permissions=controller:auth.%,action:update
  will return all roles with permissions for controller with ID starting with
  the string 'auth.' _OR_ action ID equal to 'update'
";


    private $_createHelpText = "
USAGE
    rbac create <role|permission> [controller.id]

DESCRIPTION
    This command will create role or permission in a wizard-like style.
    You will be asked to answer few questions in order to create
    new role or permission.

    You can also use it create permissions for all controller actions, in this
    case run it as:
    >> rbac create permission <controller.id>
";

    private $_deleteHelpText = "
USAGE
    rbac delete <search criteria>

DESCRIPTION
    This command will delete users, roles or permissions from the database
";

    private $_updateHelpText = "
USAGE
    rbac edit <search criteria>

DESCRIPTION
    This command will update roles and permissions in the database
";

    private $_grantHelpText = "
USAGE
    rbac grant <search criteria> <search criteria>
    rbac revoke <search criteria> <search criteria>

DESCRIPTION
    This command will set (or unset) corresponding relations between users
    and roles or between roles and permissions. It can't, however, grant/revoke
    users with permissions directly - you need to create a role first and then
    grant/revoke users with this role.
";

    private $_applistHelpText = "
USAGE
    rbac applist controllers
    rbac applist actions <controller.id>

DESCRIPTION
    This command will list application controllers and their actions.
";

    private $_missingHelpText = "
USAGE
    rbac missing <permissions|roles|controllers>

DESCRIPTION
    >> rbac missing permissions
    will list controllers with actions which _ARE_ using RBAC filter but don't
    have any permissions defined

    >> rbac missing controllers
    will list controllers that are _NOT_ using RBAC filter

    >> rbac missing roles
    will list permissions that are not assigned to any role
";

    public function run($args)
    {
        switch($args[0]){
            case 'search':
                $this->searchCommand($args);
                break;
            case 'create':
                $this->createCommand($args);
                break;
            case 'grant':
            case 'revoke':
                $this->grantRevokeCommand($args);
                break;
            case 'delete':
                $this->deleteCommand($args);
                break;
            case 'edit':
                $this->updateCommand($args);
                break;
            case 'applist':
                $this->listCommand($args);
                break;
            case 'missing':
                $this->missingCommand($args);
                break;
            default:
                echo $this->_helpText;
        }
    }
                     
    public function getHelp()
    {
        return $this->_helpText;
    }

    private function parseSearchCriteria($args, &$from , &$where)
    {
        $i = 0; $c = -1;
        $conditions = array();
        $comma = false;
        while(!empty($args[$i])){
            if(eregi('(users|roles|permissions)=', $args[$i])){ //start of condition
                $c++;
            }
            if(preg_match('/^(.*?):[\'\"]/', $args[$i]) xor preg_match('/[\'\"]$/', $args[$i])){
                $comma = !$comma;
            }
            $args[$i] = ereg_replace('[\'\"]', '', $args[$i]);
            $conditions[$c] .= $args[$i];
            if($comma){
                $conditions[$c] .= '_';
            } else {
                $conditions[$c] .= ',';
            }
            $i++;
        }

        // now parse conditions and FROM and WHERE lists
        foreach($conditions as $c){
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
                        $or[] = $talias.'.'.$regs[1][$i].' LIKE \''.$regs[2][$i].'\'';
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

    private function listCommand($args)
    {
        switch($args[1]){
            case 'controllers':
                $this->listControllersCommand($args);
                break;
            case 'actions':
                $this->listControllerActions($args);
                break;
            default:
                echo $this->_applistHelpText;
                return;
        }
    }

    private function listControllersInFolder($path, $alias = '')
    {
        $controllers =  array();
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if(is_file($path.DIRECTORY_SEPARATOR.$file)){
                        if(preg_match('/^(.*?)Controller\.php$/', $file, $matches)){
                            $controller['id'] = $alias.strtolower($matches[1]);
                            $controller['path'] = $path.DIRECTORY_SEPARATOR.$file;
                            $controllers[] = $controller;
                        }
                    } else if(is_dir($path.DIRECTORY_SEPARATOR.$file)){
                        $controllers = array_merge($controllers, $this->listControllersInFolder($path.DIRECTORY_SEPARATOR.$file, $alias.$file.'/'));
                    }
                }
            }
            closedir($handle);
        }
        return $controllers;
    }

    private function getControllerActions($id)
    {
        $actions = array();
        $ci = Yii::app()->createController($id);
        if(is_array($ci)) $ci = $ci[0];
        if($ci){
            $methods = get_class_methods($ci);
            if($methods){
                foreach($methods as $method){
                    if(preg_match('/^action([A-Z])(.*?)$/', $method, $matches)){
                        //echo $method."\n";
                        $actions[] = strtolower($matches[1].$matches[2]);
                    }
                }
            } else {
                echo 'no methods found for controller '.$id."\n";
            }
        } else {
            echo 'Unknown controller '.$id."\n";
        }

        return $actions;
    }

    private function listControllerActions($args)
    {
        $data = array();
        
        $actions = $this->getControllerActions($args[2]);
        foreach($actions as $a){
            $action['id'] = $a;
            
            $sql = 'SELECT * FROM permissions p WHERE controller=\''.$args[2].'\' AND action=\''.$a.'\'';
            $permissions = Yii::app()->db->createCommand($sql)->queryAll();

            $action['permissions'] = count($permissions);

            $data[] = $action;
        }

        ConsoleTablePrinter::printTable(array('Action.ID', 'Number of permissions' ), $data);
    }

    private function listControllersCommand($args)
    {
        //echo Yii::app()->getControllerPath();
        //print_r( get_class_methods(Yii::app()->createController('auth.users')) );
        $controllers = $this->listControllersInFolder(Yii::app()->getControllerPath());
        $data = array();
        foreach($controllers as $c){
            $actions = $this->getControllerActions($c['id']);
            $controller['id'] = $c['id'];
            $controller['actionsnum'] = count($actions);

            //get the number of unique permissions for this controller (no matter with or without bizrule)
            $permissions = Yii::app()->db->createCommand('SELECT * FROM permissions p WHERE controller=\''.$c['id'].'\' GROUP BY controller, action')->queryAll();
            $controller['permsnum'] = count($permissions);

            $data[] = $controller;
        }

        ConsoleTablePrinter::printTable(array('Controller.ID', 'Number of actions', 'Actions with permissions'), $data);
    }

    private function missingCommand($args)
    {
        switch($args[1]){
            case 'permissions':
                $this->missingPermissionsCommand();
                break;
            case 'controllers':
                $this->missingControllersCommand();
                break;
            case 'roles':
                $this->missingRolesCommand();
                break;
            default:
                echo $this->_missingHelpText;
                break;
        }
    }

    private function missingRolesCommand()
    {
        $sql = 'SELECT p.* FROM permissions p LEFT JOIN roles_has_permissions rp ON rp.permissions_id=p.id WHERE rp.roles_id IS NULL';
        $roles = Yii::app()->db->createCommand($sql)->queryAll();

        echo 'These permissions are not assigned to any role: '."\n";
        ConsoleTablePrinter::printTable(array('id', 'Name', 'Description', 'Controller.ID', 'Action.ID', 'Bizrule'), $roles);
        $this->printRbacSearchCriteria('permissions', $roles);
    }

    private function missingPermissionsCommand()
    {
        $data = array();

        $controllers = $this->listControllersInFolder(Yii::app()->getControllerPath());
        foreach($controllers as $c){
            $skipMissingActions = true;
            // lets check if this controller is filtered by our RBAC filter
            $ci = Yii::app()->createController($c['id']);
            if($ci != null){
                $filters = $ci->filters();
                foreach($filters as $f){
                    if(is_array($f)){ //class-based filter
                        if(ereg('application\.filters\.RbacFilter', $f[0])){ //our RBAC filter
                            $skipMissingActions = false; break;
                        }
                    }
                }
            }
            if(!$skipMissingActions){
                $actions = $this->getControllerActions($c['id']);
                foreach($actions as $a){
                    $sql = 'SELECT id FROM permissions WHERE controller=\''.$c['id'].'\' AND action=\''.$a.'\'';
                    $test = Yii::app()->db->createCommand($sql)->queryAll();
                    if(!count($test)){
                        $dataItem['controller'] = $c['id'];
                        $dataItem['action'] = $a;
                        $data[] = $dataItem;
                    }
                }
            }
        }

        echo 'There are no permissions defined for these actions: '."\n";
        ConsoleTablePrinter::printTable(array('Controller.ID', 'Action.ID'), $data);
        echo 'Note: we list only those controllers that are using our RBAC filter: application.filters.RbacFilter'."\n";
        echo 'To find which controllers are not using RBAC filter run >> rbac missing controllers'."\n";
    }

    private function missingControllersCommand()
    {
        $data = array();

        $controllers = $this->listControllersInFolder(Yii::app()->getControllerPath());
        foreach($controllers as $c){
            $usingRBAC = false;
            // lets check if this controller is filtered by our RBAC filter
            $ci = Yii::app()->createController($c['id']);
            if(is_array($ci)) $ci = $ci[0];
            if($ci != null){
                $filters = $ci->filters();
                foreach($filters as $f){
                    if(is_array($f)){ //class-based filter
                        if(ereg('application\.filters\.RbacFilter', $f[0])){ //our RBAC filter
                            $usingRBAC = true; break;
                        }
                    }
                }
            }
            if(!$usingRBAC){
                $sql = 'SELECT COUNT(*) FROM permissions WHERE controller=\''.$c['id'].'\'';
                $count = Yii::app()->db->createCommand($sql)->queryScalar();
                $data[] = array($c['id'], $count);
            }
        }

        echo 'These controllers are not using RBAC: '."\n";
        ConsoleTablePrinter::printTable(array('Controller.ID', 'Number of permissions defined'), $data);
    }

    private function deleteCommand($args)
    {
        $from = array();
        $where = array();

        $i = 1;
        while(!empty($args[$i])){
            if(eregi('(users|roles|permissions)=', $args[$i])){
                $criteriaStartIndex[] = $i;
            }
            $i++;
        }

        if(count($criteriaStartIndex) !=1){
            echo $this->_deleteHelpText;
            return;
        }


        $this->parseSearchCriteria(array_slice($args, $criteriaStartIndex[0]), &$from, &$where);

        $sql = 'SELECT '.$from[0]{strlen($from[0])-1}.'.* FROM '.$from[0].' WHERE '.$where[0];
        $command = Yii::app()->db->createCommand($sql);
        $items = $command->queryAll();

        $itemType = preg_replace('/s\s.$/', '', $from[0]);
        $delAll = false;

        foreach($items as $item){
            $del = false;
            echo '   delete '.$itemType.' \''.$item['name'].'\', id='.$item['id'].'     [Yes|No|All|Cancel] ';
            if(!$delAll){
                $answer = trim(fgets(STDIN));
                if(!strncasecmp($answer,'c',1)){
                    return;
                } else if(!strncasecmp($answer,'y',1)) {
                    $del = true;
                } else if(!strncasecmp($answer,'a',1)) {
                    $del = true;
                    $delAll=true;
                } else {
                        echo "       skipping \n";
                        continue;
                }
            } else {
                echo "Yes\n";
                $del = true;
            }
            if($del){
                $sql = 'DELETE '.$from[0]{strlen($from[0])-1}.'.* FROM '.$from[0].' WHERE id='.$item['id'];
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();
                echo "      removed\n";
            }
        }
    }

    private function askUpdateField($fname, $default, &$value, $required = true)
    {
        $value = '';
        echo '   change '.$fname.' ('.$default.')?   [Yes|No|Cancel] ';
        $answer = trim(fgets(STDIN));
        if(!strncasecmp($answer,'c',1)){
            return false;
        } else if(!strncasecmp($answer,'y',1)) {
            do {
                echo '   enter '.$fname.': ';
                $value = trim(fgets(STDIN));
            } while(empty($value) && $required);
            return true;
        } else {
            echo "       skipping\n";
            $value = $default;
            return true;
        }
    }

    private function updateCommand($args)
    {
        $from = array();
        $where = array();

        $i = 1;
        while(!empty($args[$i])){
            if(eregi('(roles|permissions)=', $args[$i])){
                $criteriaStartIndex[] = $i;
            }
            $i++;
        }

        if(count($criteriaStartIndex) !=1){
            echo $this->_updateHelpText;
            return;
        }


        $this->parseSearchCriteria(array_slice($args, $criteriaStartIndex[0]), &$from, &$where);

        $sql = 'SELECT '.$from[0]{strlen($from[0])-1}.'.* FROM '.$from[0].' WHERE '.$where[0];

        $command = Yii::app()->db->createCommand($sql);
        $items = $command->queryAll();

        $itemType = preg_replace('/s\s.$/', '', $from[0]);

        foreach($items as $item){

            $name = '';
            $description = '';
            $controller = '';
            $action = '';
            $bizrule = '';

            echo 'updating '.$itemType.' \''.$item['name'].'\', id='.$item['id']."\n";

            if(!$this->askUpdateField('name', $item['name'], $name)) return;
            if(!$this->askUpdateField('description', $item['description'], $description)) return;
            if($itemType == 'permission'){
                if(!$this->askUpdateField('controller ID', $item['controller'], $controller)) return;
                if(!$this->askUpdateField('action ID', $item['action'], $action)) return;
                if(!$this->askUpdateField('business rule', $item['bizrule'], $bizrule, false)) return;
            }

            if($itemType == 'role'){
                $sql = 'UPDATE roles SET name=:name, description=:description WHERE id='.$item['id'];
            } else {
                $sql = 'UPDATE permissions SET name=:name, description=:description, controller=:controller, action=:action, bizrule=:bizrule WHERE id='.$item['id'];
            }

            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":name", $name, PDO::PARAM_STR);
            $command->bindParam(":description", $description, PDO::PARAM_STR);

            if($itemType == 'permission'){
                $command->bindParam(":controller", $controller, PDO::PARAM_STR);
                $command->bindParam(":action", $action, PDO::PARAM_STR);
                $command->bindParam(":bizrule", $bizrule, PDO::PARAM_STR);
            }

            $data = $command->execute();

            echo 'Updated '.$itemType.' \''.$name.'\' with id='.$item['id']."\n";
            
        }

    }

    private function doAddUserToRole($uid, $rid)
    {
        $sql = 'INSERT INTO users_has_roles SET users_id=:uid, roles_id=:rid';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":uid", $uid, PDO::PARAM_INT);
        $command->bindParam(":rid", $rid, PDO::PARAM_INT);
        $data = $command->execute();

        echo "      added\n";
    }

    private function doRemoveUserFromRole($uid, $rid)
    {
        $sql = 'DELETE FROM users_has_roles WHERE users_id=:uid AND roles_id=:rid';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":uid", $uid, PDO::PARAM_INT);
        $command->bindParam(":rid", $rid, PDO::PARAM_INT);
        $data = $command->execute();

        echo "      removed\n";
    }

    private function doRemovePermissionFromRole($pid, $rid)
    {
        $sql = 'DELETE FROM roles_has_permissions WHERE permissions_id=:pid AND roles_id=:rid';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":pid", $pid, PDO::PARAM_INT);
        $command->bindParam(":rid", $rid, PDO::PARAM_INT);
        $data = $command->execute();

        echo "      removed\n";
    }

    private function doAddPermissionToRole($pid, $rid)
    {
        $sql = 'INSERT INTO roles_has_permissions SET permissions_id=:pid, roles_id=:rid';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":pid", $pid, PDO::PARAM_INT);
        $command->bindParam(":rid", $rid, PDO::PARAM_INT);
        $data = $command->execute();

        echo "      added\n";
    }

    private function grantUsersRoles($uwhere, $rwhere)
    {
        $usql = 'SELECT u.* FROM users u WHERE '.join($uwhere, ' AND ');
        $rsql = 'SELECT r.* FROM roles r WHERE '.join($rwhere, ' AND ');

        $rcommand = Yii::app()->db->createCommand($rsql);
        $roles = $rcommand->queryAll();

        $ucommand = Yii::app()->db->createCommand($usql);
        $users = $ucommand->queryAll();

        $addAll = false;
        foreach($users as $user){
            foreach($roles as $role){
                $test = Yii::app()->db->createCommand('SELECT users_id FROM users_has_roles WHERE users_id='.$user['id'].' AND roles_id='.$role['id'])->queryScalar();
                if(empty($test)){
                    echo '   add user ['.$user['id'].':'.$user['username'].':'.$user['email'].'] to role ['.$role['id'].':'.$role['name'].']?      [Yes|No|All|Cancel] ';
                    if(!$addAll){
                        $answer = trim(fgets(STDIN));
                        if(!strncasecmp($answer,'c',1)){
                            return;
                        } else if(!strncasecmp($answer,'y',1)) {
                            $this->doAddUserToRole($user['id'], $role['id']);
                        } else if(!strncasecmp($answer,'a',1)) {
                            $this->doAddUserToRole($user['id'], $role['id']);
                            $addAll=true;
                        } else {
                                echo "       skipping \n";
                                continue;
                        }
                    } else {
                        echo "Yes\n";
                        $this->doAddUserToRole($user['id'], $role['id']);
                    }
                } else {
                    echo '   user '.$user['username'].' already has the role \''.$role['name']."'\n";
                }
            }
        }
    }

    private function revokeUsersRoles($uwhere, $rwhere)
    {
        $usql = 'SELECT u.* FROM users u WHERE '.join($uwhere, ' AND ');
        $rsql = 'SELECT r.* FROM roles r WHERE '.join($rwhere, ' AND ');

        $rcommand = Yii::app()->db->createCommand($rsql);
        $roles = $rcommand->queryAll();

        $ucommand = Yii::app()->db->createCommand($usql);
        $users = $ucommand->queryAll();

        $addAll = false;
        foreach($users as $user){
            foreach($roles as $role){
                $test = Yii::app()->db->createCommand('SELECT users_id FROM users_has_roles WHERE users_id='.$user['id'].' AND roles_id='.$role['id'])->queryScalar();
                if(!empty($test)){
                    echo '   remove user ['.$user['id'].':'.$user['username'].':'.$user['email'].'] from role ['.$role['id'].':'.$role['name'].']?      [Yes|No|All|Cancel] ';
                    if(!$addAll){
                        $answer = trim(fgets(STDIN));
                        if(!strncasecmp($answer,'c',1)){
                            return;
                        } else if(!strncasecmp($answer,'y',1)) {
                            $this->doRemoveUserFromRole($user['id'], $role['id']);
                        } else if(!strncasecmp($answer,'a',1)) {
                            $this->doRemoveUserFromRole($user['id'], $role['id']);
                            $addAll=true;
                        } else {
                                echo "       skipping \n";
                                continue;
                        }
                    } else {
                        echo "Yes\n";
                        $this->doRemoveUserFromRole($user['id'], $role['id']);
                    }
                } else {
                    echo '   user '.$user['username'].' doesn\'t have role \''.$role['name']."'\n";
                }
            }
        }
    }

    private function grantPermissionsRoles($pwhere, $rwhere)
    {
        $psql = 'SELECT p.* FROM permissions p WHERE '.join($pwhere, ' AND ');
        $rsql = 'SELECT r.* FROM roles r WHERE '.join($rwhere, ' AND ');

        $rcommand = Yii::app()->db->createCommand($rsql);
        $roles = $rcommand->queryAll();

        $pcommand = Yii::app()->db->createCommand($psql);
        $permissions = $pcommand->queryAll();

        $addAll = false;
        foreach($permissions as $permission){
            foreach($roles as $role){
                $test = Yii::app()->db->createCommand('SELECT permissions_id FROM roles_has_permissions WHERE permissions_id='.$permission['id'].' AND roles_id='.$role['id'])->queryScalar();
                if(empty($test)){
                    echo '   add permission ['.$permission['id'].':'.$permission['name'].':'.$permission['controller'].'/'.$permission['action'].'] to role ['.$role['id'].':'.$role['name'].']?      [Yes|No|All|Cancel] ';
                    if(!$addAll){
                        $answer = trim(fgets(STDIN));
                        if(!strncasecmp($answer,'c',1)){
                            return;
                        } else if(!strncasecmp($answer,'y',1)) {
                            $this->doAddPermissionToRole($permission['id'], $role['id']);
                        } else if(!strncasecmp($answer,'a',1)) {
                            $this->doAddPermissionToRole($permission['id'], $role['id']);
                            $addAll=true;
                        } else {
                                echo "       skipping \n";
                                continue;
                        }
                    } else {
                        echo "Yes\n";
                        $this->doAddPermissionToRole($permission['id'], $role['id']);
                    }
                } else {
                    echo '   role '.$role['name'].' already has the permission \''.$permission['name']."'\n";
                }
            }
        }
    }

    private function revokePermissionsRoles($pwhere, $rwhere)
    {
        $psql = 'SELECT p.* FROM permissions p WHERE '.join($pwhere, ' AND ');
        $rsql = 'SELECT r.* FROM roles r WHERE '.join($rwhere, ' AND ');

        $rcommand = Yii::app()->db->createCommand($rsql);
        $roles = $rcommand->queryAll();

        $pcommand = Yii::app()->db->createCommand($psql);
        $permissions = $pcommand->queryAll();

        $addAll = false;
        foreach($permissions as $permission){
            foreach($roles as $role){
                $test = Yii::app()->db->createCommand('SELECT permissions_id FROM roles_has_permissions WHERE permissions_id='.$permission['id'].' AND roles_id='.$role['id'])->queryScalar();
                if(!empty($test)){
                    echo '   remove permission ['.$permission['id'].':'.$permission['name'].':'.$permission['controller'].'/'.$permission['action'].'] from role ['.$role['id'].':'.$role['name'].']?      [Yes|No|All|Cancel] ';
                    if(!$addAll){
                        $answer = trim(fgets(STDIN));
                        if(!strncasecmp($answer,'c',1)){
                            return;
                        } else if(!strncasecmp($answer,'y',1)) {
                            $this->doRemovePermissionFromRole($permission['id'], $role['id']);
                        } else if(!strncasecmp($answer,'a',1)) {
                            $this->doRemovePermissionFromRole($permission['id'], $role['id']);
                            $addAll=true;
                        } else {
                                echo "       skipping \n";
                                continue;
                        }
                    } else {
                        echo "Yes\n";
                        $this->doRemovePermissionFromRole($permission['id'], $role['id']);
                    }
                } else {
                    echo '   role '.$role['name'].' doesn\'t have the permission \''.$permission['name']."'\n";
                }
            }
        }
    }

    private function grantRevokeCommand($args)
    {
        $from1 = array();
        $where1 = array();
        $from2 = array();
        $where2 = array();

        $i = 1;
        while(!empty($args[$i])){
            if(eregi('(users|roles|permissions)=', $args[$i])){
                $criteriaStartIndex[] = $i;
            }
            $i++;
        }
        
        if(count($criteriaStartIndex) !=2){
            echo $this->_grantHelpText;
            return;
        }


        $this->parseSearchCriteria(array_slice($args, $criteriaStartIndex[0], $criteriaStartIndex[1]-$criteriaStartIndex[0]), &$from1, &$where1);

        $this->parseSearchCriteria(array_slice($args, $criteriaStartIndex[1]), &$from2, &$where2);

        if(in_array('users u', $from1) && in_array('roles r', $from2)){
            call_user_func_array(array($this, $args[0].'UsersRoles'), array($where1, $where2));
        } else if(in_array('roles r', $from1) && in_array('users u', $from2)){
            call_user_func_array(array($this, $args[0].'UsersRoles'), array($where2, $where1));
        } else if(in_array('roles r', $from1) && in_array('permissions p', $from2)){
            call_user_func_array(array($this, $args[0].'PermissionsRoles'), array($where2, $where1));
        } else if(in_array('permissions p', $from1) && in_array('roles r', $from2)){
            call_user_func_array(array($this, $args[0].'PermissionsRoles'), array($where1, $where2));
        } else {
            echo "Sorry, can't process these directly\n";
        }

        /*
        print_r($from1); print_r($where1);
        print_r($from2); print_r($where2);
         * 
         */
    }

    private function searchCommand($args)
    {
        $select = '';
        $from = array();
        $where = array();

        if(!preg_match('/^(users|roles|permissions)$/', $args[1], $regs)){
            echo $this->_helpText;
            return;
        }
        switch($regs[1]){
            case 'users':
                $select = 'u.id, u.username, CONCAT(u.firstname, " ", u.lastname) as realname, u.email';
                $criteriaTable = 'users';
                $printFields = array('ID', 'Username', 'Realname', 'Email');
                $from[] = 'users u';
                break;
            case 'roles':
                $select = 'r.id, r.name, r.description';
                $criteriaTable = 'roles';
                $printFields = array('ID', 'Name', 'Description');
                $from[] = 'roles r';
                break;
            case 'permissions':
                $criteriaTable = 'permissions';
                $printFields = array('ID', 'Name', 'Description', 'Controller.ID', 'Action.ID', 'Business rule');
                $select = 'p.id, p.name, p.description, p.controller, p.action, p.bizrule';
                $from[] = 'permissions p';
                break;
            default:
                return;
        }
        
        $this->parseSearchCriteria(array_slice($args, 2), &$from, &$where);

        $sql = 'SELECT DISTINCT '.$select.' FROM '.join($from, ', ');
        if(count($where)){
            $sql .= ' WHERE '.join($where, ' AND ');
        }
        
        //echo $sql."\n";

        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();

        ConsoleTablePrinter::printTable($printFields, $data);
        $this->printRbacSearchCriteria($criteriaTable, $data);

        echo "\n".'Search query returned '.count($data).' rows'."\n";
    }

    private function createCommand($args)
    {
        switch($args[1]){
            case 'role':
                $this->createRoleCommand($args);
                break;
            case 'permission':
                $this->createPermissionCommand($args);
                break;
            case 'user':
                echo 'Sorry, we can`t create users'."\n";
                break;
            default:
                echo $this->_createHelpText;
                break;
        }
    }

    private function createRoleCommand($args)
    {
        $name = '';
        $description = '';
        while(empty($name)){
            echo '   Enter role name: ';
            $name = trim(fgets(STDIN));
        }
        while(empty($description)){
            echo '   Enter role description: ';
            $description = trim(fgets(STDIN));
        }

        $sql = 'INSERT INTO roles SET name=:name, description=:description';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":name", $name, PDO::PARAM_STR);
        $command->bindParam(":description", $description, PDO::PARAM_STR);
        $data = $command->execute();

        echo 'Created role \''.$name.'\' with id='.Yii::app()->db->getLastInsertId()."\n";
    }

    private function createMissingPermissions($controller)
    {
        $actions = $this->getControllerActions($controller);
        $addAll = false;
        foreach($actions as $a){
            $name = '';
            $description = '';
            $bizrule = '';

            $sql = 'SELECT COUNT(*) FROM permissions WHERE controller=\''.$controller.'\' AND action=\''.$a.'\'';
            $test = Yii::app()->db->createCommand($sql)->queryScalar();

            if($test) continue;

            if(!$addAll){
                echo 'Create permission for the '.$controller.'/'.$a.' action? [Yes|No|All|Cancel]';
                $answer = trim(fgets(STDIN));
                if(!strncasecmp($answer,'c',1)){
                    return;
                } else if(!strncasecmp($answer,'y',1)) {
                    $this->createPermissionCommand(array(), $controller, $a);
                } else if(!strncasecmp($answer,'a',1)) {
                    $addAll=true;
                } else {
                        echo "       skipping \n";
                        continue;
                }
            }
            if($addAll){
                echo 'Creating permission for the '.$controller.'/'.$a.' action'."\n";
                $this->createPermissionCommand(array(), $controller, $a);
            }
        }
    }

    private function createPermissionCommand($args, $controller = '', $action= '')
    {
        if(!empty($args[2]) && $controller == ''){
            $this->createMissingPermissions($args[2]);
            return;
        }
        
        $name = '';
        $description = '';
        $bizrule = '';

        while(empty($name)){
            echo '   Enter name: ';
            $name = trim(fgets(STDIN));
        }
        while(empty($description)){
            echo '   Enter description: ';
            $description = trim(fgets(STDIN));
        }
        while(empty($controller)){
            echo '   Enter controller id: ';
            $controller = trim(fgets(STDIN));
        }
        while(empty($action)){
            echo '   Enter action id: ';
            $action = trim(fgets(STDIN));
        }
        echo '   Enter bizrule (leave empty if not needed): ';
        $bizrule = trim(fgets(STDIN));


        $sql = 'INSERT INTO permissions SET name=:name, description=:description, controller=:controller, action=:action, bizrule=:bizrule';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":name", $name, PDO::PARAM_STR);
        $command->bindParam(":description", $description, PDO::PARAM_STR);
        $command->bindParam(":controller", $controller, PDO::PARAM_STR);
        $command->bindParam(":action", $action, PDO::PARAM_STR);
        $command->bindParam(":bizrule", $bizrule, PDO::PARAM_STR);
        $data = $command->execute();

        echo 'Created permission \''.$name.'\' with id='.Yii::app()->db->getLastInsertId()."\n";
    }

    private function printRbacSearchCriteria($table, $data)
    {
        if(count($data)){
            foreach($data as $el){
                $l[] = 'id:'.$el['id'];
            }
            echo 'RBAC search criteria: '.$table.'='.join($l, ',')."\n";
        }
    }
}




/**
 * 
 */
class ConsoleTablePrinter {

    private static function findMaxLengths($row, &$flens)
    {
        foreach($row as $key=>$val){
            $flens[$key] = max(strlen($val), $flens[$key]);
        }
    }

    private static function printRow($row, $flens)
    {
        echo '|';
        for($i=0; $i<count($row); $i++){
            echo ' '.$row[$i];
            for($k=0; $k<($flens[$i]-strlen($row[$i])); $k++){
                    echo " ";
            }
            echo ' |';
        }
        echo "\n";
    }

    /**
     *
     * @param <array> $header Array of field titles
     * @param <array> $data  Data table array
     */
    public static function printTable(array $header, array $data)
    {
        if(!count($data)){
            return;
        }
        $flens = array();

        ConsoleTablePrinter::findMaxLengths($header, $flens);
        foreach($data as $row){
            $row = array_values($row);
            ConsoleTablePrinter::findMaxLengths($row, $flens);
        }

        $borderRow = '+';
        for($i=0; $i<count($header); $i++){
            for($k=0; $k<$flens[$i]+2; $k++){
                $borderRow .= '-';
            }
            $borderRow .= '+';
        }

        echo $borderRow."\n";
        ConsoleTablePrinter::printRow($header, $flens);
        echo $borderRow."\n";

        foreach($data as $row){
            $row = array_values($row);
            ConsoleTablePrinter::printRow($row, $flens);
        }

        echo $borderRow."\n";
    }
}

