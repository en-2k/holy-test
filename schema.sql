DROP TABLE IF EXISTS Vacations CASCADE;
DROP TABLE IF EXISTS Employee CASCADE;
DROP TABLE IF EXISTS Department CASCADE;

/*Підрозділи*/
CREATE TABLE Department(
 id SERIAL PRIMARY KEY, /* Перв.ключ*/
 name VARCHAR(100)      /* Назва підрозділу */
);

/*Співробітники*/
CREATE TABLE Employee(
 id SERIAL PRIMARY KEY ,                      /* Перв.ключ */
 department_id INT REFERENCES Department(id), /* Посилання на підрозділ – Department.id */
 name VARCHAR(128),                           /* ПІБ співробітника */
 fired DATE                                   /* Дата звільнення */
);

/*Періоди відпустки співробітників*/
CREATE TABLE Vacations(
 id SERIAL PRIMARY KEY,                   /* Перв.ключ */
 employee_id INT REFERENCES Employee(id), /* Посилання на співробітника – Employee.id */
 d_start DATE,                            /* Дата початку відпустки */
 d_end DATE                               /* Дата кінця відпустки */
);
