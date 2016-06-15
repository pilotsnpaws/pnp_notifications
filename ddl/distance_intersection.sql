select DISTINCT t.topic_id, ST_distance_sphere(u.location_point, t.send_location_point) / 1609 as send_dist,
	ST_distance_sphere(u.location_point, t.rec_location_point) / 1609 as rec_dist,
	ST_distance_sphere(t.send_location_point, t.rec_location_point) / 1609 as trip_dist,
	pf_flying_radius, pf_pilot_yn,
    u.user_id, u.location_point, t.send_location_point, t.rec_location_point,
    ST_buffer(u.location_point, pf_flying_radius * 0.01455581689886) as flying_circle,
    topic_linestring,
    ST_Intersects(ST_buffer(u.location_point, pf_flying_radius * 0.01455581689886), topic_linestring) as intersects
from pnp_topics t,
	pnp_users u
where 1=1
	and t.topic_id = 39811
	/* and ( (ST_distance_sphere(u.location_point, t.send_location_point) / 1609) < u.pf_flying_radius
			OR 
		(ST_distance_sphere(u.location_point, t.rec_location_point) / 1609) < u.pf_flying_radius ) */
	and pf_flying_radius > 0 -- and pf_flying_radius < 200
    and pf_pilot_yn = 1
    and ST_Intersects(ST_buffer(u.location_point, pf_flying_radius * 0.01455581689886), topic_linestring) = 1
order by user_id

-- select * from pnp_topics where topic_id = 41217

select * from pnp_users where user_id = 19223