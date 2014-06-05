<?php

	# '../' works for a sub-folder.  use './' for the root
	require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

	# SQL statement
	$sql =
	"
	select CONCAT(a.FirstName, ' ', a.LastName) AdminName, s.SurveyID, s.Title, s.Description,
	date_format(s.DateAdded, '%W %D %M %Y %H:%i') 'DateAdded' from "
	. PREFIX . "surveys s, " . PREFIX . "Admin a where s.AdminID=a.AdminID order by s.DateAdded desc
	";

	#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php
	$config->titleTag = 'Survey Title Tag';

	#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
	$config->metaDescription = 'Survey MetaDescription! ' . $config->metaDescription;
	$config->metaKeywords = 'meta, keywords, here,'. $config->metaKeywords;

	# END CONFIG AREA ----------------------------------------------------------

	get_header(); #defaults to theme header or header_inc.php
	?>
	<link rel="stylesheet" href="myTable.css">
	<br />
	<br />

	<h3 align="center">7a: <span style="text-transform:uppercase;"><?=smartTitle();?></span></h3>


	<?php
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
		if($myPager->showTotal()==1){$itemz = "survey";}else{$itemz = "surveys";}  //deal with plural

		echo '
		<table id="newspaper-a" summary="Survye list and View Object">

		<caption >Currently, there are <b style="color:red;">' . $myPager->showTotal() . ' ' . $itemz . '</b> available to view</caption>
		<thead>
			<tr>
				<th scope="col">Created</th>
				<th scope="col">Title</th>
				<th scope="col">Description</th>
				<th scope="col">Owner</th>
			</tr>
		</thead>
		';

		//echo '<tr><td colspan="4">Currently, there are <b style="color:red;">' . $myPager->showTotal() . ' ' . $itemz . '</b> available to view</td></tr>';
		while($row = mysqli_fetch_assoc($result))

		{# process each row
			 echo '<tr>'; //begin table row

			 echo '<td> ' . dbOut($row['DateAdded']) . ' </td>';
			 echo '<td class="tweak"> <a href="' . VIRTUAL_PATH . 'surveys/survey_view.php?id=' . (int)$row['SurveyID'] . '">' . dbOut($row['Title']) . '</a> </td>';
			 echo '<td> ' . dbOut($row['Description']) . ' </td>';
			 echo '<td> ' . dbOut($row['AdminName']) . ' </td>';

			 echo '</tr>';//end table row
		}

		echo '</table>';

		echo $myPager->showNAV(); # show paging nav, only if enough records
	}else{#no records
		echo "<div align=center>Currently, No SURVEYS available</div>";
	}
	@mysqli_free_result($result);

	get_footer(); #defaults to theme footer or footer_inc.php

