Drop Database if exists work;
CREATE DATABASE if not exists work Default charset=utf8;
use work;
Create Table Person(
	id int primary key auto_increment,
	first_name varchar(50) not null,
	second_name varchar(50),
	family varchar(50),
	email varchar(100),
	address varchar(100),
	phone varchar(15),
	money_per_hour float
) ENGINE InnoDB Default charset=utf8;
use work;

Create Table Work(
	id int primary key auto_increment,
	person_id int not null,
	start_time time,
	end_time time,
	free_time time,
	money_per_hour float,
	work_date date,
	foreign key Work(person_id) references Person(id)
) ENGINE InnoDB Default charset=utf8;
