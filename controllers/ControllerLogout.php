<?php
class ControllerLogout
{
    private $_userManager;
    private $_view;

    public function __construct($url)
    {
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        else
            $this->logoutUser();
    }

    public function logoutUser() {
        session_start();
        $user = $_SESSION['user'];
        $this->_userManager = new UserManager();
        $this->_userManager->logOut();
        header('Location: '. URL .'?url=gallery');
        $this->_view = new View('Gallery');
    }
}