<?php
class ControllerAdmin
{
    private $_userManager;
    private $_photoManager;
    private $_view;

    public function __construct($url)
    {
        session_start();
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        else if (!isset($_SESSION['user']))
        {
            $this->_view = new View('Login');
            $this->_view->generate(array());
        }
        else if ($_GET['passwd'] === 'ok')
        {
            $this->_userManager = new UserManager();
            $res = $this->_userManager->updatePassword(); 
        }
        else if (isset($_GET['login']) || isset($_GET['email']) || isset($_GET['notification']))
        {
            if ($_GET['login'] === 'ok')
                $this->successLogin();
            else if ($_GET['email'] === 'ok')
                $this->successEmail();
            else if ($_GET['notification'] === 'ok')
                $this->successNotif();
            else
            {
                header('Location: '. URL .'?url=admin');
                $this->entryDashboard();
            }
        }
        else if ($_GET['del_account'] === 'ok')
            $this->delUser();
            
        else
            $this->entryDashboard();
    }

    public function successLogin() {
        $this->_userManager = new UserManager();
        if ($this->_userManager->checkNewLogin())
            $this->_userManager->updateLogin();
    }
    
    public function successEmail() {
        $this->_userManager = new UserManager();
        if ($this->_userManager->checkNewEmail())
            $this->_userManager->updateEmail();
    }

    public function successNotif() {
        $this->_userManager = new UserManager();
        $this->_userManager->updateNotification();

    }

    public function delUser() {
        $this->_userManager = new UserManager();
        $res = $this->_userManager->delAccount(); 
        $this->_userManager->logOut(); 
        $this->_view = new View('Login');
        $this->_view->generate(array());
    }

    public function entryDashboard() {
        $this->_userManager = new UserManager();
        $notifStatus = $this->_userManager->getNotifStatus(); 
        $this->_view = new View('Admin');
        $this->_view->generate(array('notifStatus' => $notifStatus));   
    }
}