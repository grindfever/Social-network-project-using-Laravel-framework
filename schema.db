PRAGMA foreign_keys = on;
.mode columns
.headers on

CREATE TYPE comment_notification_type AS ENUM
    ('liked_comment', 'reply_comment', 'comment_post');

CREATE TYPE group_notification_type AS ENUM
    ('invite_join', 'request_join', 'removed', 'accept_join', 'new_member', 'member_left');

CREATE TYPE message_notification_type AS ENUM
    ('message_received');

CREATE TYPE post_notification_type AS ENUM
    ('tagged_post', 'liked_post');

CREATE TYPE user_notification_type AS ENUM
    ('friend_request', 'accept_request');

--USERS
DROP TABLE if exists users;
CREATE TABLE users
(
    username character varying(16) PRIMARY KEY ,
    password character varying(32) CONSTRAINT nn_users_password  NOT NULL,
    email character varying(32) CONSTRAINT uk_users_email UNIQUE
				CONSTRAINT nn_users_email NOT NULL,
    name character varying(32)  CONSTRAINT nn_users_name NOT NULL,
    bio character varying(256)
);

--MODERATOR
DROP TABLE if exists moderator;
CREATE TABLE moderator
(
    username character varying(16) PRIMARY KEY CONSTRAINT fk_moderator_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE 
);

--ADMIN
DROP TABLE if exists admin;
CREATE TABLE admin
(
    username character varying(16) PRIMARY KEY CONSTRAINT fk_admin_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
);

--POST
DROP TABLE if exists post;
CREATE TABLE post
(
    id integer PRIMARY KEY DEFAULT nextval('post_id_seq'::regclass),
    username character varying(16) CONSTRAINT fk_post_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_post_username NOT NULL,
    content character varying(512) ,
    date timestamp(0) without time zone CONSTRAINT nn_post_date NOT NULL DEFAULT now(),
    img character varying(256),
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--MESSAGE
DROP TABLE if exists message;
CREATE TABLE message
(
    id integer PRIMARY KEY DEFAULT nextval('message_id_seq'::regclass),
    sender character varying(16) CONSTRAINT fk_message_sender REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
				 CONSTRAINT nn_message_sender NOT NULL,
    receiver character varying(16) CONSTRAINT fk_message_receiver REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_message_receiver NOT NULL,
    content character varying(128),
    date timestamp(0) without time zone CONSTRAINT nn_message_date NOT NULL DEFAULT now(),
    viewed boolean,
    img character varying(256) ,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--COMMENT
DROP TABLE if exists comment;
CREATE TABLE comment
(
    id integer PRIMARY KEY DEFAULT nextval('comment_id_seq'::regclass),
    username character varying(16) CONSTRAINT fk_comment_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_commente_username NOT NULL,
    content character varying(128) ,
    img character varying(256) ,
    date timestamp(0) without time zone CONSTRAINT nn_comment_date NOT NULL,
    post_id integer CONSTRAINT fk_comment_post_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE
                    CONSTRAINT nn_comment_post_id NOT NULL,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--FRIEND REQUEST
DROP TABLE if exists friend_request;
CREATE TABLE friend_request
(
    sender character varying(16) CONSTRAINT fk_friend_request_sender REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE ,
    receiver character varying(16) CONSTRAINT fk_friend_request_receiver REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE,
    accepted boolean CONSTRAINT nn_friend_request_accepted NOT NULL DEFAULT FALSE,
    request_date timestamp(0) without time zone CONSTRAINT nn_friend_request_request_date NOT NULL,
    accept_date timestamp(0) without time zone,
    PRIMARY KEY (sender, receiver)

);

--GROUPS
DROP TABLE if exists groups;
CREATE TABLE groups
(
    id integer PRIMARY KEY DEFAULT nextval('groups_id_seq'::regclass),
    owner character varying(16) CONSTRAINT fk_groups_owner REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
				CONSTRAINT nn_groups_owner NOT NULL,
    name character varying(24)  CONSTRAINT nn_groups_name NOT NULL,
    description character varying(256)
);

--NOTIFICATION
DROP TABLE if exists notification;
CREATE TABLE notification
(
    id integer PRIMARY KEY DEFAULT nextval('notification_id_seq'::regclass),
    receiver character varying(16) CONSTRAINT fk_notification_receiver REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
			           CONSTRAINT nn_notification_receiver NOT NULL,
    content character varying(128) CONSTRAINT nn_notification_content NOT NULL,
    viewed boolean CONSTRAINT nn_notification_viewed NOT NULL DEFAULT FALSE,
    date timestamp(0) without time zone CONSTRAINT nn_notification_date NOT NULL
);

--POST NOTIFICATION
DROP TABLE if exists post_notification;
CREATE TABLE post_notification
(
    id_notification integer PRIMARY KEY CONSTRAINT fk_post_notification_id_notification REFERENCES notification(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id_post integer CONSTRAINT fk_post_notification_id_post REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE
		    CONSTRAINT nn_post_notification_id_post NOT NULL,
    notification_type post_notification_type CONSTRAINT nn_post_notification_notification_type NOT NULL
);

--USER NOTIFICATION
DROP TABLE if exists user_notification;
CREATE TABLE user_notification
(
    id_notification integer PRIMARY KEY CONSTRAINT fk_user_notification_id_notification REFERENCES notification(id) ON DELETE CASCADE ON UPDATE CASCADE,
    username character varying(16) CONSTRAINT fk_user_notification_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
                                   CONSTRAINT nn_user_notification_username NOT NULL,
    notification_type post_notification_type nn_user_notification_notification_type NOT NULL
);

--MESSAGE NOTIFICATION
DROP TABLE if exists message_notification;
CREATE TABLE message_notification
(
    id_notification integer PRIMARY KEY CONSTRAINT fk_message_notification_id_notification REFERENCES notification(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id_message integer CONSTRAINT fk_message_notification_id_message REFERENCES message(id) ON DELETE CASCADE ON UPDATE CASCADE
		       CONSTRAINT nn_message_notification_id_message NOT NULL,
    notification_type post_notification_type CONSTRAINT nn_message_notification_notification_type NOT NULL
);

--COMMENT NOTIFICATION
DROP TABLE if exists comment_notification;
CREATE TABLE comment_notification
(
    id_notification integer PRIMARY KEY CONSTRAINT fk_comment_notification_id_notification REFERENCES notification(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id_comment integer CONSTRAINT fk_comment_notification_id_comment REFERENCES comment(id) ON DELETE CASCADE ON UPDATE CASCADE
		       CONSTRAINT nn_comment_notification_id_comment NOT NULL,
    notification_type post_notification_type CONSTRAINT nn_comment_notification_notification_type NOT NULL
);

--GROUP NOTIFICATION
DROP TABLE if exists group_notification;
CREATE TABLE group_notification
(
    id_notification integer PRIMARY KEY CONSTRAINT fk_group_notification_id_notification REFERENCES notification(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id_group integer CONSTRAINT fk_group_notification_id_group REFERENCES group(id) ON DELETE CASCADE ON UPDATE CASCADE
		     CONSTRAINT nn_group_notification_id_group NOT NULL,
    notification_type post_notification_type CONSTRAINT nn_group_notification_notification_type NOT NULL
);

--BAN
DROP TABLE if exists ban;
CREATE TABLE ban
(
    id integer PRIMARY KEY DEFAULT nextval('ban_id_seq'::regclass),
    moderator character varying(16) CONSTRAINT fk_ban_moderator REFERENCES moderator(username) ON DELETE CASCADE ON UPDATE CASCADE
				    CONSTRAINT nn_ban_moderator NOT NULL,
    username character varying(16) CONSTRAINT fk_ban_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE NOT NULL,
    ban_time integer CONSTRAINT nn_ban_ban_time NOT NULL DEFAULT 0,
    forever boolean CONSTRAINT nn_ban_forever NOT NULL DEFAULT FALSE,
    date timestamp(0) without time zone CONSTRAINT nn_ban_date NOT NULL
);

--USER TAGGED POST
DROP TABLE if exists user_tagged_post;
CREATE TABLE user_tagged_post
(
    username character varying(16) CONSTRAINT fk_user_tagged_post_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_tagged_post_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (username, id)    
);

--USER LIKES POST
DROP TABLE if exists user_likes_post;
CREATE TABLE user_likes_post
(
    username character varying(16) CONSTRAINT fk_user_likes_post_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_likes_post_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (username, id)    
);

--USER LIKES COMMENT
DROP TABLE if exists user_likes_comment;
CREATE TABLE user_likes_comment
(
    
    username character varying(16) CONSTRAINT fk_user_likes_comment_username REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_likes_comment_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (username, id)    
);

--POST REMOVAL
DROP TABLE if exists post_removal;
CREATE TABLE post_removal
(
    id integer PRIMARY KEY DEFAULT nextval('post_removal_id_seq'::regclass),
    moderator character varying(16) CONSTRAINT fk_post_removal_moderator REFERENCES moderator(username) ON DELETE CASCADE ON UPDATE CASCADE
				    CONSTRAINT nn_post_removal_moderator NOT NULL,
    reason character varying(128) CONSTRAINT nn_post_removal_reason NOT NULL,
    date timestamp(0) without time zone CONSTRAINT nn_post_removal_date NOT NULL

);

--MEMBERSHIP
DROP TABLE if exists membership; 
CREATE TABLE membership
(
    possible_member character varying(16) CONSTRAINT fk_membership_possible_member REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
					  CONSTRAINT nn_membership_possible_member NOT NULL,
    group_id integer CONSTRAINT fk_membership_group_id REFERENCES groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
    accepted boolean CONSTRAINT nn_membership_accepted NOT NULL DEFAULT false,
    requested boolean CONSTRAINT nn_membership_requested NOT NULL DEFAULT false,
    accept_date timestamp(0) without time zone,
    req_or_inv_date timestamp(0) without time zone CONSTRAINT nn_membership_req_or_inv_date NOT NULL,
    member character varying(16) CONSTRAINT fk_membership_member REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
				 CONSTRAINT nn_membership_member NOT NULL,
    PRIMARY KEY (possible_member, group_id)
)

/*--------------------------------------------------------------------------*/

-- Indexes

CREATE INDEX IDX01 ON users (username);
CREATE INDEX IDX02 ON post (username);
CREATE INDEX IDX03 ON comment (post_id);

-- FTS Indexes

CREATE INDEX IDX04 ON post USING GIN (to_tsvector('english', content));
CREATE INDEX IDX05 ON message USING GIN (to_tsvector('english', content));

/*.................................................................................*/

--FUNCTIONS


CREATE SEQUENCE t_notification_id_seq;

--FUNCAO PARA NOTIFICACOES DE MENSAGENS
CREATE OR REPLACE FUNCTION create_message_notification()
RETURNS TRIGGER AS 
$BODY$
BEGIN
    INSERT INTO notification(id, receiver, content, viewed, date)
    VALUES (nextval('t_notification_id_seq'), NEW.receiver, NEW.content, NEW.viewed, NEW.date);

    INSERT INTO message_notification(id_notification, id_message, notification_type)
    VALUES (currval('t_notification_id_seq'), NEW.id, 'message_received');

  RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;



--FUNCAO PARA NOTIFICACOES DE LIKES EM POSTS
CREATE OR REPLACE FUNCTION create_like_post_notification()
RETURNS TRIGGER AS 
$BODY$
DECLARE 
    post_content CHAR VARYING(128);
    post_date DATE;
    post_username CHAR VARYING(16);
BEGIN

    SELECT post.username, post.date, post.content INTO post_username, post_date, post_content
    FROM post
    WHERE post.id = NEW.id;

    INSERT INTO notification(id, receiver, content, viewed, date)
    VALUES (nextval('t_notification_id_seq'), post_username, post_content, FALSE, post_date);

    INSERT INTO post_notification(id_notification, id_post, notification_type)
    VALUES (currval('t_notification_id_seq'), NEW.id, 'liked_post');

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;


--FUNCAO PARA NOTIFICACOES DE TAGS EM POSTS
CREATE OR REPLACE FUNCTION create_tag_post_notification()
RETURNS TRIGGER AS 
$BODY$
DECLARE 
    post_content CHAR VARYING(128);
    post_date DATE;
BEGIN

    SELECT post.date, post.content INTO post_date, post_content
    FROM post
    WHERE post.id = NEW.id;

    INSERT INTO notification(id, receiver, content, viewed, date)
    VALUES (nextval('t_notification_id_seq'), NEW.username, post_content, FALSE, post_date);

    INSERT INTO post_notification(id_notification, id_post, notification_type)
    VALUES (currval('t_notification_id_seq'), NEW.id, 'tagged_post');

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

--FUNCAO PARA NOTIFICACOES DE LIKES EM POSTS
CREATE OR REPLACE FUNCTION create_like_comment_notification()
RETURNS TRIGGER AS 
$BODY$
DECLARE 
    comment_content CHAR VARYING(128);
    comment_date DATE;
    comment_username CHAR VARYING(16);
BEGIN

    SELECT comment.username, comment.date, comment.content INTO comment_username, comment_date, comment_content
    FROM comment
    WHERE comment.post_id = NEW.id;

    INSERT INTO notification(id, receiver, content, viewed, date)
    VALUES (nextval('t_notification_id_seq'), comment_username, comment_content, FALSE, comment_date);

    INSERT INTO comment_notification(id_notification, id_comment, notification_type)
    VALUES (currval('t_notification_id_seq'), NEW.id, 'liked_comment');

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;


--FUNCAO PARA NOTIFICACOES DE FRIEND REQUESTS
CREATE OR REPLACE FUNCTION create_friend_request_notification()
RETURNS TRIGGER AS 
$BODY$
BEGIN

    INSERT INTO notification(id, receiver, content, viewed, date)
    VALUES (nextval('t_notification_id_seq'), NEW.receiver, '', FALSE, NEW.request_date);

    INSERT INTO user_notification(id_notification, username, notification_type)
    VALUES (currval('t_notification_id_seq'), NEW.sender, 'friend_request');

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

--FUNCAO PARA VERIFICAR ESTADO DA FRIEND REQUEST
CREATE OR REPLACE FUNCTION check_friend_request_state()
RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF NEW.accepted != OLD.accepted AND NEW.accepted = TRUE 
    THEN
        INSERT INTO notification(id, receiver, content, viewed, date)
        VALUES (nextval('t_notification_id_seq'), NEW.sender, '', FALSE, NEW.accept_date);

        INSERT INTO user_notification(id_notification, username, notification_type)
        VALUES (currval('t_notification_id_seq'), NEW.receiver, 'accept_request');
    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

--FUNCAO PARA NOTIFICAR USUARIO QUANDO UM RECEBE UM CONVITE PARA UM GRUPO OU PARA NOTIFICAR O DONO DO GRUPO CASO O USUARIO PEÇA PARA ENTRAR NO GRUPO
CREATE OR REPLACE FUNCTION create_group_request_notification()
RETURNS TRIGGER AS 
$BODY$
DECLARE 
    group_owner_var CHAR VARYING(16); 
BEGIN
    IF NEW.requested 
    THEN 
        SELECT owner INTO group_owner_var
        FROM groups
        WHERE groups.id == NEW.group_id;

        INSERT INTO notification(id, receiver, content, date)
        VALUES (nextval('t_notification_id_seq'), group_owner_var, '', NEW.req_or_inv_date);

        INSERT INTO group_notification(id_notification, , notification_type)
        VALUES (currval('t_notification_id_seq'), NEW.group_id, 'request_join');
    ELSE 
        INSERT INTO notification(id, receiver, content, date)
        VALUES (nextval('t_notification_id_seq'), NEW.possible_member, '', NEW.req_or_inv_date);

        INSERT INTO group_notification(id_notification, , notification_type)
        VALUES (currval('t_notification_id_seq'), NEW.group_id, 'invite_join');

    END IF;
    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

/*.................................................................................*/

--TRIGGERS

--TRIGGER PARA NOTIFICACOES DE MENSAGENS
CREATE TRIGGER message_insert_trigger
AFTER INSERT ON message
FOR EACH ROW
EXECUTE FUNCTION create_message_notification();

--TRIGGER PARA NOTIFICACOES DE LIKES EM POSTS
CREATE TRIGGER like_post_trigger
AFTER INSERT ON user_likes_post
FOR EACH ROW
EXECUTE FUNCTION create_like_post_notification();

--TRIGGER PARA NOTIFICACOES DE TAGS EM POSTS
CREATE TRIGGER tag_post_trigger
AFTER INSERT ON user_tagged_post
FOR EACH ROW
EXECUTE FUNCTION create_tag_post_notification();

--TRIGGER PARA NOTIFICACOES DE LIKES EM COMENTARIOS
CREATE TRIGGER like_comment_trigger
AFTER INSERT ON user_likes_comment
FOR EACH ROW
EXECUTE FUNCTION create_like_comment_notification();


--TRIGGER PARA NOTIFICACAO DE FRIEND REQUEST
CREATE TRIGGER friend_request_notification_trigger
AFTER INSERT ON friend_request
FOR EACH ROW
EXECUTE FUNCTION create_friend_request_notification();

--TRIGGER PARA VERIFICAR MUDANCAS NAS FRIEND REQUESTS
CREATE TRIGGER friend_request_update_trigger
AFTER UPDATE ON friend_request
FOR EACH ROW
EXECUTE FUNCTION check_friend_request_state();

--TRIGGER PARA NOTIFICAR USUARIO QUANDO UM RECEBE UM CONVITE PARA UM GRUPO
CREATE TRIGGER group_request_notification_trigger()
AFTER INSERT ON membership
FOR EACH ROW
EXECUTE FUNCTION create_group_request_notification();

/*--------------------------------------------------------------------------------------------*/

-- TRANSACTIONS

--TRAN01
 BEGIN; SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
  -- Update the notification to mark the follow request as accepted
UPDATE notification
SET content = 'Friend request accepted', viewed = true, NOW()
WHERE id = :notification_id;
COMMIT;

--TRAN02

BEGIN;

-- Insert a new comment notification
INSERT INTO notification (receiver, content, viewed, date)
VALUES (:receiver_username, 'You have a new comment on your post.', FALSE, NOW())
RETURNING id INTO :notification_id;

-- Insert the specific comment notification
INSERT INTO comment_notification (id_notification, id_comment, notification_type)
VALUES (:notification_id, :comment_id, 'comment_post');

-- Commit the transaction to save the changes
COMMIT;
