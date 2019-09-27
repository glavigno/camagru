<?php
class ControllerRegister
{
    private $_userManager;
    private $_view;

    public function __construct($url)
    {
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        else if (isset($_GET['submit']) && $_GET['submit'] === 'ok')
        {
            $this->_userManager = new UserManager();
            $res = $this->_userManager->registerCheck();
            if ($res == false)
            {
                $this->_view = new View('Register');
                $this->_view->generate(array());
            }
            else
                $this->accountConfirm();
        }
        else
        {
            $this->_view = new View('Register');
            $this->_view->generate(array());
        }
    }

    public function accountConfirm() {
        $this->_view = new View('Login');
        $notfication = "Check your mailbox to confirm your account";
        $this->_view->generate(array('notification' => $notfication));
    }
}