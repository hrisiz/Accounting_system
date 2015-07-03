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
Insert Into Bonus_type(name) Values('Аванс'),('Заем');

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
Alter Table Bonus Add Column use_now smallint not null default 0

use work;
Create Table if not exists Account(
	id int primary key auto_increment,
	user_name varchar(50) not null,
	password binary(128) not null	
);
Insert Into Account(user_name,password) Values('geri_1966',SHA2('PowerPass-zima123', 512));

Alter Table Person Add column balance float not null default 0;


use work;
CREATE TRIGGER `BonusUse` BEFORE INSERT ON `bonus`
 FOR EACH ROW BEGIN
    IF (CAST(NEW.start_date as date) <= CAST(CURDATE() as date)) THEN
        SET NEW.use_now = 1;
    ELSE
        SET NEW.use_now = 0;
    END IF;
END


CREATE DEFINER=`root`@`localhost` EVENT `BonusesDate` ON SCHEDULE 
EVERY 1 DAY STARTS '2015-07-03 21:57:27' 
ON COMPLETION NOT PRESERVE ENABLE DO 
Update Bonus Set use_now = 1 Where start_date <= CAST(CURDATE() as date)

use work;
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