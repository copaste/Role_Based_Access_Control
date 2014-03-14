<?php

/*
* Best practice in MVC is to set Resource to controller path + class name 
* for example "/admin/settings" or to NAMESPACE "Admin\Controller\Index", 
* and privilege to class method - "index".
*
*/

$config = array(
    'acl' => array(
        'roles' => array(
            'guest'   => null,
            'member'  => 'guest',
            'admin'  => 'member',
        ),
        'resources' => array(
            'allow' => array(
                'acl/IndexController' => array(
                    'index'   => 'guest',
                    'create'   => 'member',
                    'update'   => 'member',
                    'delete'   => 'admin',
                ),
            ),
            'deny' => array(
                'acl/IndexController' => array(
                    'delete' => 'member',
                ),
            )
        )
    )
);