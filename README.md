Тестове завдання - приклад
=============
Виконано тут - [https://holy-test.herokuapp.com](https://holy-test.herokuapp.com "Так, ось тут")
>По секрету: я автор, просто перевірив, чи реально виконати, що сам напридумував

Завдання
=============
<pre>
Підрозділи
Department(
 id autoinc ,      /* Перв.ключ*/
 name varchar(100) /* Назва підрозділу */
)

Співробітники
Employee(
 id autoinc ,        /* Перв.ключ */
 department_id int,  /* Посилання на підрозділ – Department.id */
 name varchar(128),  /* ПІБ співробітника */
 fired date          /* Дата звільнення */
)

Періоди відпустки співробітників
Vacations(
 id autoinc ,      /* Перв.ключ */
 employee_id int,  /* Посилання на співробітника – Employee.id */
 d_start date,     /* Дата початку відпустки */
 d_end date        /* Дата кінця відпустки */
)
</pre>

1) Створити таблиці, заповнити даними (щонайменше 5 підрозділів, 50 співробітників, по 2 періоди відпустки для кожного співробітника).

2) Створити засоби перегляду/додавання/корекції/видалення для підрозділів, співробітників, періодів відпустки з можливістю сортування і фільтрації по кожному полю. Посилання мають коригуватися методом вибору із значень поля __name__ пов`язаної таблиці, також це поле має виводитися на перегляд замість цілочисельного значення посилання.

3) Створити засіб перегляду всіх пар незвільнених співробітників з одного підрозділу, у яких перетинаються періоди відпустки. Включити у вибірку поля 
- назва підрозділу,
- ПІБ першого співробітника, 
- ПІБ другого співробітника, у якого період відпустки перетинається 
- мінімальна дата, коли періоди відпустки перетинаються.

4) Реалізувати протоколювання дій над існуючими даними – для кожної таблиці створити тригери після додавання, видалення та корекції, що додають рядок в таблицю __protocol__ наступної структури: дата, час, дія (INSERT, UPDATE, DELETE), назва таблиці, значення первинного ключа (__id__), коментар у випадку корекції – значення поля до і після редагування.
