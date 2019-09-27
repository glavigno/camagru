<?php
class UserManager extends Model
{
    private $_user;
    private $_query;

    public function getUsers()
    {
        return $this->getAll('user', 'User');
    }
    
    // registration methods

    // check entries of a new user

    public function registerCheck()
    {
        $pwd_strength = preg_match_all('/^.*(?=.{8,})((?=.*[\W]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/', htmlentities($_POST['password']));
        $this->_user = new User($_POST);
        $email = $this->_user->email();
        $login = $this->_user->login();
        $this->_query = 'SELECT * FROM `user` WHERE `email` = :email OR `login` =  :login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':login', $login, PDO::PARAM_STR); 
        $req->bindParam(':email', $email, PDO::PARAM_STR); 
        $req->execute();
        $res = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        if ($res == false && $pwd_strength)
        {
            $res = $this->registerUser($this->_user);
            return true;
        }
        else
        {
            header('Location: '. URL .'?url=register');
            return false;
        }
    }

    // register the new user in the database if checks where successful

    public function registerUser($user)
    {
        $values = $user->getInfo();
        $keys = array_keys($_POST);
        $this->_query = 'INSERT INTO `user`'.' (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\');';
        $req = $this->getDb()->prepare($this->_query);
        $req->execute();
        $req->closeCursor();

        $key = uniqid("KEY");
        $login = $user->login();
        $this->_query = 'UPDATE `user` SET `unique_key`= :key WHERE `login` = :login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':key', $key, PDO::PARAM_STR);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
        $this->sendConfirmationMail($user->email(), $key);
    }

    // send a mail to account confirmation mail

    public function sendConfirmationMail($to, $key)
    {
        $subject = 'Account confirmation';
        $headers = 'From: camagru' . "\r\n";
        $headers .= 'Reply-To: camagru' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
        $message = 'Welcome ! <a href="' . URL .'?url=login&key=' . $key . '">Click here to confirm your account !</a>';
        mail($to, $subject, $message, $headers);
    }

    // login methods
    
    // log user in

    public function logUser()
    {
        $this->_user = new User($_POST);
        $login = $this->_user->login();
        $passwd = $this->_user->password();
        $this->_query = 'SELECT * FROM `user` WHERE `login` = :login AND `password` = :passwd AND `confirmed` = 1';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->bindParam(':passwd', $passwd, PDO::PARAM_STR);
        $req->execute();
        $res = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();
        if ($res != false)
        {
            $this->startSession();
            return $res;
        }
        else
            return null;
    }
    
    // log user out

    public function logOut()
    {
        session_start();
        if (isset($_SESSION['user']))
            $_SESSION['user'] = null;
    }
    
    // check validity of a new account based on the unique key generated

    public function checkNewAccount($key)
    {
        $this->_query = 'SELECT * FROM `user` WHERE `unique_key` = :key';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':key', $key, PDO::PARAM_STR);
        $req->execute();
        $res = $req->fetch(PDO::FETCH_ASSOC);
        if ($res)
            $this->confirmAccount($key);
    }

    // confirm the new account created

    public function confirmAccount($key)
    {
        $this->_query = 'UPDATE `user` SET `confirmed` = NOT `confirmed` WHERE `unique_key` = :key';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':key', $key, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

    // general methods

    // assign the login value of the user logged in the $_SESSION global variable

    public function startSession()
    {
        session_start();
        $_SESSION['user'] = $this->_user->login();
    }
    
    // retrieve user_id based on his/her login

    public function getUserId($user_login)
    {
        $this->_query = 'SELECT `id` FROM `user` WHERE `login` = :user_login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_login', $user_login, PDO::PARAM_STR); 
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();
        if ($res)
            return $res;
        else
            return null;
    }

    // update methods
    
    // update login of the uszer in the different tables associated

    public function checkNewLogin()
    {
        $new_login = htmlentities($_POST['new_login']);
        $this->_query = 'SELECT * FROM `user` WHERE `login` = :login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':login', $new_login, PDO::PARAM_STR); 
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();
        if ($res)
        {
            echo 'Login already exists in the database';
            return false;
        }
        else
            return true;
    }

    public function updateLogin()
    {
        session_start();

        // update login in the user table
        $new_login = htmlentities($_POST['new_login']);
        $old_login = $_SESSION['user'];
        $this->_query = 'UPDATE `user` SET `login` = :new_login WHERE `login` = :old_login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':new_login', $new_login, PDO::PARAM_STR); 
        $req->bindParam(':old_login', $old_login, PDO::PARAM_STR); 
        $req->execute();

        // update login in the like table
        $this->_query = 'UPDATE `like` SET `login` = :new_login WHERE `login` = :old_login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':new_login', $new_login, PDO::PARAM_STR); 
        $req->bindParam(':old_login', $old_login, PDO::PARAM_STR); 
        $req->execute();

        // update login in the comment table
        $this->_query = 'UPDATE `comment` SET `author` = :new_login WHERE `author` = :old_login';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':new_login', $new_login, PDO::PARAM_STR); 
        $req->bindParam(':old_login', $old_login, PDO::PARAM_STR); 
        $req->execute();
        $req->closeCursor();
        $_SESSION['user'] = $new_login;
        echo 'Login updated';
    }
    
    // update user's password

    public function updatePassword()
    {
        session_start();
        $user_logged = $_SESSION['user'];
        $pwd_strength = preg_match_all('/^.*(?=.{8,})((?=.*[\W]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/', htmlentities($_POST['new_password']));
        $old_password = hash('whirlpool', htmlentities($_POST['old_password']));
        $new_password = hash('whirlpool', htmlentities($_POST['new_password']));
        $this->_query = 'SELECT `password` FROM `user` WHERE `login` = :user_logged';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR);
        $req->execute();
        $db_passwd = $req->fetchColumn();
        $req->closeCursor();
        if ($pwd_strength && $db_passwd === $old_password)
        {        
            $this->_query = 'UPDATE `user` SET `password` = :new_passwd WHERE `login` = :user_logged';
            $req = $this->getDb()->prepare($this->_query);
            $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
            $req->bindParam(':new_passwd', $new_password, PDO::PARAM_STR); 
            $req->execute();
            $req->closeCursor();
            echo 'Password updated';
            return true;
        }
        else
        {
            if ($db_passwd !== $old_password) 
                echo 'Failure, old password did not match';
            else  
                echo 'Failure, new password is too weak';
            return false;
        }
    }
    
    // update user's email

    public function checkNewEmail()
    {
        $new_email = htmlentities($_POST['email']);
        $this->_query = 'SELECT * FROM `user` WHERE `email` = :email';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':email', $new_email, PDO::PARAM_STR); 
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();
        if ($res)
        {
            echo 'Email already exists in the database';
            return false;
        }
        else
            return true;
    }

    public function updateEmail()
    {
        session_start();
        $user_logged =  $_SESSION['user'];
        $new_email = htmlentities($_POST['email']);
        $this->_query = 'UPDATE `user` SET `email` = :new_email WHERE `login` = :user_logged';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':new_email', $new_email, PDO::PARAM_STR); 
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
        $req->execute();
        $req->closeCursor();
        echo 'Email updated';
    }

    // update user's notification settings
    
    public function updateNotification()
    {
        session_start();
        $user_logged =  $_SESSION['user'];
        $this->_query = 'UPDATE `user` SET `notification` = NOT `notification` WHERE `login` = :user_logged';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
        $req->execute();
        $req->closeCursor();
    }

    // retrieve notification settings of the user logged

    public function getNotifStatus()
    {
        session_start();
        $user_logged =  $_SESSION['user'];
        $this->_query = 'SELECT `notification` FROM `user` WHERE `login` = :user_logged';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();
        if ($res)
            return true;
        else
            return false;
    }

    // delete methods

    // delete the account of an user

    public function delAccount() 
    {
        session_start();
        $user_logged = $_SESSION['user'];
        $id = $this->getUserId($user_logged);
        $this->_query = 'SELECT `source` FROM `photo` WHERE `user_id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->execute();
        $pictures = $req->fetchAll(PDO::FETCH_COLUMN);
        foreach($pictures as $pic)
            unlink($pic);
        $this->_query = 'DELETE FROM `like` WHERE `login` = :user_logged';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
        $req->execute();
        $this->_query = 'DELETE FROM `comment` WHERE `author` = ":user_logged';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR); 
        $req->execute();
        $this->_query = 'DELETE FROM `photo` WHERE `user_id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->execute();
        $this->_query = 'DELETE FROM `user` WHERE `id` = :id';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':id', $id, PDO::PARAM_INT); 
        $req->execute();
        $req->closeCursor();
    }

    // reset methods
    
    // reset password of an user
    
    public function resetPassword($email)
    {
        $this->_query = 'SELECT `login` FROM `user` WHERE `email` = :email';
        $req = $this->getDb()->prepare($this->_query);
        $req->bindParam(':email', $email, PDO::PARAM_STR);
        $req->execute();
        $res = $req->fetchColumn();
        $req->closeCursor();
        if ($res)
        {
            $subject = 'Reset your password';
            $headers = 'From: camagru' . "\r\n";
            $headers .= 'Reply-To: camagru' . "\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            $message = 'Reset your password ! <a href="' . URL .'?url=reset&login=' . $res . '">Click here</a>';
            mail($email, $subject, $message, $headers);      
            return true;
        }
        else
            return false;
    }

    // check new password and update it if secured enough
    
    public function setNewPassword()
    {
        session_start();
        $user_logged = $_SESSION['resetUser'];
        $pwd_strength = preg_match_all('/^.*(?=.{8,})((?=.*[\W]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/', htmlentities($_POST['new_password']));   
        if ($pwd_strength)
        {
            $new_password = hash('whirlpool', htmlentities($_POST['new_password']));
            $this->_query = 'UPDATE `user` SET `password` = :new_password WHERE `login` = :user_logged';
            $req = $this->getDb()->prepare($this->_query);
            $req->bindParam(':new_password', $new_password, PDO::PARAM_STR);
            $req->bindParam(':user_logged', $user_logged, PDO::PARAM_STR);
            $req->execute();
            $req->closeCursor();
        }
    }
}   