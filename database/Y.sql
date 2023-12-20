
--- Y Database ---

DROP SCHEMA IF EXISTS lbaw23102 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw23102;
SET search_path TO lbaw23102;

--- DROP TABLE STATEMENTS ---
DROP TABLE if exists users CASCADE;
DROP TABLE if exists moderators CASCADE;
DROP TABLE if exists admins CASCADE;
DROP TABLE if exists posts CASCADE;
DROP TABLE if exists post_likes CASCADE;
DROP TABLE IF EXISTS messages CASCADE;
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
DROP TABLE if exists group_messages CASCADE;  
DROP TABLE if exists friends CASCADE;

DROP TRIGGER IF EXISTS post_search_update ON posts;

DROP FUNCTION IF EXISTS post_search_update;

DROP INDEX IF EXISTS idx_post_search;





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

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  username VARCHAR,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  bio character varying(256),
  age INTEGER, --CONSTRAINT nn_users_age NOT NULL,
  img VARCHAR,
  priv BOOLEAN DEFAULT TRUE,
  remember_token VARCHAR,
  recover_token VARCHAR
);

--MODERATOR

CREATE TABLE moderators
(
    id Serial PRIMARY KEY CONSTRAINT fk_moderator_username REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE 
);

--ADMIN

CREATE TABLE admins
(
  id SERIAL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);

--POST

CREATE TABLE posts
(
    id SERIAL PRIMARY KEY,
    user_id INTEGER CONSTRAINT fk_posts_userid REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_posts_userid NOT NULL,
    title character varying(128) ,
    content character varying(512) ,
    date timestamp(0) without time zone CONSTRAINT nn_posts_date NOT NULL DEFAULT now(),
    public boolean NOT NULL DEFAULT TRUE,
    img VARCHAR,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--Post likes

CREATE TABLE post_likes
(
    post_id INTEGER CONSTRAINT fk_post_likes_post_id REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
            CONSTRAINT nn_post_likes_post_id NOT NULL,
    user_id INTEGER CONSTRAINT fk_post_likes_user_id REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
            CONSTRAINT nn_post_likes_user_id NOT NULL,
    PRIMARY KEY (post_id, user_id)
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
    date timestamp(0) without time zone CONSTRAINT nn_comment_date NOT NULL,
    img character varying(256) ,
    post_id integer CONSTRAINT fk_comment_post_id REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
                    CONSTRAINT nn_comment_post_id NOT NULL,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);


CREATE TABLE comment_likes
(
    comment_id INTEGER CONSTRAINT fk_comment_likes_comment_id REFERENCES comments(id) ON DELETE CASCADE ON UPDATE CASCADE
            CONSTRAINT nn_comment_likes_comment_id NOT NULL,
    user_id INTEGER CONSTRAINT fk_post_likes_user_id REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
            CONSTRAINT nn_post_likes_user_id NOT NULL,
    PRIMARY KEY (comment_id, user_id)
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
CREATE TABLE friends
(
  id SERIAL PRIMARY KEY, 
  user_id1 INTEGER CONSTRAINT fk_friends_user_id1 REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_id2 INTEGER CONSTRAINT fk_friends_user_id2 REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);


--- INDEXES ---

ALTER TABLE posts
ADD COLUMN search TSVECTOR;


CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
  NEW.search = (
    to_tsvector('english',NEW.title)
  );
  RETURN NEW;
END $$
LANGUAGE plpgsql;  


CREATE TRIGGER post_search_update
  BEFORE INSERT ON posts
  FOR EACH ROW
  EXECUTE PROCEDURE post_search_update();

CREATE INDEX idx_post_search ON posts USING GIN (search);


--- TRIGGERS ---



--- TRANSACTIONS ---

--- DATA ---
--GROUP MESSAGES

CREATE TABLE group_messages
(
    id SERIAL PRIMARY KEY,
    group_id INTEGER CONSTRAINT fk_group_message_group_id REFERENCES groups(id) ON DELETE CASCADE ON UPDATE CASCADE
                 CONSTRAINT nn_group_message_group_id NOT NULL,
    sender INTEGER CONSTRAINT fk_group_message_sender REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
                 CONSTRAINT nn_group_message_sender NOT NULL,
    content character varying(128),
    date timestamp(0) without time zone CONSTRAINT nn_group_message_date NOT NULL DEFAULT now(),
    viewed boolean DEFAULT FALSE,
    img character varying(256) ,
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);


INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'John',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  'Funny person',
  29,
  DEFAULT
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  DEFAULT,
  'Diogo',
  'Diogo',
  'diogo@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  'Yo',
  21,
  DEFAULT
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  DEFAULT,
  'a',
  'A',
  'a@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  'a',
  18,
  DEFAULT
); -- Password is 1234. Generated using Hash::make('1234')

-- Populating users table
INSERT INTO users VALUES (
  DEFAULT,
  'Emily',
  'Emily Smith',
  'emily@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Coder and designer',
  25,
  DEFAULT
);

INSERT INTO users VALUES (
  DEFAULT,
  'Mark',
  'Mark Johnson',
  'mark@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Travel enthusiast',
  30,
  DEFAULT
);

-- Add more users as needed

INSERT INTO posts VALUES (
  DEFAULT,
  1,
  'Hello World',
  'Hello World! This is my first post.',
  '2022-01-01 12:00:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  1,
  'Exciting News!',
  'I just got accepted into my dream university! So excited to start this new chapter in my life.',
  '2022-01-02 13:30:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  2,
  'Travel Adventures',
  'Just came back from an amazing trip to Bali. The beaches were stunning and the food was incredible!',
  '2022-01-03 15:45:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  2,
  'New Recipe',
  'Tried out a new recipe today and it turned out delicious! Sharing it with you all.',
  '2022-01-04 09:15:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  3,
  'Fitness Journey',
  'Started my fitness journey today. Looking forward to getting healthier and stronger!',
  '2022-01-05 17:20:00',
  DEFAULT,
  DEFAULT
);


INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Congratulations! That is amazing news!',
  '2022-01-01 12:30:00',
  DEFAULT,
  1
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'So happy for you! Best of luck in your new adventure.',
  '2022-01-01 13:00:00',
  DEFAULT,
  2
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Bali is definitely on my bucket list. Your pictures look incredible!',
  '2022-01-02 14:00:00',
  DEFAULT,
  3
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Please share the recipe! I am always looking for new dishes to try.',
  '2022-01-02 15:30:00',
  DEFAULT,
  4
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'You got this! Stay motivated and enjoy the journey.',
  '2022-01-03 16:45:00',
  DEFAULT,
  5
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'Let me know if you need any workout tips. I am happy to help!',
  '2022-01-03 18:00:00',
  DEFAULT,
  5
);


-- Add more posts as needed


INSERT INTO messages VALUES (
  DEFAULT, 1, 2, 'Blah1', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 3, 'Blah2', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 2, 1, 'Blah3', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 2, 3, 'Blah4', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 3, 1, 'Blah5', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 3, 2, 'Blah6', DEFAULT, DEFAULT
);


INSERT INTO groups VALUES (
    DEFAULT,
    1, 
    'Loucos', 
    'os mais loucos do leic'
);

INSERT INTO memberships VALUES (
    1,
    1,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO memberships VALUES (
    2,
    1,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO memberships VALUES (
    3,
    1,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO group_messages VALUES (
  DEFAULT, 1, 1, 'primeira', DEFAULT, DEFAULT
);

INSERT INTO group_messages VALUES (
  DEFAULT, 1, 2, 'segunda', DEFAULT, DEFAULT
);

INSERT INTO group_messages VALUES (
  DEFAULT, 1, 3, 'terceira', DEFAULT, DEFAULT
);

INSERT INTO friend_requests (sender, receiver, accepted, request_date)
VALUES (2, 1, FALSE, NOW());