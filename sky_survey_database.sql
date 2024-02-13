-- Create the database
CREATE DATABASE sky_survey_db;
USE sky_survey_db;

-- Create the Survey table
CREATE TABLE Survey (
    SurveyID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    StartDate DATE,
    EndDate DATE
);

-- Create the Question table
CREATE TABLE Question (
    QuestionID INT AUTO_INCREMENT PRIMARY KEY,
    SurveyID INT,
    Text TEXT NOT NULL,
    Type VARCHAR(50),
    FOREIGN KEY (SurveyID) REFERENCES Survey(SurveyID) ON DELETE CASCADE
);

-- Create the Respondent table
CREATE TABLE Respondent (
    RespondentID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    Email VARCHAR(255) UNIQUE
);

-- Create the Response table
CREATE TABLE Response (
    ResponseID INT AUTO_INCREMENT PRIMARY KEY,
    QuestionID INT,
    RespondentID INT,
    Answer TEXT,
    FOREIGN KEY (QuestionID) REFERENCES Question(QuestionID) ON DELETE CASCADE,
    FOREIGN KEY (RespondentID) REFERENCES Respondent(RespondentID) ON DELETE CASCADE
);

-- Junction table for Many-to-Many relationship between Survey and Respondent
CREATE TABLE SurveyRespondent (
    SurveyID INT,
    RespondentID INT,
    PRIMARY KEY (SurveyID, RespondentID),
    FOREIGN KEY (SurveyID) REFERENCES Survey(SurveyID) ON DELETE CASCADE,
    FOREIGN KEY (RespondentID) REFERENCES Respondent(RespondentID) ON DELETE CASCADE
);