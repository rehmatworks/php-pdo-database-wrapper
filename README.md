# PHP PDO Database Wrapper
An object-oriented way to interact with MySQL databases using PDO in PHP

## Getting Started
Simply include `DB.php` to your project. Then instantiate the class like this:

### Connecting to the Database
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
### Some common examples
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
Get all results from a table without any conditions:
```php
$results = $db->get('table_name')->results();
```
## Insert Data
Let's insert some user data to a hypothetical table `users`
```php
$data = array(
	'username' => 'john',
	'email' => 'email@example.com',
	'first_name' => 'John',
	'last_name' => 'Doe'
);

$db->insert('users', $data);
```
Get the ID of the last inserted record (assuming that you have an auto-increment primary field). Do this soon after insert:
```php
$lastID = $db->last_id();
```
## Update Data
Let's update the above hypothetical row
```php
$new_data = array(
	'username' => 'newjohn',
	'email' => 'newemail@example.com',
	'first_name' => 'NewJohn',
	'last_name' => 'NewDoe'
);
// Set conditions to update a specific row
// Let's use the ID we obtained after insert above
$conditions = array(
	'id' => $lastID
);
$db->update('users', $new_data, $conditions);
```
## Get Data
Get a specific row
```php
$conditions = array(
	'username' => 'newjohn'
);
$query = $db->get('users', $conditions);
if($query->row_count() > 0) {
	echo 'Username: ' . $query->row('username') . '<br>';
} else {
	echo 'No records found';
}
```
Or get all rows from a table
```php
$query = $db->get('users');
if($query->row_count() > 0) {
	$users = $query->results();
	foreach($users as $user) {
		echo $user->username; // Will print username
		echo $user->email; // Will print email
	} 
}
```
## Delete Data
```php
$conditions = array(
	'username' => 'newjohn'
);
$db->del('users', $conditions);
```

I hope this is simple enough! You can contribute to the code if you can make it better.
