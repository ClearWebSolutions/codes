codes
=====

codes is a rails for PHP/MySQL with visual UI for developer and nicely styled dynamic CMS for end user.
It provide a UI way to create classes, databases and content management of them via a simple design-independent framework.

Installation
---
You need a LAMP environment on your machine already set. Apache server, MySQL database and PHP. There are plenty of ready-made solutions that would install all this for you in a matter of minutes.
Once you are done and you can run local scripts via http://localhost you are good to start with installation of codes.

1. assuming you are running your local server in ~/http/ clone this repository into ~/http/codes/
2. open your MySQL client, create a database named 'codes' and import ~/http/codes/codes.sql, this would create the local database required to store all the data used by codes system
3. goto ~/http/codes/class/includes/ and open the includes.php in your editor

    change the absolute BASEPATH to where your codes repository is located
    ```
    //absolute path on server no ending slash!
    define('BASEPATH','/Applications/MAMP/htdocs/codes');
    ```
    change the URL don’t forget to include the port if you are not running on 8080
    ```
    //site URL no ending slash!
    define('URL','http://localhost/codes');
    ```
    and finally update the database access details
    ```
    //database config
    $db_setup['hostname'] = 'localhost';
    $db_setup['username'] = 'root';
    $db_setup['password'] = 'root';
    $db_setup['database'] = 'codes';
    $db_setup['dbdriver'] = 'mysql';
    ```

If you did 1,2,3 you should be able to see the screen below and start developing websites with the speed of light.
![alt tag](http://codescms.com/imgs/1.png)

A full tutorial on how to create a website and manage it’s data could be found [here](https://github.com/ClearWebSolutions/codes/wiki).
