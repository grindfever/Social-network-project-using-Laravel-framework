/*
--
-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS thingy CASCADE;
CREATE SCHEMA IF NOT EXISTS thingy;
SET search_path TO thingy;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);

--
-- Insert value.
--

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');
*/

--- Y Database ---

DROP SCHEMA IF EXISTS thingy CASCADE;
CREATE SCHEMA IF NOT EXISTS thingy;
SET search_path TO thingy;

--- DROP TABLE STATEMENTS ---
DROP TABLE if exists users CASCADE;
DROP TABLE if exists moderators CASCADE;
DROP TABLE if exists admins CASCADE;
DROP TABLE if exists posts CASCADE;
DROP TABLE if exists messages CASCADE;
DROP TABLE if exists comments CASCADE;
DROP TABLE if exists friend_requests CASCADE;
DROP TABLE if exists groups CASCADE;
DROP TABLE if exists notifications CASCADE;
DROP TABLE if exists bans CASCADE;
DROP TABLE if exists user_tagged_posts CASCADE;
DROP TABLE if exists user_likes_posts CASCADE;
DROP TABLE if exists user_likes_comments CASCADE;
DROP TABLE if exists post_removals CASCADE;
DROP TABLE if exists memberships CASCADE; 





CREATE TYPE notification_type_enum AS ENUM ('liked_comment', 
                                       'reply_comment',
                                       'comment_post',
                                       'invite_join', 
                                       'request_join', 
                                       'removed', 
                                       'accept_join', 
                                       'new_member', 
                                       'member_left',
                                       'message_received',
                                       'tagged_post', 
                                       'liked_post',
                                       'friend_request', 
                                       'accept_request'
                                       );


--USERS

CREATE TABLE users
(
    id SERIAL PRIMARY KEY,
    username character varying(256),
    password character varying(256) CONSTRAINT nn_users_password  NOT NULL,
    email character varying(256) CONSTRAINT uk_users_email UNIQUE
				CONSTRAINT nn_users_email NOT NULL,
    name character varying(256)  CONSTRAINT nn_users_name NOT NULL,
    bio character varying(256),
    remember_token VARCHAR
);

--MODERATOR

CREATE TABLE moderators
(
    id Serial PRIMARY KEY CONSTRAINT fk_moderator_username REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE 
);

--ADMIN

CREATE TABLE admins
(
    id INTEGER PRIMARY KEY CONSTRAINT fk_admin_username REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

--POST

CREATE TABLE posts
(
    id SERIAL PRIMARY KEY,
    user_id INTEGER CONSTRAINT fk_posts_userid REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_posts_userid NOT NULL,
    content character varying(512) ,
    date timestamp(0) without time zone CONSTRAINT nn_posts_date NOT NULL DEFAULT now(),
    img character varying(256),
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--MESSAGE

CREATE TABLE messages
(
    id SERIAL PRIMARY KEY,
    sender INTEGER CONSTRAINT fk_message_sender REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				 CONSTRAINT nn_message_sender NOT NULL,
    receiver INTEGER CONSTRAINT fk_message_receiver REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_message_receiver NOT NULL,
    content character varying(128),
    date timestamp(0) without time zone CONSTRAINT nn_message_date NOT NULL DEFAULT now(),
    viewed boolean DEFAULT FALSE,
    img character varying(256) ,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--COMMENT

CREATE TABLE comments
(
    id SERIAL PRIMARY KEY,
    user_id INTEGER CONSTRAINT fk_comment_userid REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_commente_userid NOT NULL,
    content character varying(128) ,
    img character varying(256) ,
    date timestamp(0) without time zone CONSTRAINT nn_comment_date NOT NULL,
    post_id integer CONSTRAINT fk_comment_post_id REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
                    CONSTRAINT nn_comment_post_id NOT NULL,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--FRIEND REQUEST

CREATE TABLE friend_requests
(
    sender INTEGER CONSTRAINT fk_friend_request_sender REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE ,
    receiver INTEGER CONSTRAINT fk_friend_request_receiver REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    accepted boolean CONSTRAINT nn_friend_request_accepted NOT NULL DEFAULT FALSE,
    request_date timestamp(0) without time zone CONSTRAINT nn_friend_request_request_date NOT NULL,
    accept_date timestamp(0) without time zone,
    PRIMARY KEY (sender, receiver)

);

--GROUPS

CREATE TABLE groups
(
    id SERIAL PRIMARY KEY,
    owner INTEGER CONSTRAINT fk_groups_owner REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				CONSTRAINT nn_groups_owner NOT NULL,
    name character varying(24)  CONSTRAINT nn_groups_name NOT NULL,
    description character varying(256)
);

--NOTIFICATION

CREATE TABLE notifications
(
    id SERIAL PRIMARY KEY,
    receiver INTEGER CONSTRAINT fk_notification_receiver REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
			           CONSTRAINT nn_notification_receiver NOT NULL,
    content character varying(128) CONSTRAINT nn_notification_content NOT NULL,
    viewed boolean CONSTRAINT nn_notification_viewed NOT NULL DEFAULT FALSE,
    date timestamp(0) without time zone CONSTRAINT nn_notification_date NOT NULL,
    notification_type notification_type_enum CONSTRAINT nn_post_notification_notification_type NOT NULL,
    id_post INTEGER REFERENCES posts(id) DEFAULT NULL,
    id_message INTEGER REFERENCES messages(id) DEFAULT NULL,
    id_comment INTEGER REFERENCES posts(id) DEFAULT NULL,
    id_group INTEGER REFERENCES posts(id) DEFAULT NULL
);

--BAN

CREATE TABLE bans
(
    id SERIAL PRIMARY KEY,
    moderator INTEGER CONSTRAINT fk_ban_moderator REFERENCES moderators(id) ON DELETE CASCADE ON UPDATE CASCADE
				    CONSTRAINT nn_ban_moderator NOT NULL,
    user_id INTEGER CONSTRAINT fk_ban_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE NOT NULL,
    ban_time integer CONSTRAINT nn_ban_ban_time NOT NULL DEFAULT 0,
    forever boolean CONSTRAINT nn_ban_forever NOT NULL DEFAULT FALSE,
    date timestamp(0) without time zone CONSTRAINT nn_ban_date NOT NULL
);

--USER TAGGED POST

CREATE TABLE user_tagged_posts
(
    user_id INTEGER CONSTRAINT fk_user_tagged_post_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id INTEGER CONSTRAINT fk_user_tagged_post_id REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, id)    
);

--USER LIKES POST
CREATE TABLE user_likes_posts
(
    user_id INTEGER CONSTRAINT fk_user_likes_post_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_likes_post_id REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, id)    
);

--USER LIKES COMMENT
CREATE TABLE user_likes_comments
(
    
    user_id INTEGER CONSTRAINT fk_user_likes_comment_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_likes_comment_id REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, id)    
);

--POST REMOVAL
CREATE TABLE post_removals
(
    id SERIAL PRIMARY KEY,
    moderator INTEGER CONSTRAINT fk_post_removal_moderator REFERENCES moderators(id) ON DELETE CASCADE ON UPDATE CASCADE
				    CONSTRAINT nn_post_removal_moderator NOT NULL,
    reason character varying(128) CONSTRAINT nn_post_removal_reason NOT NULL,
    date timestamp(0) without time zone CONSTRAINT nn_post_removal_date NOT NULL

);

--MEMBERSHIP
CREATE TABLE memberships
(
    possible_member INTEGER CONSTRAINT fk_membership_possible_member REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
					  CONSTRAINT nn_membership_possible_member NOT NULL,
    group_id INTEGER CONSTRAINT fk_membership_group_id REFERENCES groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
    accepted boolean CONSTRAINT nn_membership_accepted NOT NULL DEFAULT false,
    requested boolean CONSTRAINT nn_membership_requested NOT NULL DEFAULT false,
    accept_date timestamp(0) without time zone,
    req_or_inv_date timestamp(0) without time zone CONSTRAINT nn_membership_req_or_inv_date NOT NULL,
    member INTEGER CONSTRAINT fk_membership_member REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				 CONSTRAINT nn_membership_member NOT NULL,
    PRIMARY KEY (possible_member, group_id)
);

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  'admin@example.com',
  'John',
  'Funny person'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO posts VALUES (
    DEFAULT,
    1, 
    'My first post', 
    '2023-08-08'
);

INSERT INTO posts VALUES (
    DEFAULT,
    2, 
    'My second post,is a very large text completo de varios coisas e poucas coisas', 
    '2023-02-02'
);