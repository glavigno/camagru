<?php
class ControllerPost
{
    private $_photoManager;

    public function __construct($url)
    {
        session_start();
        if (isset($url) && count($url) > 1)
            throw new Exception('Page does not exist');
        if (isset($_GET['id']))
        {
            session_start();
            $picture_id = $_GET['id'];
            $user_logged = $_SESSION['user'];
            $this->_photoManager = new PhotoManager();
            $ownerPic = $this->_photoManager->getPictureAuthor($picture_id);
            if (isset($_GET['del']) && $_GET['del'] === 'ok')
            {
                if ($user_logged == $ownerPic)
                    $this->deletePicture($picture_id);
                header('Location: '. URL .'?url=gallery');
                $this->_view = new View('Gallery');
                $this->_view->generate(array());
            }
            else
            {
                if (isset($_GET['comment']) && $_GET['comment'] === 'ok')
                    $this->_photoManager->addComment($picture_id, htmlentities($_POST['content']), $user_logged);
                else if (isset($_GET['like']) && $_GET['like'] === 'ok')
                    $this->_photoManager->likePost($picture_id, $user_logged);
                else
                {
                    if (isset($_GET['commid']))
                    {
                        $ownerComm = $this->_photoManager->getCommentAuthor($_GET['commid']);
                        if ($user_logged == $ownerComm)
                            $this->_photoManager->deleteComment($_GET['commid']);
                    }
                    $this->generatePost($picture_id);
                }
            }
        }
        else
        {
            $this->_view = new View('Gallery');
            $this->_view->generate(array());
        }
    }

    public function deletePicture($id) {
        $this->_photoManager->deletePicture($id);
        $pictures = $this->_photoManager->getUserPictures();
        // $this->_view = new View('Main');
        // $this->_view->generate(array('pictures' => $pictures));
    }

    public function generatePost($picture_id) {
        $login = $this->_photoManager->getPictureAuthor($picture_id);
        $nb_likes = $this->_photoManager->getLikes($picture_id);
        $liked_or_not = $this->_photoManager->alreadyLiked($picture_id);
        $picture = $this->_photoManager->getPictureById($picture_id);
        $comments = $this->_photoManager->getComments($picture_id);
        $date = $this->_photoManager->getPictureDate($picture_id);
        $this->_view = new View('Post');
        $this->_view->generate(array(
            'id' => $picture_id, 
            'login' => $login, 
            'picture' => $picture, 
            'comments' => $comments, 
            'nb_likes'=> $nb_likes,
            'liked_or_not' => $liked_or_not,
            'date' => $date));
    }
}