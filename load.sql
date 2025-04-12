
LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/PARENT.csv'
INTO TABLE PARENT
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/CHILD.csv'
INTO TABLE CHILD
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/USER.csv'
INTO TABLE USER_ACCOUNT
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/CHORE.csv'
INTO TABLE CHORE
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/PAYMENT.csv'
INTO TABLE PAYMENT
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
