<?php
class User
{
    private $_id;
    private $_login;
    private $_password;
    private $_lastName;
    private $_firstName;
    private $_email;
    private $_notification;
    private $_admin_rights;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    // call all the setters matching the keys of the array passed in parameter 

    public function hydrate(array $data)
    {
        $tab = [];
        foreach($data as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method))
                $this->$method(htmlentities($value));
        }
        return $tab;
    }

    // setters

    public function setLogin($login)
    {
        $this->_login = $login;
    }

    public function setPassword($password)
    {
        $this->_password = hash('whirlpool', $password);
    }

    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function setNotification($notification)
    {
        $this->_notification = $notification;
    }

    public function setAdminRights($admin_rights)
    {
        $this->_admin_rights = $admin_rights;
    }

    // getters

    public function login()
    {
        return $this->_login;
    }

    public function password()
    {
        return $this->_password;
    }

    public function lastName()
    {
        return $this->_lastName;
    }

    public function firstName()
    {
        return $this->_firstName;
    }
    
    public function email()
    {
        return $this->_email;
    }

    public function getInfo()
    {
        $values = [
            $this->_login,
            $this->_email,
            $this->_firstName,
            $this->_lastName,
            $this->_password
        ];
        return $values;
    }
}