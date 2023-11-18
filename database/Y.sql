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
DROP TABLE if exists users;
CREATE TABLE users
(
    id SERIAL PRIMARY KEY,
    username character varying(16),
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
    id Serial PRIMARY KEY CONSTRAINT fk_moderator_username REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE 
);

--ADMIN
DROP TABLE if exists admin;
CREATE TABLE admin
(
    id INTEGER PRIMARY KEY CONSTRAINT fk_admin_username REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

--POST
DROP TABLE if exists post;
CREATE TABLE post
(
    id SERIAL PRIMARY KEY,
    user_id INTEGER CONSTRAINT fk_post_userid REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_post_userid NOT NULL,
    content character varying(512) ,
    date timestamp(0) without time zone CONSTRAINT nn_post_date NOT NULL DEFAULT now(),
    img character varying(256),
    CHECK (content IS NOT NULL OR img IS NOT NULL)
);

--MESSAGE
DROP TABLE if exists message;
CREATE TABLE message
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
DROP TABLE if exists comment;
CREATE TABLE comment
(
    id SERIAL PRIMARY KEY,
    user_id INTEGER CONSTRAINT fk_comment_userid REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				   CONSTRAINT nn_commente_userid NOT NULL,
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
    sender INTEGER CONSTRAINT fk_friend_request_sender REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE ,
    receiver INTEGER CONSTRAINT fk_friend_request_receiver REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    accepted boolean CONSTRAINT nn_friend_request_accepted NOT NULL DEFAULT FALSE,
    request_date timestamp(0) without time zone CONSTRAINT nn_friend_request_request_date NOT NULL,
    accept_date timestamp(0) without time zone,
    PRIMARY KEY (sender, receiver)

);

--GROUPS
DROP TABLE if exists groups;
CREATE TABLE groups
(
    id SERIAL PRIMARY KEY,
    owner INTEGER CONSTRAINT fk_groups_owner REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
				CONSTRAINT nn_groups_owner NOT NULL,
    name character varying(24)  CONSTRAINT nn_groups_name NOT NULL,
    description character varying(256)
);

--NOTIFICATION
DROP TABLE if exists notification;
CREATE TABLE notification
(
    id SERIAL PRIMARY KEY,
    receiver INTEGER CONSTRAINT fk_notification_receiver REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
			           CONSTRAINT nn_notification_receiver NOT NULL,
    content character varying(128) CONSTRAINT nn_notification_content NOT NULL,
    viewed boolean CONSTRAINT nn_notification_viewed NOT NULL DEFAULT FALSE,
    date timestamp(0) without time zone CONSTRAINT nn_notification_date NOT NULL,
    notification_type notification_type_enum CONSTRAINT nn_post_notification_notification_type NOT NULL,
    id_post INTEGER REFERENCES post(id) DEFAULT NULL,
    id_message INTEGER REFERENCES message(id) DEFAULT NULL,
    id_comment INTEGER REFERENCES post(id) DEFAULT NULL,
    id_group INTEGER REFERENCES post(id) DEFAULT NULL
);

--BAN
DROP TABLE if exists ban;
CREATE TABLE ban
(
    id SERIAL PRIMARY KEY,
    moderator INTEGER CONSTRAINT fk_ban_moderator REFERENCES moderator(id) ON DELETE CASCADE ON UPDATE CASCADE
				    CONSTRAINT nn_ban_moderator NOT NULL,
    user_id INTEGER CONSTRAINT fk_ban_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE NOT NULL,
    ban_time integer CONSTRAINT nn_ban_ban_time NOT NULL DEFAULT 0,
    forever boolean CONSTRAINT nn_ban_forever NOT NULL DEFAULT FALSE,
    date timestamp(0) without time zone CONSTRAINT nn_ban_date NOT NULL
);

--USER TAGGED POST
DROP TABLE if exists user_tagged_post;
CREATE TABLE user_tagged_post
(
    user_id INTEGER CONSTRAINT fk_user_tagged_post_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id INTEGER CONSTRAINT fk_user_tagged_post_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, id)    
);

--USER LIKES POST
DROP TABLE if exists user_likes_post;
CREATE TABLE user_likes_post
(
    user_id INTEGER CONSTRAINT fk_user_likes_post_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_likes_post_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, id)    
);

--USER LIKES COMMENT
DROP TABLE if exists user_likes_comment;
CREATE TABLE user_likes_comment
(
    
    user_id INTEGER CONSTRAINT fk_user_likes_comment_user REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    id integer CONSTRAINT fk_user_likes_comment_id REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, id)    
);

--POST REMOVAL
DROP TABLE if exists post_removal;
CREATE TABLE post_removal
(
    id SERIAL PRIMARY KEY,
    moderator INTEGER CONSTRAINT fk_post_removal_moderator REFERENCES moderator(id) ON DELETE CASCADE ON UPDATE CASCADE
				    CONSTRAINT nn_post_removal_moderator NOT NULL,
    reason character varying(128) CONSTRAINT nn_post_removal_reason NOT NULL,
    date timestamp(0) without time zone CONSTRAINT nn_post_removal_date NOT NULL

);

--MEMBERSHIP
DROP TABLE if exists membership; 
CREATE TABLE membership
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
