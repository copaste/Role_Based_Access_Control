<?php

class Acl
{
    /**
     * Roles storage
     *
     * @var array
     */
    protected $roles = array();
    
    /**
     * Roles Ids/priorities 
     *
     * @var array
     */
    protected $roleId = array();
    
    /**
     * Resource tree
     *
     * @var array
     */
    protected $resources = array();
    
    /**
     * Parents storage - for feature use :)
     *
     * @var array
     */
    protected $parents = array();
    
    /**
     * Default role if none exist in ACL
     *
     * @var const
     */
    const DEFAULT_ROLE = 'guest';
     /**
     * Constructor
     *
     * @param array $config
     * @return void
     * @throws \Exception
     */
    public function __construct(array $config = array())
    {
        if(! empty($config) )
        {
            if (!isset($config['acl']['roles']) || !isset($config['acl']['resources'])) {
                throw new \Exception('Invalid ACL Config found');
            }

            $roles = $config['acl']['roles'];
            if (!isset($roles[self::DEFAULT_ROLE])) {
                $roles[self::DEFAULT_ROLE] = null;
            }

            $this->addRoles($roles)
                 ->addResources($config['acl']['resources']);
        }
    }
    
    /**
     * Add/Append Role to ACL
     *
     * The $parent is to indicate the Role
     * from which the newly added Role will directly inherit.
     *
     * @param string $name
     * @param string $parent
     * @return mixed|void
     */
    public function addRole($name, $parent=null)
    {
        if( in_array($name, $this->roles) )
        {
            throw new \Exception('Role ' . $name . ' already exists.');
        }
        
        $roles = $this->roles;
        $this->roles = array_merge(
            array_slice($roles, 0, array_search($parent, $roles)+1), 
            array($name), 
            array_slice($roles, array_search($parent, $roles)+1, count($roles)) 
        );
        $this->reArrangeIds();
        
        return $this;
    }
    
    /**
     * Re-arrange the list with Roles
     *
     * @return mixed|void
     */
    protected function reArrangeIds()
    {
        foreach($this->roles as $id => $name)
        {
            $this->roleId[$name] = $id;
        }
        return $this;
    }
    
    /**
     * Adds Roles to ACL
     *
     *
     * @param  array $roles
     * @return mixed|void
     */
    public function addRoles(array $roles)
    {
        foreach($roles as $name => $parent)
        {
            $this->roles[] = $name; 
            $this->roleId[$name] = array_search($name, $this->roles);
        }
        return $this;
    }
    
    /**
     * Add Resources for different roles.
     *
     *
     * @param  array $acl_resources
     * @return mixed|void
     */
    public function addResources(array $acl_resources)
    {
        foreach($acl_resources as $access => $resources)
        {
            foreach($resources as $name => $privilege)
            {
                $this->resources[$access][$name] = $privilege;
            }	
        }
        return $this;
    }
    
    /**
     * Returns true if and only if the Resource exists in the registry
     *
     *
     * @param  string $resource
     * @return bool true|false
     */
    public function hasResource($resource)
    {
        return ( isset($this->resources['allow'][$resource]) || isset($this->resources['deny'][$resource]) );
    }
    
    /**
     * Returns true if and only if the Role exists in the registry
     *
     * 
     *
     * @param  string $resource
     * @return bool true|false
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }
    
    /**
     * Returns true if and only if the Role has access to the Resource
     *
     *
     * @param  string $role
     * @param  string $resource
     * @param  string $privilege
     * @return bool true|false
     */
    public function isAllowed($role, $resource, $privilege)
    {
        
        if( isset($this->resources['deny'][$resource]) &&
            ( isset($this->resources['deny'][$resource][$privilege]) || 
              isset($this->resources['deny'][$resource]['all'])
            )
        )
        {
            $setRole = isset($this->resources['deny'][$resource]['all']) ?
                        $this->resources['deny'][$resource]['all']:
                        $this->resources['deny'][$resource][$privilege];
                        
            return ($this->roleId[$role]>$this->roleId[$setRole]);
        }
        
        if( isset($this->resources['allow'][$resource]) &&
            ( isset($this->resources['allow'][$resource][$privilege]) || 
              isset($this->resources['allow'][$resource]['all'])
            )
        )
        {
            $setRole = isset($this->resources['allow'][$resource]['all']) ?
                        $this->resources['allow'][$resource]['all']:
                        $this->resources['allow'][$resource][$privilege];
                        
            return ($this->roleId[$role]>=$this->roleId[$setRole]);
        }
    
        return false;
    }
}
