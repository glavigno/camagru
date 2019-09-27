<?php
class ControllerReset
{
    private $_userManager;
    private $_view;

    public function __construct($url)
    {
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        elseif (isset($_GET['email']) && $_GET['email'] === 'ok')
            $this->sendResetMail();
        elseif (isset($_GET['login']))
            $this->confirmResetMail();
        elseif (isset($_GET['done']) && $_GET['done'] === 'ok')
            $this->completeResetMail();
        else
        {
            $status = 0;
            $this->_view = new View('Reset');
            $this->_view->generate(array('status' => $status));
        }
    }

    public function sendResetMail() {
        session_start();
        $this->_userManager = new UserManager();
        $res = $this->_userManager->resetPassword($_POST['email']);
        if ($res)
            $message = "Check your mail box";
        else
            $message = "This email does not match with any account on our platform";
        $this->_view = new View('Reset');
        $this->_view->generate(array('message' => $message));
    }

    public function confirmResetMail() {
        session_start();
        $_SESSION['resetUser'] = $_GET['login'];
        $status = 1;
        $this->_view = new View('Reset');
        $this->_view->generate(array('status' => $status));
    }

    public function completeResetMail() {
        session_start();
        $this->_userManager = new UserManager();
        $this->_userManager->setNewPassword($_SESSION['resetUser']);
    }
}