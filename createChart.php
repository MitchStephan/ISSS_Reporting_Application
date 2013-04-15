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
 	
 	//functions 
 	/** selects the proper joins for the user's query */
 	function makeJoins($gender, $region, $country, $program, $college){
 		$q = "";
 		
 		if ($gender != 'All' && $gender != '0') {
			$q = $q." ,student";
		}
		
		if (($region != 'All' || $country != 'All') && $gender == '0') {
			$q = $q." ,country";
		} else if (($region != 'All' || $country != 'All') && $gender == 'All') {
			$q = $q." ,student, country";
		} else if (($region != 'All' || $country != 'All') && $gender != 'All') {
			$q = $q." ,country";
		}
		
		if ($program != 'All') {
			$q = $q." ,programs";
		}
		
		if ($college != 'All') {
			$q = $q." ,academic_info";
		}
		
		return $q;
 	
 	}
 	
 	/** selects the proper where conditions for the user's query */
 	function makeQuery($level, $gender, $region, $country, $program, $college){
 		$q = "";
 		$and = false;
 		
 		//input where conditions
 		if ($level != 'All') {
			$q = $q." academic_level='".$level."'";
			$and = true;
		} 
		
		if ($gender != 'All' && !$and) {
			$q = $q." gender='".$gender."' and semester.ut_eid=student.ut_eid";
			$and = true;
		} else if ($gender != 'All') {
			$q = $q." and gender='".$gender."' and semester.ut_eid=student.ut_eid";
		}
		
		if ($region != 'All' && !$and) {
			$q = $q." Region_of_citizenship='".$region."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
			$and = true;
		} else if ($region != 'All') {
			$q = $q." and Region_of_citizenship='".$region."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
		}
		
		if ($country != 'All' && !$and) {
			$q = $q." country.country_name='".$country."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
			$and = true;
		} else if ($country != 'All') {
			$q = $q." and country.country_name='".$country."' and country.country_code=student.country_code and semester.ut_eid=student.ut_eid";
		}
		
		if ($program != 'All' && !$and) {
			$q = $q." programs.program_code='".$program."' and semester.program_code=programs.program_code";
			$and = true;
		} else if ($program != 'All') {
			$q = $q." and programs.program_code='".$program."' and semester.program_code=programs.program_code";
		}
		
		if ($college != 'All' && !$and) {
			$q = $q." academic_info.school_name='".$college."' and semester.major_code=academic_info.major_code";
			$and = true;
		} else if ($college != 'All') {
			$q = $q." and academic_info.school_name='".$college."' and semester.major_code=academic_info.major_code";
		}
		
		return $q;
 	}
 	
 	
 	//begin creating response
 	$response = '<script language="javascript"> function graph() {';
 	
 	//student distribution by academic_level
 	if ($report == '1'){
		//build querry
		$query = "select academic_level, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery('All', $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' group by academic_level;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' group by academic_level;"; 
		}
		
		//echo ($query."   ");
		
		$val1 = $val2 = 0;
		$colName1 = $colName2 = "";
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$val1 = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		$colName1 = ($queryResult['academic_level'] == 'UG'? 'Undergraduate' : 'Graduate');
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$val2 = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		$colName2 = ($colName1 == 'Graduate'? 'Undergraduate' : 'Graduate');
		
		$total = $val1 + $val2;
		
		//create chart
		$response = $response."Highcharts.setOptions({ colors:".$colors." });"
		."$('#graphContainer').highcharts({ chart: { type: 'column' }, credits: { position: { align: 'right', verticalAlign: 'bottom'},"
		."text: 'Total Students:".$total."', href: '#', style: { cursor: 'cursor', color: '#3E576F', fontSize: '15px'} },"
		."title: { text: '".$reportName."' }, xAxis: { categories: ['Academic Level'] }, yAxis: { title: { text: 'Number of Students'} },"
		."series: [{name: '".$colName1."', data: [".$val1."]}, { name: '".$colName2."', data: [".$val2."] }] });";
  	}
  	
  	//student distribution by classification
  	else if ($report == '2'){
  		//obtain freshman
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Freshman' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Freshman' group by classification;"; 
		}
		
		//echo ($query."      ");
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$freshmanNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain sophomores
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Sophomore' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Sophomore' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$sophomoreNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain juniors
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Junior' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Junior' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$juniorNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain seniors
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Senior' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Senior' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$seniorNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain Masters
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Masters' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Masters' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$mastersNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain Doctoral
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Doctoral' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Doctoral' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$doctoralNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain Law
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='Law' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='Law' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$lawNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		
		//obtain PharmD
		$query = "select classification as class, count(*) as count from semester";
		//select joins
		$query = $query.makeJoins($gender, $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, $gender, $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and classification='PharmD' group by classification;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and classification='PharmD' group by classification;"; 
		}
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$pharmNum = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);

		$total = $freshmanNum + $sophomoreNum + $juniorNum + $seniorNum + $mastersNum + $doctoralNum + $lawNum + $pharmNum;
		
		//create chart
		$response = $response."Highcharts.setOptions({ colors:".$colors." });"
		."$('#graphContainer').highcharts({ chart: { type: 'column' }, credits: { position: { align: 'right', verticalAlign: 'bottom'},"
		."text: 'Total Students:".$total."', href: '#', style: { cursor: 'cursor', color: '#3E576F', fontSize: '15px'} }," 
		."title: { text: '".$reportName."' },"
		."xAxis: { categories: ['Classification'] }, yAxis: { title: { text: 'Number of Students'} },"
		."series: [{name: 'Freshman', data: [".$freshmanNum."]}, { name: 'Sophomore', data: [".$sophomoreNum."] },"
		." { name: 'Junior', data: [".$juniorNum."] }, { name: 'Senior', data: [".$seniorNum."] },"
		." { name: 'Masters', data: [".$mastersNum."] }, { name: 'Doctoral', data: [".$doctoralNum."] }, { name: 'Law', data: [".$lawNum."] },"
		."{ name: 'PharmD', data: [".$pharmNum."] } ]});";
  		
  	}
  	
  	//student distribution by gender
 	if ($report == '3'){
		//build querry
		$query = "select student.gender as gender, count(*) as count from semester, student";
		//select joins
		$query = $query.makeJoins('0', $region, $country, $program, $college);
		$query = $query." where";
		//input where conditions
		$query = $query.makeQuery($level, 'All', $region, $country, $program, $college);
		//finish query
		$pos = strpos($query, '=');
		if ($pos === false){
		$query = $query." year=".$year." and semester='Fall' and semester.ut_eid=student.ut_eid group by student.gender;"; 
		} else {
			$query = $query." and year=".$year." and semester='Fall' and semester.ut_eid=student.ut_eid group by student.gender;"; 
		}
		
		//echo ($query."   ");
		
		$val1 = $val2 = 0;
		$colName1 = $colName2 = "";
		
		//execute query and store results
		$stmt = $db_server->query($query);
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$val1 = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		$colName1 = ($queryResult['gender'] == 'm'? 'Male' : 'Female');
		$queryResult = $stmt->fetch_array(MYSQLI_ASSOC);
		$val2 = ($queryResult['count'] > 0 ? $queryResult['count'] : 0);
		$colName2 = ($colName1 == 'Male'? 'Female' : 'Male');
		
		$total = $val1 + $val2;
		
		//$val1 = ((double)$val1)/$total * 100;
		//$val2 = ((double)$val2)/$total * 100;
		
		//create chart
		$response = $response."Highcharts.setOptions({ colors:".$colors." });"
		."$('#graphContainer').highcharts({
        chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".$reportName."'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Percent of Students',
                data: [
                    ['".$colName1."', ".$val1."],
                    {
                        name: '".$colName2."',
                        y: ".$val2.",
                        sliced: true,
                        selected: true
                    },
                ]
            }], credits: { position: { align: 'right', verticalAlign: 'bottom'},"
		."text: 'Total Students:".$total."', href: '#', style: { cursor: 'cursor', color: '#3E576F', fontSize: '15px'} }
        });";
  	}
 
 	$response = $response.'} </script>';
	
	echo ($response);
?>