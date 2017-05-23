# PHP PDO Database Wrapper
An object-oriented way to interact with MySQL databases using PDO in PHP

## Getting Started
Simply include `DB.php` to your project. Then instantiate the class like this:

Create an array containing below keys for MySQL database connection
```php
$conn_data = array(
	'dbname' => 'database_name_here',
	'dbuser' => 'database_username_here',
	'dbpass' => 'database_password_here',
	'dbhost' => 'database_hostname_here' // Mostly localhost
);
```
And then instantiate the class:
```php
$db = new DB($conn_data);
```
Execute a standard `SELECT` query:
```php
$query = $db->query('SELECT * FROM `table_name`');
```
Check if rows returned:
```php
if($query->row_count() > 0) {
	echo 'Results found';
}
```
Get results array:
```php
$results = $query->results();
```
A parameterized query:
```php
$db->query('SELECT * FROM `table_name` WHERE `column_name` = ?', array('column_name_value'));
```
A shorcut method to get data from the database (with chaining):
```php
$results = $db->get('table_name', array('column_name' => 'column_value'))->results();
```
Get results without any conditions:
```php
$results = $db->get('table_name')->results();
```
