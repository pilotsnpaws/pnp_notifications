-- this is for AWS mysql
create table pnp_trip_notif_status
	(id 		INT NOT NULL AUTO_INCREMENT,
    topic_id 	MEDIUMINT(8),
    user_id 	MEDIUMINT(8),
    notify_status TINYINT(1),
	  status_code VARCHAR(10),
    created_ts				TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	  source_server			VARCHAR(100),
    source_database			VARCHAR(64),
    PRIMARY KEY (id)
    ) ENGINE=InnoDB ;

create UNIQUE INDEX idx_topic_user ON pnp_trip_notif_status (topic_id, user_id);
-- drop table pnp_topics
create table pnp_topics
	(id						INT NOT NULL AUTO_INCREMENT,
    topic_id 				INT, 
    forum_id 				INT, 
    topic_title 			VARCHAR(255), 
	  topic_time_ts	TIMESTAMP,
    topic_first_poster_name VARCHAR(255),
    pnp_sendZip 			VARCHAR(5), 
    pnp_recZip 				VARCHAR(5),
	send_lat 				decimal(12,6) NOT NULL,
	send_lon 				decimal(12,6) NOT NULL,
    send_location_point		point, 
    rec_lat 				decimal(12,6) NOT NULL,
	rec_lon 				decimal(12,6) NOT NULL,
    rec_location_point		point, 
    topic_linestring		linestring,
    created_ts				TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_server			VARCHAR(100),
    source_database			VARCHAR(64),
    PRIMARY KEY (id)
    ) ENGINE=InnoDB ; 

create unique index idx_topics on pnp_topics (source_server, source_database, forum_id, topic_id);

-- drop table pnp_users
CREATE TABLE pnp_users (
  id int(11) NOT NULL AUTO_INCREMENT,
  last_visit timestamp NOT NULL,
  user_id mediumint(9) NOT NULL,
  user_email varchar(255) NOT NULL,
  user_regdate int(11) NOT NULL,
  username varchar(255) NOT NULL,
  pf_flying_radius mediumint(9) DEFAULT NULL,
  pf_foster_yn varchar(1) DEFAULT NULL,
  pf_pilot_yn varchar(1) NOT NULL DEFAULT '0',
  apt_id varchar(4) NOT NULL DEFAULT '0000',
  apt_name varchar(100) DEFAULT NULL,
  zip varchar(5) NOT NULL DEFAULT '00000',
  lat decimal(12,6) NOT NULL,
  lon decimal(12,6) NOT NULL,
  location_point point DEFAULT NULL,
  city varchar(40) DEFAULT NULL,
  state varchar(2) DEFAULT NULL,
  created_ts timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_ts timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_source_ts timestamp NOT NULL,
  source_server varchar(100) DEFAULT NULL,
  source_database varchar(64) DEFAULT NULL,
  user_inactive_reason tinyint(4) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY idx_unique_env (user_id,source_server,source_database),
  KEY idx_insert_helper (source_server,source_database,user_id)
) ENGINE=InnoDB ;

CREATE INDEX idx_user_update_helper ON pnp_users (source_server ASC, source_database ASC, user_id ASC);




insert into pnp_users (last_visit, user_id, user_email, user_regdate, username, lat, lon, location_point, source_database )
VALUES
('2016-01-01 12:00:00', 1, 'forum+test@pilotsnpaws.org', 1245981769, 'bootstrap', 
29.27, -80.65, ST_GeomFromText('POINT(-80.65 29.27)'), 'xpilotsnpaws-forum'
);

insert into pnp_users (last_visit, user_id, user_email, user_regdate, username, lat, lon, location_point, source_database )
VALUES
('2016-01-01 12:00:00', 1, 'forum+test@pilotsnpaws.org', 1245981769, 'bootstrap', 
29.27, -80.65, ST_GeomFromText('POINT(-80.65 29.27)'), 'pnp_forum_0607'
);

select * from pnp_users order by updated_source_ts desc

select * from pnp_users where user_id = 1207

delete from pnp_users where user_id = 150

delete from pnp_users where source_database = 'pnp_forum_0607'


INSERT INTO pnp_topics
(topic_id,
forum_id,
topic_title,
topic_first_poster_name,
pnp_sendZip,
pnp_recZip,
sendLat, sendLon,
recLat, recLon,
source_server,
source_database)
VALUES
(1,5,'First topic','mike','32507','45409','bootstrap','pnp_forum_0607');
 
SELECT max(topic_id) as max_topic_id FROM pnp_topics WHERE forum_id = 5 and source_database = 'pnp_forum_0607' HAVING max_topic_id IS NOT NULL

SELECT * FROM phpbb_topics LIMIT 5

select * from pnp_trip_notif_status
    
select * from pnp_topics where source_database = 'pnp_forum_0607'

delete from pnp_topics where id > 1 and source_database = 'pnp_forum_0607'