<?php
class PhotoManager extends Model
{
    private $_userManager;
    private $_query;

    // merge and save picture method
    
    public function save($data, $filter, $offsetX, $offsetY)
    {
        session_start();
        $data = str_replace(' ', '+', $data);
        $image_parts = explode(";base64,", $data);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $id_pic = uniqid().'.png';

        // save picture locally
        $file = UPLOAD_DIR.$id_pic;
        file_put_contents($file, $image_base64);
        
        // merge picture and filter
        list($height, $width) = getimagesize($file);
        $final_img = imagecreatetruecolor($height, $width);
        $dest = imagecreatefrompng($file);
        imagecopy($final_img, $dest, 0, 0, 0, 0, $height, $width);
        list($x, $y) = getimagesize($filter);
        $src = imagecreatefrompng($filter);
        imagecopy($final_img, $src, $offsetX, $offsetY, 0, 0, $x, $y);
        header('Content-Type: image/png');
        $dest = UPLOAD_DIR.uniqid("camagru").'.png';
        imagepng($final_img, $dest);
        unlink($file);

        // save local picture path in the database
        $this->_userManager = new UserManager();
        $id = $this->_userManager->getUserId($_SESSION['user']);
        date_default_timezone_set('Europe/Paris');
        $upload_date = date("Y-m-d H:i:s");
        $this->_query = 'INSERT INTO `photo` (`user_id`, `source`, `upload_date`) VALUES (:id, :dest, :upload_date)';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->bindParam(':dest', $dest, PDO::PARAM_STR);
        $req->bindParam(':upload_date', $upload_date, PDO::PARAM_STR);
        $req->execute();
        $this->_query = 'SELECT `id` FROM `photo` WHERE `user_id` = :id ORDER BY `id` DESC';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchColumn();
        echo $res;
        $req->closeCursor();
        return true;
    }
    
    // manage pictures methods

    // get the total number of pictures in the database

    public function getAllPictures()
    {
        $this->_query = 'SELECT COUNT(*) FROM `photo`';
        $req = $this->getDb()->prepare($this->_query);
        $req->execute();
        $res = (int)$req->fetchColumn();
        $req->closeCursor();
        return $res;
    }

    // compute the number of pictures to be displayed per page

    public function getPicturesPerPage($page)
    {
        // $results_start = ($page - 1) * 6;
        $results_start = ($page - 1) * 12;
        // $this->_query = 'SELECT `id`, `source` FROM `photo`  ORDER BY `id` DESC LIMIT '. $results_start . ', ' . 6 . '';
        $this->_query = 'SELECT `id`, `source` FROM `photo`  ORDER BY `id` DESC LIMIT '. $results_start . ', ' . 12 . '';
        $req = $this->getDb()->prepare($this->_query);
        $req->execute();
        $pictures = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC))
        {
            $this->_query = 'SELECT COUNT(*) FROM `like` WHERE `photo_id` = :photo_id';
            $sncdReq = $this->getDb()->prepare($this->_query);
            $sncdReq->bindParam(':photo_id', $data['id'], PDO::PARAM_INT);
            $sncdReq->execute();
            $nb_likes = $sncdReq->fetchColumn();
            $data['likes'] = $nb_likes;
            $this->_query = 'SELECT COUNT(*) FROM `comment` WHERE `photo_id` = :photo_id';
            $scndReq = $this->getDb()->prepare($this->_query);
            $scndReq->bindParam(':photo_id', $data['id'], PDO::PARAM_INT);
            $scndReq->execute();
            $nb_comments = $scndReq->fetchColumn();
            $data['comments'] = $nb_comments;
            $pictures[] = $data;
        }
        $req->closeCursor();
        $scndReq->closeCursor();
        return $pictures;
    }

    // retrieve a given user's pictures

    public function getUserPictures()
    {
        session_start();
        $this->_userManager = new UserManager();
        $id = $this->_userManager->getUserId($_SESSION['user']);
        $this->_query = 'SELECT `id`, `source` FROM `photo` WHERE `user_id` = :id ORDER BY `id` DESC';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $pictures = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC))
            $pictures[] = $data; 
        $req->closeCursor();
        return $pictures;
    }

    // retrieve a picture given its id in the database

    public function getPictureById($id)
    {
        $this->_query = 'SELECT `source` FROM `photo` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $picture = $req->fetchColumn();
        $req->closeCursor();
        return $picture;
    }

    // retrieve the login of the picture's owner

    public function getPictureAuthor($id)
    {
        $this->_query = 'SELECT `login` FROM `user` INNER JOIN `photo` ON `user`.`id` = `photo`.`user_id` WHERE `photo`.`id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $login = $req->fetchColumn();
        $req->closeCursor();
        return $login;
    }

    // retrieve the picture upload date

    public function getPictureDate($id)
    {
        $this->_query = 'SELECT `upload_date` FROM `photo` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $date = strtotime($req->fetchColumn());
        $req->closeCursor();
        return $date;
    }

    // delete all elements linked to a picture

    public function deletePicture($id)
    {
        // delete all comment associated with the picture
        $this->_query = 'DELETE FROM `comment` WHERE `photo_id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        // delete all likes associated with the picture
        $this->_query = 'DELETE FROM `like` WHERE `photo_id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        // delete the local copy of the image
        $this->_query = 'SELECT `source` FROM `photo` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchColumn();
        unlink($res);

        // delete all the info associated to the picture in the database
        $this->_query = 'DELETE FROM `photo` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $login = $req->fetchColumn();

        $req->closeCursor();
    }

    // manage comments methods

    // retrieve all comments of a picture

    public function getComments($id)
    {
        $this->_query = 'SELECT `id`, `author`, `content` FROM `comment` WHERE `photo_id` = :id ORDER BY `id` DESC';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->execute();
        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC))
            $comments[] = $data;
        $req->closeCursor();
        return $comments;
    }

    public function getCommentAuthor($id)
    {
        $this->_query = 'SELECT `author` FROM `comment` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->execute();
        $res = $req->fetchColumn();
        return $res;
    }
    
    // add comment on a picture 

    public function addComment($id, $content, $author)
    {
        $this->_query = 'INSERT INTO `comment` (`photo_id`, `content`, `author`) VALUES (:id, :content, :author)';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->bindParam(':content', $content, PDO::PARAM_STR); 
        $req->bindParam(':author', $author, PDO::PARAM_STR); 
        $req->execute();
        $comments = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC))
        $comments[] = $data;
        
        $this->_query = 'SELECT `user_id` FROM `photo` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->execute();
        $res = $req->fetchColumn();

        $this->_query = 'SELECT `login` FROM `user` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $res, PDO::PARAM_INT); 
        $req->execute();
        $picOwner = $req->fetchColumn();

        $this->_query = 'SELECT `id` FROM `comment` ORDER BY `id` DESC';
        $req = $this->getDb()->prepare($this->_query);
        $req->execute();
        $commId = $req->fetchColumn();
        echo $commId;

        $this->_query = 'SELECT `notification` FROM `user` WHERE `id` = :res';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':res', $res, PDO::PARAM_INT); 
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();
        if ($res && ($picOwner != $author))
            $this->sendNotifMail($id);
        return $comments;
    }

    // send an email to the picture's owner on comments

    public function sendNotifMail($id)
    {
       $this->_query = 'SELECT `user_id` FROM `photo` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchColumn();

        $this->_query = 'SELECT `email` FROM `user` WHERE `id` = :res';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':res', $res, PDO::PARAM_INT); 
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();

        $subject = 'New comment on your picture !';
        $headers = 'From: camagru' . "\r\n";
        $headers .= 'Reply-To: camagru' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
            
        $message = 'Someone comment your picture';
        mail($res, $subject, $message, $headers);
    }

    // delete comment 

    public function deleteComment($id)
    {
        $this->_query = 'DELETE FROM `comment` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $req->closeCursor();
    }

    // manage likes methods
    
    // like a given post

    public function likePost($id, $login)
    {
        $this->_query = 'SELECT COUNT(*) FROM `like` WHERE `photo_id` = :id AND `login` = :login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $res = $req->fetchColumn();
        if ($res)
            $this->_query = 'DELETE FROM `like` WHERE `login` = :login AND `photo_id` = :id';
        else
            $this->_query = 'INSERT INTO `like` (`photo_id`, `login`) VALUES (:id, :login)';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }
    
    // retrieve the total number of likes for a picture

    public function getLikes($id)
    {
        $this->_query = 'SELECT COUNT(*) FROM `like` WHERE `photo_id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $nb_likes = $req->fetchColumn();
        $req->closeCursor();
        return $nb_likes;
    }
    
    // return a boolean depending if the photo is liked or not by the user logged

    public function alreadyLiked($photo_id)
    {
        session_start();
        $user_logged =  $_SESSION['user'];
        $this->_query = 'SELECT * FROM `like` WHERE `login` = :user_logged AND `photo_id` = :photo_id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
        $req->bindParam(':photo_id', $photo_id, PDO::PARAM_INT); 
        $req->execute();
        $res = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        if ($res)
            return true;
        else
            return false;

    }
}