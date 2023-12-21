
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
DROP TABLE if exists report_users CASCADE; 
DROP TABLE if exists report_posts CASCADE;
DROP TABLE if exists report_groups CASCADE;
DROP TABLE if exists group_messages CASCADE;  
DROP TABLE if exists friends CASCADE;

DROP TRIGGER IF EXISTS post_search_update ON posts;

DROP FUNCTION IF EXISTS post_search_update;

DROP INDEX IF EXISTS idx_post_search;

DROP TRIGGER IF EXISTS group_search_update ON groups;

DROP FUNCTION IF EXISTS group_search_update;

DROP INDEX IF EXISTS idx_group_search;




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
  recover_token VARCHAR,
  deleted_at timestamp(0) without time zone 
);

--MODERATOR

CREATE TABLE moderators
(
  id INTEGER PRIMARY KEY CONSTRAINT fk_moderator_id REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE 
);

--ADMIN

CREATE TABLE admins
(
  id SERIAL PRIMARY KEY,
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
    admin INTEGER CONSTRAINT fk_ban_ADMIN REFERENCES admins(id) ON DELETE CASCADE ON UPDATE CASCADE,
    moderator INTEGER CONSTRAINT fk_ban_moderator REFERENCES moderators(id) ON DELETE CASCADE ON UPDATE CASCADE,
    user_id INTEGER CONSTRAINT fk_ban_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE NOT NULL,
    date timestamp(0) without time zone CONSTRAINT nn_ban_date NOT NULL DEFAULT now(),
    CHECK (admin IS NOT NULL OR moderator IS NOT NULL)
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
    date timestamp(0) without time zone CONSTRAINT nn_post_removal_date NOT NULL DEFAULT now()

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
  IF TG_OP = 'INSERT' THEN   
    NEW.search = (
      setweight(to_tsvector('english',NEW.title),'A') || setweight(to_tsvector('english',NEW.content),'B') 

    );
  END IF;
  IF  TG_OP = 'UPDATE' THEN
    IF(NEW.title <> OLD.title OR NEW.content <> OLD.content) THEN
      NEW.search = (
          setweight(to_tsvector('english',NEW.title),'A') || setweight(to_tsvector('english',NEW.content),'B') 
        );
    END IF;
  END IF;
  RETURN NEW;
END $$
LANGUAGE plpgsql;  


CREATE TRIGGER post_search_update
  BEFORE INSERT OR UPDATE ON posts
  FOR EACH ROW
  EXECUTE PROCEDURE post_search_update();

CREATE INDEX idx_post_search ON posts USING GIN (search);


ALTER TABLE groups
ADD COLUMN search TSVECTOR;


CREATE FUNCTION group_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' THEN   
    NEW.search = (
      setweight(to_tsvector('english',NEW.name),'A') 
    );
  END IF;
  IF  TG_OP = 'UPDATE' THEN
    IF(NEW.name <> OLD.name) THEN
      NEW.search = (
          setweight(to_tsvector('english',NEW.name),'A') 
        );
    END IF;
  END IF;
  RETURN NEW;
END $$
LANGUAGE plpgsql;  


CREATE TRIGGER group_search_update
  BEFORE INSERT OR UPDATE ON groups
  FOR EACH ROW
  EXECUTE PROCEDURE group_search_update();

CREATE INDEX idx_group_search ON posts USING GIN (search);



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


--REPORT USER
CREATE TABLE report_users
(
  id SERIAL PRIMARY KEY,
  user_reported_id INTEGER CONSTRAINT fk_reported_user REFERENCES users(id) ON DELETE CASCADE CONSTRAINT nn_reported NOT NULL,
  user_who_repoted_id INTEGER CONSTRAINT fk_who_reported_user REFERENCES users(id) ON DELETE CASCADE CONSTRAINT nn_who_reported NOT NULL,
  date timestamp(0) without time zone CONSTRAINT nn_date NOT NULL DEFAULT now()
);

--REPORT USER
CREATE TABLE report_posts
(
  id SERIAL PRIMARY KEY,
  post_id INTEGER CONSTRAINT fk_reported_post REFERENCES posts(id) ON DELETE CASCADE CONSTRAINT nn_reported NOT NULL,
  user_id INTEGER CONSTRAINT fk_who_reported_user REFERENCES users(id) ON DELETE CASCADE CONSTRAINT nn_who_reported NOT NULL,
  date timestamp(0) without time zone CONSTRAINT nn_date NOT NULL DEFAULT now()
);

--REPORT USER
CREATE TABLE report_groups
(
  id SERIAL PRIMARY KEY,
  group_id INTEGER CONSTRAINT fk_reported_group REFERENCES groups(id) ON DELETE CASCADE CONSTRAINT nn_reported NOT NULL,
  user_who_repoted_id INTEGER CONSTRAINT fk_who_reported_user REFERENCES users(id) ON DELETE CASCADE CONSTRAINT nn_who_reported NOT NULL,
  date timestamp(0) without time zone CONSTRAINT nn_date NOT NULL DEFAULT now()
);

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'John',
  'john@example.com',
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
  'Afonso Dias',
  'Afonso',
  'afonso@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  'skrt',
  18,
  DEFAULT
); -- Password is 1234. Generated using Hash::make('1234')


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


INSERT INTO users VALUES (
  DEFAULT,
  'Sophie',
  'Sophie Turner',
  'sophie@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Actress',
  18,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Daniel',
  'Daniel White',
  'daniel@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Software Engineering Student',
  19,
  DEFAULT
);

INSERT INTO users VALUES (
  DEFAULT,
  'Olivia',
  'Olivia Davis',
  'olivia@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Marketing Student',
  21,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Ethan',
  'Ethan Brooks',
  'ethan@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Graphic Design Student',
  20,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Ava',
  'Ava Robinson',
  'ava@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Photography Student',
  22,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Carter',
  'Carter Miller',
  'carter@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Finance Student',
  23,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Hannah',
  'Hannah Lee',
  'hannah@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Interior Design Student',
  24,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Connor',
  'Connor Clark',
  'connor@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Journalism Student',
  26,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'Grace',
  'Grace Turner',
  'grace@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Biology Student',
  25,
  DEFAULT
);


INSERT INTO users VALUES (
  DEFAULT,
  'James',
  'James Foster',
  'james@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', -- Password is 1234
  'Computer Science Student',
  22,
  DEFAULT
);


INSERT INTO admins VALUES (
  DEFAULT,
  'boss@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
);


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
  4,
  'Sou vski',
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

INSERT INTO posts VALUES (
  DEFAULT,
  1,
  'Hello Again World',
  'Hello World! This is my second post. Or is it?',
  '2022-01-01 12:00:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  1,
  'Exciting Stuff!',
  'I just got accepted into my dream job! So excited to start working.',
  '2022-01-02 13:30:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  2,
  'Travelling',
  'Going on a trip to London for a few months. Hopefully I can catch the British GP',
  '2022-01-03 15:45:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  2,
  'Pancake Recipe',
  'Does anyone know Carlos Sainz pancake recipe?',
  '2022-01-04 09:15:00',
  DEFAULT,
  DEFAULT
);


INSERT INTO posts VALUES (
  DEFAULT,
  3,
  'Fitness is hard',
  'Started my fitness journey a few days ago but feel like giving up',
  '2022-01-08 17:20:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  4,
  'Coding Challenges',
  'Completed a set of coding challenges today. Feeling accomplished!',
  '2022-01-06 14:10:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  4,
  'Tech Conference',
  'Attended a tech conference and learned about the latest developments in AI and machine learning.',
  '2022-01-07 11:45:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  5,
  'Artistic Creations',
  'Worked on some artistic creations today. Feeling inspired!',
  '2022-01-08 18:30:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  5,
  'Gallery Exhibition',
  'Visited a gallery exhibition showcasing incredible artworks. Truly inspiring!',
  '2022-01-09 16:00:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  6,
  'Finance Insights',
  'Explored finance insights and investment strategies. Learning something new every day!',
  '2022-01-10 12:20:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  6,
  'Stock Market Trends',
  'Analyzed stock market trends and made some strategic investments. Fingers crossed!',
  '2022-01-11 09:45:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  7,
  'Interior Design Projects',
  'Worked on some exciting interior design projects today. Cant wait to see the final results!',
  '2022-01-12 14:30:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  7,
  'Home Renovation',
  'Planning a home renovation project. Looking for creative ideas and inspiration!',
  '2022-01-13 10:55:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  8,
  'Breaking News',
  'Stay tuned for breaking news updates. Keeping you informed on the latest events!',
  '2022-01-14 17:10:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  8,
  'Global Trends',
  'Exploring global trends and their impact on various industries. The world is constantly changing!',
  '2022-01-15 15:15:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  9,
  'Environmental Initiatives',
  'Engaged in environmental initiatives to promote sustainability. Every action counts!',
  '2022-01-16 11:30:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
  9,
  'Nature Photography',
  'Capturing the beauty of nature through photography. A picture is worth a thousand words!',
  '2022-01-17 08:45:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
   10,
  'Journalism Updates',
  'Providing journalism updates on current events. Journalism is about telling the truth!',
  '2022-01-18 16:00:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO posts VALUES (
  DEFAULT,
   10,
  'Interviewing Experts',
  'Conducted interviews with experts in the field. Sharing valuable insights with you!',
  '2023-12-19 14:05:00',
  DEFAULT,
  DEFAULT
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Congratulations! That is amazing news!',
  '2023-01-01 12:30:00',
  DEFAULT,
  1
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'So happy for you! Best of luck in your new adventure.',
  '2023-01-01 13:00:00',
  DEFAULT,
  2
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Bali is definitely on my bucket list. Your pictures look incredible!',
  '2023-01-02 14:00:00',
  DEFAULT,
  3
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Please share the recipe! I am always looking for new dishes to try.',
  '2023-01-02 15:30:00',
  DEFAULT,
  4
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'You got this! Stay motivated and enjoy the journey.',
  '2023-01-03 16:45:00',
  DEFAULT,
  5
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'Let me know if you need any workout tips. I am happy to help!',
  '2023-01-03 18:00:00',
  DEFAULT,
  5
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Congratulations on getting accepted! What will you be studying?',
  '2023-01-02 14:15:00',
  DEFAULT,
  2
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'That sounds amazing! I have always wanted to visit Bali. Any recommendations for places to visit?',
  '2023-01-03 16:00:00',
  DEFAULT,
  3
);


INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'The recipe looks delicious! I would love to try it out.',
  '2023-01-04 10:00:00',
  DEFAULT,
  4
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'Great job on trying out new recipes! Whats your favorite type of cuisine?',
  '2023-01-04 11:30:00',
  DEFAULT,
  4
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Keep up the good work on your fitness journey! You inspire me to start mine.',
  '2023-01-05 18:30:00',
  DEFAULT,
  5
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Im interested in starting a fitness journey too. Any tips for beginners?',
  '2023-01-05 19:45:00',
  DEFAULT,
  5
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'I didnt ask lol',
  '2023-01-07 12:30:00',
  DEFAULT,
  6
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'I see you are a coder. Wanna talk?',
  '2023-01-08 09:15:00',
  DEFAULT,
  6
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Your artworks are beautiful! Im sure they will be lucky to have you at your new job',
  '2022-01-09 17:00:00',
  DEFAULT,
  7
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'What is the job? Please tell me',
  '2022-01-10 10:30:00',
  DEFAULT,
  7
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'I love the UK',
  '2022-01-11 11:00:00',
  DEFAULT,
  8
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'London > Lisbon',
  '2022-01-11 14:45:00',
  DEFAULT,
  8
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'No idea',
  '2022-01-12 15:30:00',
  DEFAULT,
  9
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'I heard he shared it in a youtube video on the F1 channel',
  '2022-01-13 11:45:00',
  DEFAULT,
  9
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Dont give up bro you got this',
  '2022-01-14 18:30:00',
  DEFAULT,
  10
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Same XD',
  '2022-01-15 16:00:00',
  DEFAULT,
  10
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'The next Elon Musk',
  '2022-01-16 12:20:00',
  DEFAULT,
  11
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Zuckerberg WHO? You are so crazy',
  '2022-01-17 09:45:00',
  DEFAULT,
  11
);

INSERT INTO comments VALUES (
  DEFAULT,
   1,
  'What did you learn?',
  '2022-01-18 17:10:00',
  DEFAULT,
  12
);

INSERT INTO comments VALUES (
  DEFAULT,
   3,
  'Where????',
  '2022-01-19 15:15:00',
  DEFAULT,
  12
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'This post is insightful! Have you considered writing more on this topic?',
  '2022-02-01 14:45:00',
  DEFAULT,
  13
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'I completely agree with your perspective. Lets continue this conversation!',
  '2022-02-02 16:30:00',
  DEFAULT,
  13
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Sounds nice! Where is it?',
  '2022-02-03 09:15:00',
  DEFAULT,
  14
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'One day they will have my paintings there',
  '2022-02-03 10:45:00',
  DEFAULT,
  14
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'I have no idea how that stuff works haha',
  '2022-02-04 17:20:00',
  DEFAULT,
  15
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Share what you have bro. Lets get richer',
  '2022-02-05 10:30:00',
  DEFAULT,
  15
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'Please, share them I beg you',
  '2022-02-06 11:00:00',
  DEFAULT,
  16
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Easy, you got this. Profit is coming',
  '2022-02-06 12:30:00',
  DEFAULT,
  16
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Share them when they are ready',
  '2022-02-07 12:30:00',
  DEFAULT,
  17
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'I wanna see the process',
  '2022-02-08 09:15:00',
  DEFAULT,
  17
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'The interior design concept is fascinating! What inspired your creative choices?',
  '2022-02-09 17:00:00',
  DEFAULT,
  18
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Im redecorating my space. Any tips for creating a cozy home environment?',
  '2022-02-10 10:30:00',
  DEFAULT,
  18
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'The news update is eye-opening! How can individuals contribute to global awareness?',
  '2022-02-11 11:00:00',
  DEFAULT,
  19
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Im passionate about current affairs too! What inspired you to pursue journalism?',
  '2022-02-11 14:45:00',
  DEFAULT,
  19
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'This post provides valuable insights! How did you come across this information?',
  '2022-02-14 18:30:00',
  DEFAULT,
  21
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'Im interested in global trends. Which trend do you find most impactful currently?',
  '2022-02-15 16:00:00',
  DEFAULT,
  20
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'Environmental initiatives are crucial! How can we contribute to sustainability?',
  '2022-02-16 12:20:00',
  DEFAULT,
  21
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Nature photography sounds like a great hobby! Do you share your photos online?',
  '2022-02-17 09:45:00',
  DEFAULT,
  22
);

INSERT INTO comments VALUES (
  DEFAULT,
  1,
  'This post is insightful! Have you considered writing more on this topic?',
  '2022-02-18 17:10:00',
  DEFAULT,
  23
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'I completely agree with your perspective. Lets continue this conversation!',
  '2022-02-19 15:15:00',
  DEFAULT,
  23
);

INSERT INTO comments VALUES (
  DEFAULT,
  2,
  'Congratulations on your achievement!',
  '2023-12-20 09:15:00',
  DEFAULT,
  24
);

INSERT INTO comments VALUES (
  DEFAULT,
  3,
  'Im waiting...',
  '2023-12-20 10:45:00',
  DEFAULT,
  24
);



INSERT INTO messages VALUES (
  DEFAULT, 1, 2, 'yoo have you heard the news?', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 3, 'Blah2', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 2, 1, 'Really?', DEFAULT, DEFAULT
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

INSERT INTO messages VALUES (
  DEFAULT, 4, 3, 'Hey', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 7, 'Yooo', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 5, 2, 'ready ready', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 2, 'the project deadline was extended!!!', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 3, 1, 'Blah5', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 5, 1, 'Blah6', DEFAULT, DEFAULT
);


INSERT INTO messages VALUES (
  DEFAULT, 1, 3, 'Blah2', DEFAULT, DEFAULT
);


INSERT INTO messages VALUES (
  DEFAULT, 2, 3, 'Blah4', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 7, 1, 'Blah5', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 7, 'Blah6', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 7, 5, 'Blah1', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 9, 'Blah2', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 10, 1, 'Blah3', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 3, 4, 'Blah4', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 2, 7, 'Blah5', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 1, 6, 'Blah6', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 7, 4, 'Blah1', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 3, 4, 'Blah3', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 5, 1, 'Blah4', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 8, 1, 'Blah5', DEFAULT, DEFAULT
);

INSERT INTO messages VALUES (
  DEFAULT, 9, 2, 'Blah6', DEFAULT, DEFAULT
);
INSERT INTO groups VALUES (
    DEFAULT,
    1, 
    'Loucos', 
    'os mais loucos do leic'
);

INSERT INTO groups VALUES (
    DEFAULT,
    2, 
    'Os Cincum', 
    'Reis da feup'
);

INSERT INTO groups VALUES (
    DEFAULT,
    3, 
    'ESAG', 
    'antigos estudantes do liceu'
);

INSERT INTO groups VALUES (
    DEFAULT,
    1, 
    'F1', 
    'Bora discutir f1'
);

INSERT INTO moderators VALUES (
  2
);


INSERT INTO report_users (user_reported_id, user_who_repoted_id)
VALUES (1, 2);

INSERT INTO report_posts (post_id, user_id)
VALUES (1, 3);


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

INSERT INTO memberships VALUES (
    1,
    2,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    2
);

INSERT INTO memberships VALUES (
    2,
    2,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    2
);

INSERT INTO memberships VALUES (
    5,
    2,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    2
);

INSERT INTO memberships VALUES (
    3,
    3,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    3
);

INSERT INTO memberships VALUES (
    1,
    3,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    3
);

INSERT INTO memberships VALUES (
    6,
    3,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    3
);

INSERT INTO memberships VALUES (
    8,
    3,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    3
);

INSERT INTO memberships VALUES (
    1,
    4,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO memberships VALUES (
    2,
    4,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO memberships VALUES (
    3,
    4,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO memberships VALUES (
    7,
    4,
    DEFAULT,
    DEFAULT,
    '2023-12-07 11:00:23',
    '2023-12-07 11:00:23',
    1
);

INSERT INTO group_messages VALUES (
  DEFAULT, 1, 1, 'how you doing guys?', DEFAULT, DEFAULT
);

INSERT INTO group_messages VALUES (
  DEFAULT, 1, 2, 'everything alright and you?', DEFAULT, DEFAULT
);

INSERT INTO group_messages VALUES (
  DEFAULT, 1, 3, 'yo yo yo', DEFAULT, DEFAULT
);

INSERT INTO friend_requests (sender, receiver, accepted, request_date)
VALUES (2, 1, FALSE, NOW());


INSERT INTO bans (id,moderator,user_id) 
VALUES
(
  DEFAULT,
  2,
  3
);


INSERT INTO friends (user_id1, user_id2)
VALUES (1, 3);
