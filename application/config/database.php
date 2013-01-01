<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'santa12';
$active_record = TRUE;
$db['santa12']['hostname'] = 'localhost';
$db['santa12']['username'] = 'root';
$db['santa12']['password'] = '';
$db['santa12']['database'] = 'santa12';
$db['santa12']['dbdriver'] = 'mysql';
$db['santa12']['dbprefix'] = '';
$db['santa12']['pconnect'] = TRUE;
$db['santa12']['db_debug'] = TRUE;
$db['santa12']['cache_on'] = FALSE;
$db['santa12']['cachedir'] = '';
$db['santa12']['char_set'] = 'utf8';
$db['santa12']['dbcollat'] = 'utf8_general_ci';
$db['santa12']['swap_pre'] = '';
$db['santa12']['autoinit'] = TRUE;
$db['santa12']['stricton'] = TRUE;

$db['ntsp']['hostname'] = '172.28.58.136';
$db['ntsp']['username'] = 'santa';
$db['ntsp']['password'] = 'santa123';
$db['ntsp']['database'] = 'santa12';
$db['ntsp']['dbdriver'] = 'mysql';
$db['ntsp']['dbprefix'] = '';
$db['ntsp']['pconnect'] = TRUE;
$db['ntsp']['db_debug'] = TRUE;
$db['ntsp']['cache_on'] = FALSE;
$db['ntsp']['cachedir'] = '';
$db['ntsp']['char_set'] = 'utf8';
$db['ntsp']['dbcollat'] = 'utf8_general_ci';
$db['ntsp']['swap_pre'] = '';
$db['ntsp']['autoinit'] = TRUE;
$db['ntsp']['stricton'] = FALSE;

$db['julio']['hostname'] = '172.28.61.38';
$db['julio']['username'] = 'root';
$db['julio']['password'] = 'ngsfadmin_10';
$db['julio']['database'] = 'santa12';
$db['julio']['dbdriver'] = 'mysql';
$db['julio']['dbprefix'] = '';
$db['julio']['pconnect'] = TRUE;
$db['julio']['db_debug'] = TRUE;
$db['julio']['cache_on'] = FALSE;
$db['julio']['cachedir'] = '';
$db['julio']['char_set'] = 'utf8';
$db['julio']['dbcollat'] = 'utf8_general_ci';
$db['julio']['swap_pre'] = '';
$db['julio']['autoinit'] = TRUE;
$db['julio']['stricton'] = TRUE;


/* End of file database.php */
/* Location: ./application/config/database.php */