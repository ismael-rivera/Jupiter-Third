<?php  

/*
* INITIALIZER:
*----------------------------
*
* This page here, acts as a hub and main controller to all other pages 
* and is to be included in every header.
* Use this file to initialize the rest of the headers, includes, sessions, tokens
* and manage connections to database. 
*
* Automatically launch directory class for all pages, regardless.
* Anything that is to be included on every page without exception can be 
* initialized like so.
*/
// start output buffering at the top of our script with this simple command
require_once("application/base/main_config.php");
require_once("application/base/class.dir.php");
require_once("application/backend/session.php");

/*
*
*/

/*
* HEADER MANAGER:
*----------------------------
* There are different headers (2 at the moment of writing this).  
* Anything else which needs to be included should be managed as not all classes are needed for every header
*
*/
require_once("application/base/class.bootloader.php");







