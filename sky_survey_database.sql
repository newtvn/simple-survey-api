CREATE DATABASE IF NOT EXISTS sky_survey_db;
USE sky_survey_db;

CREATE TABLE IF NOT EXISTS survey_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email_address VARCHAR(255) NOT NULL,
    description TEXT,
    gender ENUM('MALE', 'FEMALE', 'OTHER') NOT NULL,
    programming_stack TEXT,
    certificates TEXT,
    date_responded DATETIME NOT NULL
);
