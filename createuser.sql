CREATE DATABASE todo2025;
CREATE USER 'todo'@'localhost' IDENTIFIED BY 'motdepassefort';
ALTER USER 'todo'@'localhost' IDENTIFIED BY 'todo';
GRANT ALL PRIVILEGES ON todo2025.* TO 'todo'@'localhost';
FLUSH PRIVILEGES;