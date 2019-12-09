update zipcodes
set fullstate  	= proper_case(fullstate),
	city 		= proper_case(city),
   	county 		= proper_case(county);
    
select * from airports;

update airports
set apt_name = proper_case(apt_name),
	city = proper_case(city);