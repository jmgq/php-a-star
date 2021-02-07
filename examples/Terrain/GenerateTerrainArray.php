<?php

 /**
  * Generate terrain arrays for StaticExampleTest
  * Paste results into tests like tests/Example/Terrain/StaticExampleTest
  *
  */

$rows=500;
$wid=500;

for  ($i=0; $i<$rows;$i++)
{
	echo " array ( ";

	for  ($j=0; $j<$wid;$j++)
	{
		echo rand(1,10);

		if ( $j<$wid-1)
			echo ",";
	}
	echo " ) ";

	if ( $i < $rows-1 )
		echo " , ";
	echo "\n";
}

?>
