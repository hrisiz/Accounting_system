Drop Database if exists work;
CREATE DATABASE if not exists work Default charset=utf8;

use work;
Create Table if not exists Person(
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
Create Table if not exists Work(
	id int primary key auto_increment,
	person_id int not null,
	start_time time,
	end_time time,
	free_time time,
	money_per_hour float,
	work_date date,
	work_time time,
	work_money float,
	foreign key Work(person_id) references Person(id)
) ENGINE InnoDB Default charset=utf8;

use work;
Create Table if not exists Bonus_type(
	id int primary key auto_increment,
	name varchar(50)
) ENGINE InnoDB Default charset=utf8;
Insert Into Bonus_type(name) Values('Аванс'),('Бонус'),('Заем');

use work;
Create Table if not exists Bonus(
	id int primary key auto_increment,
	person_id int not null,
	type int,
	current_money float,
	money float,
	money_per_week float,
	start_date date,
	Constraint fk1 foreign key Bonus(person_id) references Person(id),
	Constraint fk2 foreign key Bonus(type) references Bonus_type(id)
) ENGINE InnoDB Default charset=utf8;


use work
CREATE TRIGGER `EditTimeAndMoney` BEFORE UPDATE ON `work`
 FOR EACH ROW BEGIN
Set NEW.work_time = SUBTIME(SUBTIME(NEW.end_time, NEW.start_time), NEW.free_time);
Set NEW.work_money = FORMAT(((HOUR(NEW.work_time)*60) + MINUTE(NEW.work_time)) * (NEW.money_per_hour/60),2);
END

CREATE TRIGGER `AddTimeAndMoney` BEFORE INSERT ON `work`
 FOR EACH ROW BEGIN
Set NEW.work_time = SUBTIME(SUBTIME(NEW.end_time, NEW.start_time), NEW.free_time);
Set NEW.work_money = FORMAT(((HOUR(NEW.work_time)*60) + MINUTE(NEW.work_time)) * (NEW.money_per_hour/60),2);
END