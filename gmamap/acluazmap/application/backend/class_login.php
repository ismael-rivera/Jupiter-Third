<?php

/**
 * Project:     Login PHP Class
 * File:        class_login.php
 * Purpose:     Authenticates users by checking their data in a mysql database
 *
 * For questions, help, comments, discussion, etc, please send
 * e-mail to antonio.desire@gmail.com
 *
 * @link http://antoniociccia.netsons.org
 * @author Antonio Ciccia <antonio.desire@gmail.com>
 * @package Login PHP Class
 * @version 1.0
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/
 */
 
class Login
{
    private $databaseUsersTable;
    private $showMessage;
    private $cryptMethod;
    
    function Login()
    {
        if(!isset($_SESSION)) session_start();
        $_SESSION['user_login_session']=false;
    }

    /**
     * Sets the database users table
     *
     * @param string $database_user_table
     */
    public function setDatabaseUsersTable($database_user_table)
    {
        $this->databaseUsersTable=$database_user_table;
    }
    
    
    /**
     * Sets the crypting method
     *
     * @param string $crypt_method - You can set it as 'md5' or 'sha1' to choose the crypting method for the user password.
     */
    public function setCryptMethod($crypt_method)
    {
        $this->cryptMethod=$crypt_method;
    }

    /**
     * Crypts a string
     *
     * @param string $text_to_crypt -  crypt a string if $this->cryptMethod was defined.
     * If not, the string will be returned uncrypted.
     */
    public function setCrypt($text_to_crypt)
    {
        switch($this->cryptMethod)
        {
            case 'md5': $text_to_crypt=trim(md5($text_to_crypt)); break;
            case 'sha1': $text_to_crypt=trim(sha1($text_to_crypt)); break;
        }
       return $text_to_crypt;
    }

    /**
     * Anti-Mysql-Injection method, escapes a string.
     *
     * @param string $text_to_escape
     */
    static public function setEscape($text_to_escape)
    {
        if(!get_magic_quotes_gpc()) $text_to_escape=mysql_real_escape_string($text_to_escape);
        return $text_to_escape;
    }

    /**
     * If on true, displays class messages.
     *
     * @param boolean $database_user_table
     */
    public function setShowMessage($login_show_message)
    {
        if(is_bool($login_show_message)) $this->showMessage=$login_show_message;
    }
    
    /**
     * Prints the class messages with a customized style if html tags are defined.
     *
     * @param string $message_text - the message text
     * @param string $message_html_tag_open - the html tag placed before the text
     * @param string $message_html_tag_close - the html tag placed after the text
     * @param boolean $message_die - if on true die($message_text);
     */ 
    public function getMessage($message_text, $message_html_tag_open=null, $message_html_tag_close=null, $message_die=false)
    {
        if($this->showMessage)
        {
            if(!$message_die) die($message_text);
            else echo $message_html_tag_open . $message_text . $message_html_tag_close;
        }
    }

    /**
     * If the user name and password sent via $ _POST method, are in the database, sets login sessions. Else, displays an error message.
     *
	 *Error 9035-2-80: Users table in the database is not specified. Please specify it before any other operation using the method setDatabaseUsersTable();
	 *
     * The user form data needed is: user_name and user_pass
     */
    public function setLoginSession()
    {
        if(!$this->databaseUsersTable) $this->getMessage('<h3>Error 9035-2-80:</h3> <p>Please check with your website administrator.</p>', '', '', 'true');
        if(!$this->getLoginSession())
        {
            $user_name=$this->setEscape($_POST['user_name']);
            $user_pass=$this->setCrypt($_POST['user_pass']);
            $result=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_name='$user_name' AND user_pass='$user_pass'");
            if($fetch=mysql_fetch_assoc($result))
            {
                $_SESSION['user_id']=$fetch['user_id'];
                $_SESSION['user_name']=$fetch['user_name'];
                $_SESSION['user_active']=$fetch['user_active'];
                $_SESSION['user_login_session']=true;
            }
            else
            {
                $this->getMessage('Access data entered is incorrect.');
            }
        }
    }
    
    /**
     * Gets login session, true or false.
     */
    public function getLoginSession()
    {
        if($_SESSION['user_login_session']) return true;
        else return false;
    }

    /**
     * When called, it destroys login session.
     * 
     * Logout method
     */
    public function unsetLoginSession()
    {
        if($this->getLoginSession())
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_active']);
            unset($_SESSION['user_login_session']);
        }
    }


    /**
     * Gets logged user id if login session is true.
     */
    public function getUserId()
    {
        if($_SESSION['user_login_session']) return $_SESSION['user_id'];      
    }

    /**
     * Gets logged user name if login session is true.
     */
    public function getUserName()
    {
        if($_SESSION['user_login_session']) return $_SESSION['user_name'];      
    }

    /**
     * Sets logged user active if login session is true.
     */
    public function setUserActive()
    {
        if($this->getLoginSession())
        {
            if(!$_SESSION['user_active'])
            mysql_query("UPDATE"." ".$this->databaseUsersTable." "."SET user_active='true' WHERE user_id='".$_SESSION['user_id']."'");
            else $this->getMessage('The entered username is already active in our database.');
        }
    }
    
    /**
     * Gets the user activation status if login session is true.
     */
    public function getUserActive()
    {
        if($this->getLoginSession())
        {
            if($_SESSION['user_active']) return true;
            else return false;
        }
    }
    
}

?>