# PHP PDO Database Wrapper
An object-oriented way to interact with MySQL databases using PDO in PHP

## Getting Started
Simply clone `DB.php` to your project and include it there. Then instantiate the class like this:

Create an array containing below keys for MySQL database connection
```php
$conn_data = array(
	'dbname' => 'userdb',
	'dbuser' => 'rehmat',
	'dbpass' => 'aneejaikNib1',
	'dbhost' => 'localhost'
);
```
And then instantiate the class:
```php
$db = new DB($conn_data);
```

