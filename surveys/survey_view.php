<?php //Survey_view.php

	require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
	$config->metaRobots = 'no index, no follow';#never index survey pages

	# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
	if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
		 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
	}else{
		myRedirect(VIRTUAL_PATH . "surveys/survey_list.php");
	}

	$mySurvey = new Survey($myID);
	if($mySurvey->isValid)
	{
		$config->titleTag = "'" . $mySurvey->Title . "' Survey!";
	}else{
		$config->titleTag = smartTitle(); //use constant
	}
	#END CONFIG AREA ----------------------------------------------------------

	get_header(); #defaults to theme header or header_inc.php
	?>

	<link rel="stylesheet" href="myTable.css">

	<h3>7a: <span style="text-transform:uppercase;"><?=THIS_PAGE;?></span></h3>

	<?php

	if($mySurvey->isValid)
	{ #check to see if we have a valid SurveyID
		echo "<b>#" . $mySurvey->SurveyID . ":</b>";
		echo $mySurvey->Title . "<br />";
		echo $mySurvey->Description . "<br /><br />";
		$mySurvey->showQuestions();
		
		echo responseList($myID);
	}else{
		echo '<a href="surveys/survey_list.php">Sorry, no such survey!</a>';
	}

	echo '<p align="center"><a href="survey_list.php">Back to Surveys</a></p>';

	get_footer(); #defaults to theme footer or footer_inc.php

	#banshee, Colossus, Cyclops, Havok, Nightcrawler, Pheonix, Polaris Storm

	
	
	function responseList($myID){
		//will create a list of responses for THIS survey
		
		$sql = "select * from sp14_responses where SurveyID=$myID";
		
		
		#reference images for pager
		$prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
		$next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';
	
		# Create instance of new 'pager' class
		$myPager = new Pager(10,'',$prev,$next,''); //the 'space' is a holder for another button if needed
		$sql = $myPager->loadSQL($sql);  #load SQL, add offset
	
		# connection comes first in mysqli (improved) function
		$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	
		if(mysqli_num_rows($result) > 0)
		{#records exist - process
			if($myPager->showTotal()==1){$itemz = "response";}else{$itemz = "responses";}  //deal with plural
	
			echo '
			<table id="newspaper-a" summary="Survye list and View Object">
	
			<caption >Currently, there are <b style="color:red;">' . $myPager->showTotal() . ' ' . $itemz . '</b> available to view</caption>
			<thead>
				<tr>
					<th scope="col">Created</th>
				</tr>
			</thead>
			';
	
			//echo '<tr><td colspan="4">Currently, there are <b style="color:red;">' . $myPager->showTotal() . ' ' . $itemz . '</b> available to view</td></tr>';
			while($row = mysqli_fetch_assoc($result))
	
			{# process each row
				 echo '<tr>'; //begin table row
	
				 echo '<td class="tweak"> <a href="' . VIRTUAL_PATH . 'surveys/response_view.php?id=' . (int)$row['ResponseID'] . '">' . dbOut($row['DateAdded']) . '</a> </td>';
	
				 echo '</tr>';//end table row
			}
	
			echo '</table>';
	
			echo $myPager->showNAV(); # show paging nav, only if enough records
		}else{#no records
			echo "<div align=center>Currently, No SURVEYS available</div>";
		}
		@mysqli_free_result($result);
			
			
		
		
		
		}