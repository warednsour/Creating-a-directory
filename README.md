# Creating-a-directory

creating DataBase tables

database name : directory

tables :

journals

CREATE TABLE journals (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  image VARCHAR(100) NOT NULL,
  release_date DATE
)


authors
CREATE TABLE authros (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(255) NOT NULL,
  middle_name VARCHAR(255),
  last_name VARCHAR(255) NOT NULL
)
