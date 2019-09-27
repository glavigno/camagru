<?php
class ControllerLogin
{
    private $_userManager;
    private $_user;
    private $_photoManager;
    private $_view;

    public function __construct($url)
    {
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        else if (isset($_GET['key']))
            $this->checkNewUser($_GET['key']);
        else if (isset($_GET['submit']) && $_GET['submit'] === 'ok')
            $this->checkLog();
        else
            $this->showBasicLoginPage();
    }

    public function checkNewUser($key)
    {
        $this->_userManager = new UserManager();
        $this->_userManager->checkNewAccount($key);
        $this->_view = new View('Login');
        $notification = "Account confirmed ! You can now log in.";
        $this->_view->generate(array('notification' => $notification));
    }
    
    public function checkLog()
    {
        $this->_userManager = new UserManager();
        $res = $this->_userManager->logUser();
        if ($res)
        {
            $this->_photoManager = new PhotoManager();
            $pictures = $this->_photoManager->getUserPictures();
            header('Location: '. URL .'?url=main');
            $this->_view = new View('Main');
            $this->_view->generate(array('pictures' => $pictures));
        }
        else
        {
            $notification = "Wrong login / password combination...";
            $this->_view = new View('Login');
            $this->_view->generate(array('notification' => $notification));
        }
    }

    public function showBasicLoginPage()
    {
        $this->_view = new View('Login');
        $this->_view->generate(array());
    }
}