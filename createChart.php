<?php
	
	//establish connection to database
	require_once 'database/DBInfo.php';
	$db_server = new mysqli($db_hostname, $db_username, $db_password, $db_database);
	
	if ($db_server->connect_errno) {
			// connect_error returns the a string of the error from the latest sql command
			print ("<h1> There was an error:</h1> <p> " . $db_server->connect_error . "</p>");
	}
	
	//obtain report filters
	$reportName = $_POST['reportName'];
 	$report = $_POST['reportVal']; 
 	$year = $_POST['year']; 
 	$level = $_POST['academicLevel']; 
 	$gender = $_POST['gender']; 
 	$region = $_POST['region']; 
 	$country = $_POST['country'];
 	$program = $_POST['program']; 
 	$college = $_POST['college']; 
 	
 	$errorCount = 0;
 	$colors = "['#FF9900', '#EBB461' , '#FFCC00', '#D0D0D0', '#FF3333', '#FF6666', '#FCCCC', '#CCFF99', '#CC9966', '#CC6600', '#993333']";
 	
 	$response = '<script language="javascript"> function graph() {';
 	
 	//student distribution by academic_level
 	if ($report == '1'){
  			//build querry
  			$query = "select academic_level, count(*) as count from semester";
  			
  			//select joins
  			if ($gender != 'All') {
  				$query = $query." ,student";
  			}
  			
  			if (($region != 'All' || $country != 'All') && $gender == 'All') {
  				$query = $query." ,student, country";
  			} else if (($region != 'All' || $country != 'All') && $gender != 'All') {
  				$query = $query." ,country";
  			}
  			if ($program != 'All') {
  				$query = $query." ,programs";
  			}
  			if ($college != 'All') {
  				$query = $query." ,academic_info";
  			}
  			
  			$query = $query." where";
  			$and = false;
  			
  			//input where conditions
  			if ($gender != 'All' && !$and) {
  				$query = $query." gender='".$gender."' and semester.ut_eid=student.ut_eid";
  				$and = true;
  			} else if ($gender != 'All') {
  				$query = $query." and gender='".$gender."' and semester.ut_eid=student.ut_eid";
  			}
  			
  			if ($region != 'All' && !$and) {
  				$query = $query." Region_of_citizenship='".$region."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
  				$and = true;
  			} else if ($region != 'All') {
  				$query = $query." and Region_of_citizenship='".$region."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
  			}
  			
  	 		if ($country != 'All' && !$and) {
  				$query = $query." country.country_name='".$country."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
  				$and = true;
  			} else if ($country != 'All') {
  				$query = $query." and country.country_name='".$country."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
  			}
  			
  			if ($program != 'All' && !$and) {
  				$query = $query." programs.program_code='".$program."' and semester.program_code=programs.program_code";
  				$and = true;
  			} else if ($program != 'All') {
  				$query = $query." and programs.program_code='".$program."' and semester.program_code=programs.program_code";
  			}
  			
  			if ($college != 'All' && !$and) {
  				$query = $query." academic_info.school_name='".$college."' and semester.major_code=academic_info.major_code";
  				$and = true;
  			} else if ($college != 'All') {
  				$query = $query." and academic_info.school_name='".$college."' and semester.major_code=academic_info.major_code";
  			}
  			
  			if (!$and){
  				$query = $query." year=".$year." and semester='Fall' group by academic_level;"; 
  			} else {
  				$query = $query." and year=".$year." and semester='Fall' group by academic_level;"; 
  			}
  			
  			//echo ($query."   ");
  			
  			$val1 = $val2 = 0;
  			$colName1 = $colName2 = "";
  			
  			$stmt = $db_server->query($query);
  			$row_cnt = $stmt->num_rows;
  			$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
			$val1 = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
			$colName1 = ($queryResult['academic_level'] == 'UG'? 'Undergraduate' : 'Graduate');
			$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
			$val2 = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
			$colName2 = ($colName1 == 'Graduate'? 'Undergraduate' : 'Graduate');
			
			
			//create chart
			$response = $response."Highcharts.setOptions({ colors:".$colors." });"
			."$('#graphContainer').highcharts({ chart: { type: 'column' }, credits: { enabled: false }, title: { text: '".$reportName."' },"
			."xAxis: { categories: ['Academic Level'] }, yAxis: { title: { text: 'Number of Students'} },"
			."series: [{name: '".$colName1."', data: [".$val1."]}, { name: '".$colName2."', data: [".$val2."] }] });";
  	}
  	
  	else if ($report == '2'){
  		//build querry
  		$query = "select academic_level, count(*) as count from semester";
  	}
 	
 	$response = $response.'} </script>';
	
	echo ($response);
?>