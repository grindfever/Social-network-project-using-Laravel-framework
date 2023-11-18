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

DROP TABLE IF EXISTS messages CASCADE;
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


--
-- Insert value.
--

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  DEFAULT,
  'Diogo',
  'diogo@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  DEFAULT,
  'a',
  'a@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')



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

INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');
