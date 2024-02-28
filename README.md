# simple-survey-api
An explanation of:

1. ERD Diagram
2. Database SQL file
3. REST API code
4. Postman Collection. each repository should have a README.md documenting:
5. how to set up and run your application on a local machine and the deployment process.

## Overview

The Simple Survey Client Application is a RESTful web service that allows users to submit survey responses and upload corresponding certificates. This document provides an explanation of the database structure through an ERD diagram, details of the REST API code for handling requests, and instructions for setting up and running the application both locally and in a production environment.

## ERD Diagram

The Entity-Relationship Diagram (ERD) represents the database schema used by the Simple Survey Client Application. It includes three main entities:

- **User**: Stores user details with attributes like `UserID`, `FullName`, `Email`, `Gender`, and `Description`.
- **SurveyResponse**: Records the responses submitted by users, linked to the `User` entity via `UserID`. Attributes include `ResponseID`, `Description`, `ProgrammingStack`, and `Timestamp`.
- **Certificate**: Keeps track of files uploaded by users as part of their survey response, associated with `SurveyResponse` via `ResponseID`. Attributes include `CertificateID`, `FilePath`, and `UploadDate`.

![ERD Diagram](path-to-erd-diagram-image)

## Database SQL File

Included in the repository is a SQL file that contains all the necessary statements to create and initialize the database schema for the application. Import this file into your MySQL instance to set up the database.

## REST API Code

The REST API is implemented in PHP and handles two main operations:

- **GET**: Retrieves a paginated list of survey responses.
- **POST**: Allows the submission of survey responses and uploading of certificates.

The code is designed to return JSON responses and handle errors gracefully by providing informative messages and HTTP status codes.

## Postman Collection

A Postman collection is provided to facilitate the testing of API endpoints. Import the collection into Postman to interact with the API easily.

## Local Setup and Running the Application

To set up and run the application locally:

1. Install PHP and MySQL on your machine.
2. Clone the repository to your web server's directory, e.g., `C:/xampp/htdocs/`.
3. Create a MySQL database named `sky_survey_db` and import the provided SQL schema.
4. Modify the database connection settings in `api.php` if needed.
5. Start your web server and access the API via `http://localhost/simple_survey_client/api.php`.

## Deployment Process

For deploying the application to a live server:

1. Upload the application files to your web hosting service.
2. Set up the MySQL database on your server and import the SQL schema.
3. Update the database connection settings in `api.php` to match the live server's database credentials.
4. Test the API endpoints to ensure they're working correctly on the new server.

## Contribution

Please read `CONTRIBUTING.md` for details on our code of conduct, and the process for submitting pull requests to us.

## License

This project is licensed under the MIT License - see the `LICENSE.md` file for details.

