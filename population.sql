INSERT INTO users(username,password,email,name,bio)
VALUES ('diog0', '123m', 'diog0@gmail.com', 'Diogo', 'Wandering...');
INSERT INTO users(username,password,email,name,bio)
VALUES ('marc0', 'mypass', 'marc0@gmail.com', 'Marco', 'My name is Marco!');
INSERT INTO users(username,password,email,name,bio)
VALUES ('rui_', 'mypass', 'rui@gmail.com', 'Rui', '');
INSERT INTO users(username, password, email, name, bio)
VALUES ('joao1', '1senha12', 'joao1@email.com', 'João', 'This is João.');
INSERT INTO users(username, password, email, name, bio)
VALUES ('ped_r0', 'Rua12', 'pedr0@email.com', 'Pedro', 'Energy.');
INSERT INTO users(username, password, email, name, bio)
VALUES ('sofi', 'just', 'sofia@email.com', 'Sofia', '');
INSERT INTO users(username, password, email, name, bio)
VALUES ('miGuel', '3241iu981', 'miguelito@email.com', 'Miguel', 'Miguel. 22.');
INSERT INTO users(username, password, email, name, bio)
VALUES ('mariana', 'asjhlad', 'mariana@email.com', 'Mariana', 'Studying!');


INSERT INTO post(username, content, date) 
VALUES ('diog0', 'My first post', '2023-08-08');

INSERT INTO user_likes_post(username,id)
VALUES('diog0',2);


insert into friend_request(sender,receiver,accepted,request_date,accept_date)
values('diog0','marc0',false, '2023-04-04',null);
