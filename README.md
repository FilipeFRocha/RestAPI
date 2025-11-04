# PHP Rest API Usage Guide
This document provides practical examples of how to use the Rest API project to build and execute SQL queries.

## Example Queries
Below are practical examples of how to create SQL Queries using the QueryBuilder.
All of these need to be placed inside the `ApiController.php` file.

All queries begin by selecting a table using:
`$queryBuilder->table('TableName');`

### SELECT
```php
$results = $queryBuilder
	->table('Users')
	->select(['id', 'username', 'email'])
	->get();
```

#### Explanation

`select()` defines which columns to retrieve.  
You can pass `['*']` to select all columns.

**Generated SQL**
```sqL
SELECT id, username, email FROM Users
```
  
### WHERE

#### Example 1 — Simple condition
 ```php
$results = $queryBuilder
	->table('Users')
	->select(['id', 'username'])
	->where('status', '=', 'active')
	->get();
```

**Generated SQL**
```sql
SELECT id, username FROM Users WHERE status = active
```
  
#### Example 2 — Multiple conditions
```php
$results = $queryBuilder
	->table('Products')
	->select(['id', 'name', 'price'])
	->where('price', '>', 50)
	->where('category', '=', 'Electronics')
	->get();
```

**Generated SQL**
```sql
SELECT id, name, price FROM Products WHERE price > 50 AND category = 'Eletronics'
```
  
#### Example 3 — IN operator
```php
$results = $queryBuilder
	->table('Orders')
	->select(['order_id', 'status'])
	->where('status', 'IN', ['Pending', 'Shipped', 'Delivered'])
	->get();
```
  
**Generated SQL**
```sql
SELECT order_id, status FROM Orders WHERE status IN ('Pending', 'Shipped', 'Delivered')
```
  
### OR WHERE
```php
$results = $queryBuilder
	->table('Users')
	->select(['id', 'username'])
	->where('role', '=', 'admin')
	->orWhere('role', '=', 'moderator')
	->get();
```
  
**Generated SQL**
```sql
SELECT id, username FROM Users WHERE role = 'admin' OR role = 'moderator'
```
  
### JOIN & LEFT JOIN

#### INNER JOIN
```php
$results = $queryBuilder
	->table('Orders')
	->select(['Orders.id', 'Customers.name AS Customer'])
	->join('Customers', 'Orders.customer_id', '=', 'Customers.id')
	->get();
```

**Generated SQL**
```sql
SELECT Orders.id, Customers.name AS Customer FROM Orders JOIN Customers ON Orders.customer_id = Customers.id
```

#### LEFT JOIN
```php
$results = $queryBuilder
	->table('Customers')
	->select(['Customers.name', 'Orders.id AS OrderID'])
	->leftJoin('Orders', 'Customers.id', '=', 'Orders.customer_id')
	->get();
```

**Generated SQL**
```sql
SELECT Customers.name, Orders.id AS OrderID FROM Customers LEFT JOIN Orders ON Customers.id = Orders.customer_id
```

### LIMIT
```php
$results = $queryBuilder
	->table('Orders')
	->select(['order_id', 'status'])
	->limit(5)
	->get();
```

**Generated SQL**
```sql
SELECT order_id, status FROM Orders LIMIT 5
```
  
### ORDER BY

#### Example 1 — Single column

```php
$results = $queryBuilder
	->table('Products')
	->select(['name', 'price'])
	->order('price', 'DESC')
	->get();
```

**Generated SQL**
```sql
SELECT name, price FROM Products ORDER BY price DESC
```

#### Example 2 — Multiple order columns
```php
$results = $queryBuilder
	->table('Users')
	->select(['id', 'username'])
	->order('last_login', 'DESC')
	->order('username', 'ASC')
	->get();
```

**Generated SQL**
```sql
SELECT id, username FROM Users ORDER BY last_login DESC, username ASC
```
  
### GROUP BY
```php
$results = $queryBuilder
	->table('Orders')
	->select(['customer_id', 'COUNT(*) AS total_orders'])
	->groupBy('customer_id')
	->get();
```
  
**Generated SQL**
```sql
SELECT customer_id, COUNT(*) AS total_orders FROM Orders GROUP BY customer_id
```
  
### INSERT
```php
$success = $queryBuilder
	->table('Users')
	->insert([
	'username' => 'joaosilva',
	'email' => 'joao@example.com',
	'status' => 'active'
	]);
```

Returns true if the insert was successful.

**Generated SQL**
```sql
INSERT INTO Users (username, email, status) VALUES (johnDoe, john@example.com, active)
```

You can retrieve the last inserted ID with:

```php
$lastId = $queryBuilder->getLastInsertId();
```
  
### UPDATE
```php
$success = $queryBuilder
	->table('Users')
	->update(['status' => 'inactive'])
	->where('id', '=', 3)
	->execute();
```
  
**Generated SQL**
```sql
UPDATE Users SET status = inactive WHERE id = 3
```
  
### DELETE
```php
$success = $queryBuilder
	->table('Users')
	->delete()
	->where('id', '=', 10)
	->execute();
```
  
**Generated SQL**
```sql
DELETE FROM Users WHERE id = 10
```
  
### Get Server Timestamp
```php
$timestamp = $queryBuilder->getServerTimeStamp();

echo "Current server time: $timestamp";
```
  
### Raw SQL (for advanced use)
```php
$queryBuilder->raw("UPDATE Users SET status = 'active' WHERE id = 1");
```
  
Use raw() only for specific custom queries when the builder doesn’t support a needed feature.

## Example Endpoint Definitions
Below are practical examples of how to define API routes using the router.  
Each route connects an HTTP method and URL path to a controller function.

### GET — Retrieve All Records
`$router->add('GET', '/users', fn() => $controller->getUsers());`

**Description:**  
Retrieves all users from the database.

**Example Request:**
`GET /users`

### GET (with Parameters) — Retrieve Specific Record
`$router->add('GET', '/userById/$id', fn($id) => $controller->getUserByID($id));`

**Description:**  
Retrieves a user by their unique ID.

**Example Request:**
`GET /userById/5`

### POST — Insert New Record
`$router->add('POST', '/insertUser', fn() => $controller->insertUser());`

**Description:**  
Creates a new user in the database.

**Example Request:**
`POST /insertUser`

**Example Body (JSON):**
```json
{   
	"username": "JohnDoe",
	"email": "john@example.com",
	"status": "active" 
}
```
### PUT — Update Existing Record
`$router->add('PUT', '/updateUser', fn() => $controller->updateUser());`

**Description:**  
Updates user information in the database.

**Example Request:**
`PUT /updateUser`

**Example Body (JSON):**
```json
{   
	"id": 5,   
	"email": "JohnDoeUpdate@example.com",   
	"status": "inactive"
}
```

### DELETE — Remove Record
`$router->add('DELETE', '/deleteUserByID/$id', fn($id) => $controller->deleteUserByID($id));`

**Description:**  
Deletes a user by their ID.

**Example Request:**
`DELETE /deleteUserByID/5`
