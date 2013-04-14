<?php
	$db_hostname = 'z';
	$db_username = 'bveltman';
	$db_database = 'cs105_s13_bveltman';
	$db_password = 'kKOcaj59il';
	//mysql -h z -p -u bveltman
	//USE cs105_s13_bveltman;
	
	/**
	Below is data to populate the sample database
	
	INSERT INTO student(ut_eid, last_name, first_name, gender, marital_status, first_semester_enrolled, last_semester_enrolled, country_code)
	VALUES('abc123', 'Abc', 'Cba', 'm', 's', '1', '5', '1');
	INSERT INTO student(ut_eid, last_name, first_name, gender, marital_status, first_semester_enrolled, last_semester_enrolled, country_code)
	VALUES('bcd234', 'Bcd', 'Dcb', 'm', 'm', '1', '5', '2');
	INSERT INTO student(ut_eid, last_name, first_name, gender, marital_status, first_semester_enrolled, last_semester_enrolled, country_code)
	VALUES('cde345', 'Cde', 'Edc', 'f', 's', '2', '6', '1');
	INSERT INTO student(ut_eid, last_name, first_name, gender, marital_status, first_semester_enrolled, last_semester_enrolled, country_code)
	VALUES('def456', 'Def', 'Fed', 'f', 's', '3', '7', '3');
	INSERT INTO student(ut_eid, last_name, first_name, gender, marital_status, first_semester_enrolled, last_semester_enrolled, country_code)
	VALUES('efg567', 'Efg', 'Gfe', 'm', 'm', '4', '8', '4');
	INSERT INTO student(ut_eid, last_name, first_name, gender, marital_status, first_semester_enrolled, last_semester_enrolled, country_code)
	VALUES('hij890', 'Efg', 'Gfe', 'f', 'm', '4', '8', '4');

	INSERT INTO country(country_code, country_name, region_of_citizenship, region_code)
	VALUES('1', 'China', 'East Asia', '1');
	INSERT INTO country(country_code, country_name, region_of_citizenship, region_code)
	VALUES('2', 'Mexico', 'North America', '2');
	INSERT INTO country(country_code, country_name, region_of_citizenship, region_code)
	VALUES('3', 'South Korea', 'East Asia', '1');
	INSERT INTO country(country_code, country_name, region_of_citizenship, region_code)
	VALUES('4', 'Poland', 'Western Europe', '3');

	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code)
	VALUES('Fall',2012,'abc123','UG','sophomore','1','1','1','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code)
	VALUES('Spring',2013,'abc123','UG','sophomore','1','1','1','3');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code)
	VALUES('Fall',2013,'abc123','UG','sophomore','1','1','1','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Fall',2012,'bcd234','UG','freshman','2','2','1','2','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Spring',2013,'bcd234','UG','sophomore','2','2','1','2','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Fall',2013,'bcd234','UG','sophomore','2','2','1','2','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code)
	VALUES('Spring',2012,'cde345','UG','freshman','3','1','1','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code)
	VALUES('Fall',2012,'cde345','UG','freshman','3','1','2','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Fall',2011,'def456','UG','junior','4','3','1','3','2');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Spring',2012,'def456','UG','junior','4','3','2','3','1');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Fall',2010,'efg567','G','senior','5','1','1','1','2');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Spring',2010,'efg567','G','senior','5','1','1','1','2');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Spring',2013,'hij890','G','senior','5','1','1','1','2');
	INSERT INTO semester (semester, year, ut_eid, academic_level, classification, passport,  program_code, visa_status, major_code, major_code2)
	VALUES('Fall',2013,'hij890','G','senior','5','1','1','1','2');

	INSERT INTO academic_info (major_code, major_description, school_code, school_name)
	VALUES('1','Computer Science','1','College of Natural Sciences');
	INSERT INTO academic_info (major_code, major_description, school_code, school_name)
	VALUES('2','Mathematics','1','College of Natural Sciences');
	INSERT INTO academic_info (major_code, major_description, school_code, school_name)
	VALUES('3','Economics','2','College of Liberal Arts');

	INSERT INTO programs (program_code, program_description)
	VALUES('1','sponsored');
	INSERT INTO programs (program_code, program_description)
	VALUES('2','exchange');
	INSERT INTO programs (program_code, program_description)
	VALUES('3','both');

	INSERT INTO immigration_info (visa_status_code, visa_status_description)
	VALUES('1','okay');
	INSERT INTO immigration_info (visa_status_code, visa_status_description)
	VALUES('2','warning');
	
	*/

?>