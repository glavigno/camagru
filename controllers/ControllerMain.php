<?php

class ControllerMain
{
    private $_userManager;
    private $_photoManager;
    private $_view;

    public function __construct($url)
    {
        session_start();
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        if (!isset($_SESSION['user'])) 
        {
            $this->_view = new View('Login');
            $this->_view->generate(array());
        }
        else
        {
            if ($_GET['save'] === 'ok')
            {
                $data = $_POST["picture"];
                $filter = $_POST["filter"];
                $x = $_POST["x"];
                $y = $_POST["y"];
                $this->_photoManager = new PhotoManager();
                $this->_photoManager->save($data, $filter, $x, $y);
            }
            else
            {
                $this->_photoManager = new PhotoManager();
                $pictures = $this->_photoManager->getUserPictures();
                $this->_view = new View('Main');
                $this->_view->generate(array('pictures' => $pictures));
            }
        } 
    }
}