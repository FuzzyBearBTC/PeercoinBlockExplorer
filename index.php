<?php

	require_once ("bc_daemon.php");
	require_once ("bc_layout.php");
	
	
//	If a block hash was provided the block detail is shown
	if (isset ($_REQUEST["block_hash"]))
	{
		site_header ("Block Detail Page");
		
		block_detail ($_REQUEST["block_hash"], TRUE);
	}
	
//	If a block height is provided the block detail is shown
	elseif (isset ($_REQUEST["block_height"]))
	{
		site_header ("Block Detail Page");
		
		block_detail ($_REQUEST["block_height"]);
	}
	
//	If a TXid was provided the TX Detail is shown
	elseif (isset ($_REQUEST["transaction"]))
	{
		site_header ("Transaction Detail Page");
		
		tx_detail ($_REQUEST["transaction"]);
	}
	
//	If there were no request parameters the menu is shown
	else {
		site_header("Block Viewer");
		
		echo "	<div id=\"node_info\">\n";
		echo "\n";
		
		$network_info = getinfo ();
		
		echo "		<div class=\"node_detail\">\n";
		echo "			<span class=\"node_desc\">Block Count</span><br>\n";
		echo "			<span class=\"node_desc_value\">".$network_info["blocks"]."</span>\n";
		echo "		</div>\n";
		echo "\n";

		echo "		<div class=\"node_detail\">\n";
		echo "			<span class=\"node_desc\">Difficulty</span><br>\n";
		echo "			<span class=\"node_desc_value\">".$network_info["difficulty"]."</span>\n";
		echo "		</div>\n";
		echo "\n";

		echo "		<div class=\"node_detail\">\n";
		echo "			<span class=\"node_desc\">Connections</span><br>\n";
		echo "			<span class=\"node_desc_value\">".$network_info["connections"]."</span>\n";
		echo "		</div>\n";
		echo "\n";

/*
		$net_speed = getnetworkhashps ();
		if ($net_speed != "") {
			echo "		<div class=\"node_detail\">\n";
			echo "			<span class=\"node_desc\">Network H/s:</span><br>\n";
			echo "			".$net_speed."\n";
			echo "		</div>\n";
			echo "\n";
		}
*/		
		echo "	</div>\n";
		echo "\n";

		echo "	<div id=\"site_menu\">\n";
		echo "\n";

		echo "		<p class=\"left\">Explore the Peercoin blockchain by looking for a Block Number (Index), Block Hash, or Transaction ID.</p>";
		echo "		<br />";
		
		echo "		<div class=\"menu_item\">\n";
		echo "			<form action=\"\" method=\"post\">\n";
		echo "				<label for=\"block_height\" class=\"menu_desc\">Enter a Block Number</label><br>\n";
		echo "				<input type=\"text\" name=\"block_height\" id=\"block_height\" size=\"40\">\n";
		echo "				<input type=\"submit\" name=\"submit\" value=\"Jump To Block\">\n";
		echo "			</form>\n";
		echo "		</div>\n";
		echo "\n";

		echo "		<div class=\"menu_item\">\n";
		echo "			<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n";
		echo "				<label for=\"block_hash\" class=\"menu_desc\">Enter a Block Hash</label><br>\n";
		echo "				<input type=\"text\" name=\"block_hash\" id=\"block_hash\" size=\"40\">\n";
		echo "				<input type=\"submit\" name=\"submit\" value=\"Jump To Block\">\n";
		echo "			</form>\n";
		echo "		</div>\n";
		echo "\n";

		echo "		<div class=\"menu_item\">\n";
		echo "			<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n";
		echo "				<label for=\"transaction\" class=\"menu_desc\">Enter a Transaction ID</label><br>\n";
		echo "				<input type=\"text\" name=\"transaction\" id=\"transaction\" size=\"40\">\n";
		echo "				<input type=\"submit\" name=\"submit\" value=\"Jump To TX\">\n";
		echo "			</form>\n";
		echo "		<div class=\"menu_item\">\n";
		echo "			<span class=\"menu_desc\">Find out more on Peercoin (PPC)</span><br>\n";
		echo "<a href=\"http://peercoin.net\" target=\"_blank\">Visit Peercoin.net Official Peercoin Website</a> 		</div>\n";
		echo "	<a href=\"http://www.peercointalk.org\" target=\"_blank\">Official Peercoin Forum</a> 	</div>\n";
		echo "\n";

		echo "	</div>\n";
		echo "\n";
		echo "<div id=\"credits_box\"><a href=\"http://www.peercointalk.org\" target=\"_blank\">Brought to you by FuzzyBear and PeercoinTalk.org</a></div>\n";
		echo "\n";
		echo "<a href=\"http://peercoin.net\" target=\"_blank\"><img id=\"peercoin_logo\" src=\"http://merchanttools.peercointalk.org/Logo/Logo.png\" alt=\"Peercoin Logo\" title=\"Peercoin Logo\"></img></a>";
	}
	
	
	site_footer ();

/******************************************************************************
	This script is Copyright � 2013 Jake Paysnoe.
	I hereby release this script into the public domain.
	Jake Paysnoe Jun 26, 2013
******************************************************************************/
?>
