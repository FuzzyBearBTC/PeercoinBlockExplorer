<?php

	require_once ("src/PPC_daemon.php");
	require_once ("src/PPC_layout.php");
	require_once ("src/stats_controller.php");
	
	
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

		$block_height = $_REQUEST["block_height"];

		if(empty ($block_height))
		{
			$network_info = getinfo ();
			// Default to the latest block
			$block_height = intval($network_info["blocks"]);
		}
		
		block_detail ($block_height);
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
		
		
		$network_info = getinfo ();
		$difficulty_info = getdifficulty ();



/*		$net_speed = getnetworkhashps ();
		if ($net_speed != "") {
			echo "		<div class=\"node_detail\">\n";
			echo "			<span class=\"node_desc\">Network H/s:</span><br>\n";
			echo "			".$net_speed."\n";
			echo "		</div>\n";
			echo "\n";
		}*/
	
		echo "\n";

		echo "	<div id=\"site_menu\">\n";
		echo "\n";

		echo "		<p class=\"center\"><center>Explore the Peercoin blockchain by looking for a Block Number (Index), Block Hash, or Transaction ID.</center></p>";
		echo "		<br />";
		
		echo "		<div class=\"menu_item\">\n";
		echo "			<form action=\"\" method=\"post\">\n";
		echo "				<label for=\"block_height\" class=\"menu_desc\">Enter a Block Number</label><br>\n";
		echo "				<input type=\"text\" name=\"block_height\" id=\"block_height\" size=\"130px\">\n";
		echo "				<input type=\"submit\" name=\"submit\" value=\"Jump To Block\">\n";
		echo "			</form>\n";
		echo "		</div>\n";
		echo "\n";

		echo "		<div class=\"menu_item\">\n";
		echo "			<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n";
		echo "				<label for=\"block_hash\" class=\"menu_desc\">Enter a Block Hash</label><br>\n";
		echo "				<input type=\"text\" name=\"block_hash\" id=\"block_hash\" size=\"130px\">\n";
		echo "				<input type=\"submit\" name=\"submit\" value=\"Jump To Block\">\n";
		echo "			</form>\n";
		echo "		</div>\n";
		echo "\n";

		echo "		<div class=\"menu_item\">\n";
		echo "			<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">\n";
		echo "				<label for=\"transaction\" class=\"menu_desc\">Enter a Transaction ID</label><br>\n";
		echo "				<input type=\"text\" name=\"transaction\" id=\"transaction\" size=\"130px\">\n";
		echo "				<input type=\"submit\" name=\"submit\" value=\"Jump To TX\">\n";
		echo "			</form>\n";
		echo "		<div class=\"menu_item\">\n";
		echo "			<span class=\"menu_desc\"><center>Find out more on Peercoin (PPC)</center></span><br>\n";
		echo "<a href=\"http://peercoin.net\" target=\"_blank\"><center>Visit Peercoin.net Official Peercoin Website</center></a> 		</div>\n";
		echo "	<a href=\"http://www.peercointalk.org\" target=\"_blank\"><center>Official Peercoin Forum</center></a> 	</div>\n";
		echo "\n";

		echo "	</div>\n";
		echo "\n";
		echo "	</div>\n";
	site_stats();
	
	echo "<body style=\"vertical-align:middle\">";
	echo "<table style=\"display:inline-block\" align = \"center\" >";
	
	// Total Coins
	
	echo "<tr>"	;
	echo "	<td>Total Coins:</td>\n";
	$totalcoins = intval($network_info["moneysupply"]);
	$totalcoins = number_format($totalcoins, 0 , '.' , ',');
	echo "	<td>" . $totalcoins . " Peercoins</td>\n";
	echo "</tr>"	;
	
	// Price
	
	echo "<tr>"	;
	echo "	<td>Price:</td>\n";
	echo "	<td><div id=\"ticker\">Loading...</div></td>\n"; 
	echo "</tr>"	;
	
	// Market Capitalization
	
	echo "<tr>"	;
	echo "	<td>Market Capitalization:</td>\n";
	echo "	<td><div id=\"marketcap\">Loading...</div></td>\n"; 
	echo "</tr>"	;
	
	// PoS Difficulty
	
	echo "<tr>"	;
	echo "	<td>PoS Difficulty:</td>\n";
	echo "	<td>". $difficulty_info["proof-of-stake"] ."</td>\n"; 
	echo "</tr>"	;
	
	// PoW Difficulty
	
	echo "<tr>"	;
	echo "	<td>PoW Difficulty:</td>\n";
	echo "	<td>". $difficulty_info["proof-of-work"] ."</td>\n"; 
	echo "</tr>"	;
	
	//Minted Reward last 1h/24h
	
	$hours = 1;
	list ($POS1, $POW1, $POScoins1, $POWcoins1, $avgPOScoins1, $avgPOWcoins1) = get_num_pos($hours);
	list ($POS24, $POW24, $POScoins24, $POWcoins24, $avgPOScoins24, $avgPOWcoins24) = get_num_pos($hours * 24);
	
	echo "<tr>";
	echo "	<td>PoS Minting Reward (last 1h/24h):</td>\n";
	echo "	<td>" . $POScoins1 . "/" . $POScoins24 . "</td>\n";
	echo "</tr>\n";
	
	//Average Minted Reward last 1h/24h
	
	echo "<tr>";
	echo "	<td>Average PoS Minting Reward (last 1h/24h):</td>\n";
	echo "	<td>" . round($avgPOScoins1, 2) . "/" . round($avgPOScoins24, 2) . "</td>\n";
	echo "</tr>\n";	
	
	//Mined Reward last 1h/24h
	
	echo "<tr>";
	echo "	<td>PoW Mining Reward (last 1h/24h):</td>\n";
	echo "	<td>" . $POWcoins1  . "/" . $POWcoins24 . "</td>\n";
	echo "</tr>\n";
	
	//Average Mined Reward last 1h/24h
	
	echo "<tr>";
	echo "	<td>Average PoW Mining Reward (last 1h/24h):</td>\n";
	echo "	<td>" . round($avgPOWcoins1, 2)  . "/" . round($avgPOWcoins24, 2) . "</td>\n";
	echo "</tr>\n";	
	
	// Market Cap
	
	// Total Blocks
	
	echo "<tr>"	;
	echo "	<td>Total Blocks:</td>\n";
	$totalblocks = intval($network_info["blocks"]);
	echo "	<td>". number_format($totalblocks, 0 , '.' , ',') ." Blocks </td>\n";
	echo "</tr>"	;
	
	// POS Blocks
	
	echo "<tr>";
	echo "	<td>PoS Blocks (last 1h/24h):</td>\n";
	
	echo "	<td>" . $POS1 . "/" . $POS24 . "</td>\n";
	echo "</tr>\n";
	
	// POW Blocks
	
	echo "<tr>";
	echo "	<td>PoW Blocks (last 1h/24h):</td>\n";
	echo "	<td>" . $POW1  . "/" . $POW24 . "</td>\n";
	echo "</tr>\n";
	
	// POS:POW Ratio
	
	$ratio1 = ratio($POS1, $POW1); 
	$ratio24 = ratio($POS24, $POW24);
	echo "<tr>"	;
	echo "	<td>PoS:PoW Ratio 1h/24:</td>\n";
	echo "	<td>" . $ratio1 . "/" . $ratio24 . "  </td>\n";
	echo "</tr>"	;
	
	//Connections
	
	echo "<tr>"	;
	echo "	<td>Connections:</td>\n";
	echo "	<td>". $network_info["connections"] ."</td>\n"; //I couldn't make out how you ended up outputting the price mark-bl.
	echo "</tr>"	;
	
	echo "</table>\n";
	
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
