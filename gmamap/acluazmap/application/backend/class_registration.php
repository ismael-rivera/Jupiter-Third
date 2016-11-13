<?php

/**
 * Project:     Registration PHP Class
 * File:        class_registration.php
 * Purpose:     Register users in a mysql database
 *
 * For questions, help, comments, discussion, etc, please send
 * e-mail to antonio.desire@gmail.com
 *
 * @link http://antoniociccia.netsons.org
 * @author Antonio Ciccia <antonio.desire@gmail.com>
 * @package Registration PHP Class
 * @version 1.1
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

class Registration
{
    private $databaseUsersTable;
    private $cryptMethod;
    private $showMessage;

    /**
     * Sets the database users table
     *
     * @param string $database_user_table
     */
    public function setDatabaseUserTable($database_user_table)
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
     * If on true, displays class messages
     *
     * @param boolean $database_user_table
     */
    public function setShowMessage($registration_show_message)
    {
        if(is_bool($registration_show_message)) $this->showMessage=$registration_show_message;
    }
    
    /**
     * Prints the class messages with a customized style if html tags are defined
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
            if($message_die) die($message_text);
            else echo $message_html_tag_open . $message_text . $message_html_tag_close;
        }
    }
    
    /**
     * Register user in the database
     *
     * The user form data needed is: user_name, user_pass, user_confirm_pass, user_mail, user_confirm_mail
     */
    public function setUserRegistration()
    {
        if(!$this->databaseUsersTable) $this->getMessage('Users table in the database is not specified. Please specify it before any other operation using the method setDatabaseUserTable();','','','true');
        $user_name=$this->setEscape($_POST['user_name']);
        $user_pass=$_POST['user_pass'];
        $user_confirm_pass=$_POST['user_confirm_pass'];
        $user_mail=$_POST['user_mail'];
        $user_confirm_mail=$_POST['user_confirm_mail'];
        $user_crypted_pass=$this->setCrypt($user_pass);
        $result_user_name=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_name='$user_name'");
        $result_user_mail=mysql_query("SELECT * FROM"." ".$this->databaseUsersTable." "."WHERE user_mail='$user_mail'");
        if((strlen($user_name)<6) or (strlen($user_name)>16)) $this->getMessage('Entered username length must be of 6 to 16 characters.');
        elseif(mysql_num_rows($result_user_name)) $this->getMessage('Entered username already exists in the database.');
        elseif((strlen($user_pass)<8) or (strlen($user_pass)>16)) $this->getMessage('Entered password length must be of 8 to 16 characters.');
        elseif($user_pass!=$user_confirm_pass) $this->getMessage('Passwords entered do not match.');
        elseif(mysql_num_rows($result_user_mail)) $this->getMessage('Entered email already exists in the database.');
        elseif($user_mail!=$user_confirm_mail) $this->getMessage('Email addresses entered do not match.');
        elseif(!preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-]{4,})+\.)+([a-zA-Z0-9]{2,})+$/", $user_mail)) $this->getMessage('Email address entered is not valid.');
        else
        {
            if(mysql_query("INSERT INTO"." ".$this->databaseUsersTable." "."(user_name, user_pass, user_mail) VALUES ('$user_name', '$user_crypted_pass', '$user_mail')")) $this->getMessage('Registration was successful.');
        }
    }
}

?>