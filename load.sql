
LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/parents.csv'
INTO TABLE PARENT
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/children.csv'
INTO TABLE CHILD
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/user.csv'
INTO TABLE USER_ACCOUNT
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/chores.csv'
INTO TABLE CHORE
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE '/Users/gayathriutla/Desktop/Projects/databases_class_project/parents.csv'
INTO TABLE PAYMENT
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
