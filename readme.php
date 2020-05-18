<?php
/**
 * file name readme.php
 * contain all the information about this project
 * use PHPMailer library for send mail
 * use libphonenumber library to valid phone number
 * use composer to install libphonenumber library
 * use CheckPassword class from (PHP Solutions, Dynamic Web Design Made Easy 3rd (2014) by David Powers)
 * use Upload class from (PHP Solutions, Dynamic Web Design Made Easy 3rd (2014) by David Powers)
 * I am not professional so
 * sorry for bad documentation and coning
 */

/** install */
/**
 * build the database and table
 *
 * first of all build a database and import the .sql file to create require tables
 * update the database name in admin/includes/connection.php
 * also update the username and password (default username is "root", password is "" (blank))
 * there are two type of user, one for admin and one for visitor
 * now paste the project folder in the require directory
 * go to the site_url/install.php in the browser
 * fill the input field to create a admin user
 * after create the admin user login in site_url/admin and delete the install.php and CheckPassword.php file
 * insert some menu, product, and news and visit site_url in the browser
 */

/** user area */
/**
 * no need to login for a user
 * user can bye product, post feedbacks about service and post review about product
 * must need admin approval to display feedback and review
 * one phone number for one user or one email account
 * send a mail to the user of each purchase
 */

/** admin area */
/**
 * need to login first
 * create or update product
 * create, delete or update menu
 * create, delete or update news
 * check the of transactions
 * check the product review, update visibility and delete a review
 * check the feedback, update visibility and delete a feedback
 * product can not be delete for transaction history
 * product can be hidden from user to update visibility
 */