## Get Started

This guide will walk you through the steps needed to get this project up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

- Docker
- Docker Compose

### Building the Docker Environment

Build and start the containers:

```
docker-compose up -d --build
```

### Installing Dependencies

```
docker-compose exec app sh
composer install
```

### Database Setup

Set up the database:

```
bin/cake migrations migrate
```

Note: if you get error permission plz run cmd:

```
chmod +x bin/cake
```

### Run Seed Data
```
bin/cake migrations seed
```

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

### Authentication

pls login with the credentials below:
user: admin@admin.com
password: Admin1@

TODO: pls summarize how to check "Authentication" bahavior

******** Authentication ********
I'm using cakephp Authentication Plugin to implement user login
Link: https://book.cakephp.org/4/en/tutorials-and-examples/cms/authentication.html
the steps are:
1. Install the Plugin
2. Load the Plugin and Configuration
3. User Entity
4. Middleware
5. Authorization and Session Handling

to handle Token-Based Authentication I have combined with library firebase/php-jwt
Link:
    - https://github.com/firebase/php-jwt
    - https://book.cakephp.org/authentication/2/en/authenticators.html#jwt
if you want how I handled it, pls go to src/Aplication.php and Api/UsersController.php for detail

### Article Management

TODO: pls summarize how to check "Article Management" bahavior

1. Database Setup: Define a database table for storing articles.

2. Model: Define associations, validations, and any business logic related to articles.

3. Controller: Create a controller to handle CRUD operations for articles. Implement actions for listing, viewing, adding, editing, and deleting articles.

4. Routing: Define routes in your config/routes.php file to map URLs to controller actions for managing articles.

5. Views: Create views for displaying articles. Design templates for listing articles, viewing individual articles, adding new articles, and editing existing articles.

6. JS: Use ajax call to server to execute CRUD

7. Authentication: Implement authentication to control access to article management features.

### Like Feature

TODO: pls summarize how to check "Like Feature" bahavior


1. Database Schema: Design a database schema to store information about likes.

2. Model: Create a Like model representing the likes table. Define associations between the Like model.

3. Controller Actions: Implement controller actions to handle liking and unliking items.

4. Routing

5. Authentication: Ensure that users are authenticated before they can like or unlike items.