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

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

### Authentication

1. Access the application at http://localhost:34251
2. Call the API to register a new user
    - Method: POST
    - URL: http://localhost:34251/api/users/register
    - Body: 
        ```json
        {
            "username": "test",
            "password": "test"
        }
        ```
    - Response: 
        ```json
        {
            "message": "User signed up successfully"
        }
        ```
3. Call the API to login with the registered user
    - Method: POST
    - URL: http://localhost:34251/api/users/login
    - Body: 
        ```json
        {
            "username": "test",
            "password": "test"
        }
        ```
    - Response: 
        ```json
        {
              "message": "User logged in successfully"
        }
       ```
4. Call the API to logout
    - Method: POST
    - URL: http://localhost:34251/api/users/logout
    - Response: 
        ```json
        {
              "message": "User logged out successfully"
        }
       ```

### Article Management

1. Access the application at http://localhost:34251
2. Call the API to create a new article
    - Method: POST
    - URL: http://localhost:34251/api/articles
    - Body: 
        ```json
        {
            "title": "Test Article",
            "body": "This is a test article"
        }
        ```
    - Response: 
        ```json
        {
            "data": {
                "id": 1,
                "title": "Test Article",
                "body": "This is a test article",
                "user_id": 1,
                "created": "2021-08-15T14:00:00+00:00",
                "modified": "2021-08-15T14:00:00+00:00"
            }
        }
        ```
3. Call the API to get all articles
    - Method: GET
    - URL: http://localhost:34251/api/articles
    - Response: 
        ```json
        {
            "data": [
                {
                    "id": 1,
                    "title": "Test Article",
                    "body": "This is a test article",
                    "user_id": 1,
                    "created": "2021-08-15T14:00:00+00:00",
                    "modified": "2021-08-15T14:00:00+00:00"
                }
            ]
        }
        ```
4. Call the API to get a specific article
    - Method: GET
    - URL: http://localhost:34251/api/articles/1
    - Response: 
        ```json
        {
            "data": {
                "id": 1,
                "title": "Test Article",
                "body": "This is a test article",
                "user_id": 1,
                "created": "2021-08-15T14:00:00+00:00",
                "modified": "2021-08-15T14:00:00+00:00"
            }
        }
        ```
5. Call the API to update an article
    - Method: PUT
    - URL: http://localhost:34251/api/articles/1
    - Body: 
        ```json
        {
            "title": "Updated Test Article",
            "body": "This is an updated test article"
        }
        ```
    - Response: 
        ```json
        {
            "data": {
                "id": 1,
                "title": "Updated Test Article",
                "body": "This is an updated test article",
                "user_id": 1,
                "created": "2021-08-15T14:00:00+00:00",
                "modified": "2021-08-15T14:00:00+00:00"
            }
        }
        ```
6. Call the API to delete an article
    - Method: DELETE
    - URL: http://localhost:34251/api/articles/1
    - Response: 
        ```json
        {
            "message": "Article deleted successfully"
        }
        ```

### Like Feature

1. Access the application at http://localhost:34251
2. Call the API to like an article
    - Method: POST
    - URL: http://localhost:34251/api/articles/1/like
    - Response: 
        ```json
        {
            "message": "Article liked successfully"
        }
        ```

Table UsersArticleLikes will be like this:

users_article_likes
| column | type | description |
| id | int | primary key |
| user_id | int | foreign key to users table |
| article_id | int | foreign key to articles table |
| created_at | datetime | created date |
| updated_at | datetime | updated date |
