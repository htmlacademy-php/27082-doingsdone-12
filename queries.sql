/*
добавление проектов
*/
INSERT INTO project (`title`,`id`) VALUES
                                          ("УЧЕБА",1),
                                          ("ВХОДЯЩИЕ",1),
                                          ("РАБОТА",1),
                                          ("ДОМАШНИЕ ДЕЛА",1),
                                          ("АВТО",1),
                                          ("МАГАЗИН",2),
                                          ("МЕТРО",2),
                                          ("ХЛАМ",2);

/*
добавление пользователей
*/
  INSERT INTO users (`name`,`email`,`password`,`data`) VALUES
  ("denni","den@mirom@mir","aAESWF@Q","2022-05-17"),
  ("konst","konst@mirom@mir","sdfc","2022-03-17");
/*
добавление списка задача
*/
INSERT INTO task (`name`, `deadline`, `project_id`, `user`)
VALUES ("Собеседование в IT компании", "2020-12-01",1,1),
  ("Выполнить тестовое задание", "2021-11-06",2,1),
  ("Сделать задание новое 3", "2022-12-21",3,2),
  ("Встреча с другом", "2022-10-22",2,2),
  ("Купить корм для кота", NULL,4,1),
  ("Заказать пиццу", NULL,5,2),
  ("тестирование", "2022-11-01",6,1),
  ("поход в магазин", "2022-09-30",7,1),
  ("покушать", "2022-11-22",8,2),
  ("поспать", "2012-10-01",8,2),
  ("игры", "2022-01-27",2,1),
  ("встреча", "2022-10-07",3,1);
/*
названия всех задач определенного пользователя
*/
SELECT name FROM task WHERE `user`=1;
/*
названия всех проектов определенного пользователя
*/
SELECT title FROM project WHERE `user_id`=1;
/*
пометить задачу выполненной
*/
UPDATE task SET STATUS = 1 WHERE name = "игры";

/*
смена названия
*/
UPDATE task SET name = "home"WHERE id = 3;

/*
сведение таблиц
*/
SELECT * FROM users inner JOIN task ON users.id=task.user;
