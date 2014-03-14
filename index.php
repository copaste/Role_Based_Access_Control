<?php
require_once('acl.php');
require_once('config.php');

class IndexController extends Acl
{
    public $role = 'guest'; // You can test with other role like - (guest, member, admin)
    
    public function __construct($config)
    {
        parent::__construct($config);
    }
    
    public function index()
    {
        $resource = basename(__DIR__) . '/'. __CLASS__;
        $privilege = explode('::', __METHOD__);
        $privilege = $privilege[1];
        
        if($this->isAllowed($this->role, $resource, $privilege))
        {
            echo "<p>User with role '{$this->role}' <b>has access</b> to <b>{$privilege}</b> action.</p>";
        }
        else
        {
            echo "<p>User with role '{$this->role}' <b>doesn't have access</b> to <b>{$privilege}</b> action.</p>";
        }
    }
    
    public function create()
    {
        $resource = basename(__DIR__) . '/'. __CLASS__;
        $privilege = explode('::', __METHOD__);
        $privilege = $privilege[1];
        
        if($this->isAllowed($this->role, $resource, $privilege))
        {
            echo "<p>User with role '{$this->role}' <b>has access</b> to <b>{$privilege}</b> action.</p>";
        }
        else
        {
            echo "<p>User with role '{$this->role}' <b>doesn't have access</b> to <b>{$privilege}</b> action.</p>";
        }
    }
    
    public function update()
    {
        $resource = basename(__DIR__) . '/'. __CLASS__;
        $privilege = explode('::', __METHOD__);
        $privilege = $privilege[1];
        
        if($this->isAllowed($this->role, $resource, $privilege))
        {
            echo "<p>User with role '{$this->role}' <b>has access</b> to <b>{$privilege}</b> action.</p>";
        }
        else
        {
            echo "<p>User with role '{$this->role}' <b>doesn't have access</b> to <b>{$privilege}</b> action.</p>";
        }
    }

    public function delete()
    {
        $resource = basename(__DIR__) . '/'. __CLASS__;
        $privilege = explode('::', __METHOD__);
        $privilege = $privilege[1];
        
        if($this->isAllowed($this->role, $resource, $privilege))
        {
            echo "<p>User with role '{$this->role}' <b>has access</b> to <b>{$privilege}</b> action.</p>";
        }
        else
        {
            echo "<p>User with role '{$this->role}' <b>doesn't have access</b> to <b>{$privilege}</b> action.</p>";
        }
    }
}

$index = new IndexController($config);
$index->index();
$index->update();
$index->delete();
$index->create();

