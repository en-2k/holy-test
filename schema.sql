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

DROP TABLE IF EXISTS protocol;
CREATE TABLE protocol(
    id SERIAL PRIMARY KEY,
    stamp             TIMESTAMP,
    act               VARCHAR(16),
    table_name        VARCHAR(32),
    table_id          INT,
    descr             TEXT
);

CREATE OR REPLACE FUNCTION to_protocol() RETURNS TRIGGER AS $protoc$
DECLARE t_descr text;
    BEGIN
        IF (TG_OP = 'DELETE') THEN
            INSERT INTO protocol(stamp,act,table_name,table_id,descr) SELECT 
              now(),
              'DELETE',
              TG_TABLE_NAME::varchar(32),
              OLD.id,
              '';
            RETURN OLD;
        ELSIF (TG_OP = 'UPDATE') THEN
            t_descr = '';
            BEGIN
                IF OLD.name <> NEW.name THEN
                t_descr = t_descr || 'name ' || E'\n';
                t_descr = t_descr || ' old: ' || OLD.name::text ||  E'\n';
                t_descr = t_descr || ' new: ' || NEW.name::text ||  E'\n\n';
                END IF;
            EXCEPTION WHEN OTHERS THEN END;
            BEGIN
                IF OLD.department_id <> NEW.department_id THEN
                t_descr = t_descr || 'department_id ' || E'\n';
                t_descr = t_descr || ' old: ' || OLD.department_id::text ||  E'\n';
                t_descr = t_descr || ' new: ' || NEW.department_id::text ||  E'\n\n';
                END IF;
            EXCEPTION WHEN OTHERS THEN END;
            BEGIN
                IF OLD.fired <> NEW.fired THEN
                t_descr = t_descr || 'fired ' || E'\n';
                t_descr = t_descr || ' old: ' || OLD.fired::text ||  E'\n';
                t_descr = t_descr || ' new: ' || NEW.fired::text ||  E'\n\n';
                END IF;
            EXCEPTION WHEN OTHERS THEN END;
            BEGIN
                IF OLD.employee_id <> NEW.employee_id THEN
                t_descr = t_descr || 'employee_id ' || E'\n';
                t_descr = t_descr || ' old: ' || OLD.employee_id::text ||  E'\n';
                t_descr = t_descr || ' new: ' || NEW.employee_id::text ||  E'\n\n';
                END IF;
            EXCEPTION WHEN OTHERS THEN END;
            BEGIN
                IF OLD.d_start <> NEW.d_start THEN
                t_descr = t_descr || 'd_start ' || E'\n';
                t_descr = t_descr || ' old: ' || OLD.d_start::text ||  E'\n';
                t_descr = t_descr || ' new: ' || NEW.d_start::text ||  E'\n\n';
                END IF;
            EXCEPTION WHEN OTHERS THEN END;
            BEGIN
                IF OLD.d_end <> NEW.d_end THEN
                t_descr = t_descr || 'd_end ' || E'\n';
                t_descr = t_descr || ' old: ' || OLD.d_end::text ||  E'\n';
                t_descr = t_descr || ' new: ' || NEW.d_end::text ||  E'\n\n';
                END IF;
            EXCEPTION WHEN OTHERS THEN END;
            INSERT INTO protocol(stamp,act,table_name,table_id,descr) SELECT 
              now(),
              'UPDATE',
              TG_TABLE_NAME::varchar(32),
              NEW.id,
              t_descr;
            RETURN NEW;
        ELSIF (TG_OP = 'INSERT') THEN
            INSERT INTO protocol(stamp,act,table_name,table_id,descr) SELECT 
              now(),
              'INSERT',
              TG_TABLE_NAME::varchar(32),
              NEW.id,
              '';
            RETURN NEW;
        END IF;
        RETURN NULL; -- возвращаемое значение для триггера AFTER игнорируется
    END;
$protoc$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS fire_protocol ON Department;
DROP TRIGGER IF EXISTS protocol_Department ON Department;

CREATE TRIGGER protocol_Department
AFTER INSERT OR UPDATE OR DELETE ON Department
    FOR EACH ROW EXECUTE PROCEDURE to_protocol();