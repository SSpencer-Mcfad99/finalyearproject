GRANT ALL ON votesys.* to 'votesys_user'@'localhost' IDENTIFIED BY 'votesys_user_pass';

DROP TABLE IF EXISTS users;

CREATE TABLE users (
	userid INT(11) NOT NULL AUTO_INCREMENT,
	username VARCHAR(25) UNIQUE NOT NULL,
	password VARCHAR(256) NOT NULL,
	role ENUM('0','1') NOT NULL DEFAULT '0',
	email VARCHAR(250) NOT NULL,
	firstname VARCHAR(30) NOT NULL,
	lastname VARCHAR(30) NOT NULL,
	creationtimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (userid)
);

DROP TABLE IF EXISTS loginlogs;

CREATE TABLE loginlogs (
	logid INT(11) NOT NULL AUTO_INCREMENT,
	userid INT(11) NOT NULL,
	loginstatus BOOLEAN,
	logintimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (logid),
	FOREIGN KEY (userid) REFERENCES users(userid)
);

DROP TABLE IF EXISTS sessions;

-- Tables for forum discussion board --
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
 categoryid INT(2) NOT NULL AUTO_INCREMENT,
 categoryname VARCHAR(50) UNIQUE NOT NULL,
 categorydescription VARCHAR(255) NOT NULL,
 PRIMARY KEY (categoryid)
);

DROP TABLE IF EXISTS posts;

CREATE TABLE posts (
  postid INT(8) NOT NULL AUTO_INCREMENT,
  postsubject VARCHAR(255) NOT NULL,
  postdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  postcategory INT(8) NOT NULL,
  postauthor  VARCHAR(25) NOT NULL,
  PRIMARY KEY (postid),
  FOREIGN KEY (postcategory) REFERENCES categories(categoryid) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS replies;

CREATE TABLE replies (
  replyid INT(8) NOT NULL AUTO_INCREMENT,
  replycontent TEXT NOT NULL,
  replydate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  replytopic INT(8) NOT NULL,
  replyauthor VARCHAR(25)  NOT NULL,
  PRIMARY KEY (replyid),
  FOREIGN KEY (replytopic) REFERENCES posts(postid) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tables for quiz section --
DROP TABLE IF EXISTS quizzes;

CREATE TABLE quizzes (
  quizid INT(8) NOT NULL AUTO_INCREMENT,
  quizname VARCHAR(255) NOT NULL,
  quizdescription VARCHAR (255) NOT NULL,
  PRIMARY KEY (quizid)
);

DROP TABLE IF EXISTS questions;

CREATE TABLE questions (
  questionid INT(8) NOT NULL AUTO_INCREMENT,
  quizid INT(8) NOT NULL,
  question VARCHAR(255) NOT NULL,
  choice1 VARCHAR(255) NOT NULL,
  choice2 VARCHAR(255) NOT NULL,
  choice3 VARCHAR(255) NOT NULL,
  choice4 VARCHAR(255) NOT NULL,
  ans VARCHAR(255) NOT NULL,
  PRIMARY KEY (questionid),
  FOREIGN KEY (quizid) REFERENCES quizzes(quizid) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tables for voting system section --

DROP TABLE IF EXISTS system_type;

CREATE TABLE system_type (
  systemtypeid INT(8) NOT NULL AUTO_INCREMENT,
  systemtypename VARCHAR(255) NOT NULL,
  systemtypedesc VARCHAR(255) NOT NULL,
  PRIMARY KEY (systemtypeid)
);


DROP TABLE IF EXISTS voting_system;

CREATE TABLE voting_system(
  systemid INT(8) NOT NULL AUTO_INCREMENT,
  systemname VARCHAR(255) NOT NULL,
  systemtypeid INT(8) NOT NULL,
  systemsummary VARCHAR(255) NOT NULL,
  systeminformation TEXT NOT NULL,
  PRIMARY KEY (systemid),
  FOREIGN KEY (systemtypeid) REFERENCES system_type(systemtypeid) ON DELETE RESTRICT ON UPDATE CASCADE
);

DROP TABLE IF EXISTS glossary;

CREATE TABLE glossary(
  wordid INT(8) NOT NULL AUTO_INCREMENT,
  word VARCHAR(255) NOT NULL,
  worddefinition VARCHAR(255) NOT NULL,
  PRIMARY KEY (wordid)
);