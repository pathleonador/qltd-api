# Q Ltd Exam (Backend)

## About The Project

This is a simple CRUD exmple for a news article, where in user can upvote or downvote articles.

## Built With

- PHP 7.4
- PostgreSQL 12
- NGINX

## Getting Started

Follow these steps to get a local copy up and running.

### Prerequisites

- Docker
- PHP 7.4
- PostgreSQL 12
- NGINX

### Installation

Follow these steps to run the application.

1. In your terminal, clone the repo to your prefered location.

```
git clone https://github.com/pathleonador/qltd-api.git backend
```

2. Go to the project folder

```
cd backend
```

3. Run the application by running this command. Once successfuly created, you can now test the application. See **Usage** section.

```
docker-compose up --remove-orphans -d
```

4. To terminate the application, issue the folowing command

```
docker-compose down -v
```

5. Remove the liked database folders using the following command.

```
sudo rm -rf database/local
```

### Usage

Using your Postman, import the api endpoints named **Articles.postman_collection.json**.

1. Once you import the API collection, you can now create article using the **Create Article** endpoint.
2. Cast vote to a specific article by using the endpoint **Cast Vote to an Article**
3. Fetch data using the endpoints with a **Get** method.

### Contact

Patrocinio Leonador - pleonador@gmail.com  
Project Link: [https://github.com/pathleonador/qltd-api](https://github.com/pathleonador/qltd-api)

### ToDo

There's still need to be done.

1. Standardize response data structure to all the endpoints.
2. Add simple security or token validation.
3. Code clean up, remove die commands and replace with proper return of data.
