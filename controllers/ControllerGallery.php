<?php
class ControllerGallery
{
    private $_photoManager;

    public function __construct($url)
    {
        session_start();
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        else if (isset($_GET['page']))
            $this->displayGallery($_GET['page']);
        else
            $this->displayGallery(1);
    }

    public function displayGallery($page)
    {
        $this->_photoManager = new PhotoManager();
        $pictures = $this->_photoManager->getAllPictures();
        if (!$pictures)
        {
            header('Location: '. URL .'?url=login');
            $this->_view = new View('Login');
            $this->_view->generate(array());
        }
        else
        {
            $nb_results = 12;
            $nb_pages = ceil(($pictures / $nb_results));
            $pictures = $this->_photoManager->getPicturesPerPage($page);
            $this->_view = new View('Gallery');
            $this->_view->generate(array('pictures' => $pictures, 'nb_pages' => $nb_pages));
        }
    }
}