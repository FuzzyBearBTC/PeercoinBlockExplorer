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
?>
<div id="site_menu">
	<p class="center"></p>
	<center>Explore the Peercoin blockchain by looking for a Block Number (Index), Block Hash, or Transaction ID.</center>
	<div class="menu_item">
		<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
			<label for="block_height" class="menu_desc">Enter a Block Number</label><br>
			<input class="form-control" type="text" name="block_height" id="block_height">
			<input class="btn btn-success" type="submit" name="submit" value="Jump To Block">
		</form>
	</div>

	<div class="menu_item">
		<form action="/index.php" method="post">
			<label for="block_hash" class="menu_desc">Enter a Block Hash</label><br>
			<input class="form-control" type="text" name="block_hash" id="block_hash">
			<input class="btn btn-success" type="submit" name="submit" value="Jump To Block">
		</form>
	</div>

	<div class="menu_item">
		<form action="/index.php" method="post">
			<label for="transaction" class="menu_desc">Enter a Transaction ID</label><br>
			<input class="form-control" type="text" name="transaction" id="transaction">
			<input class="btn btn-success" type="submit" name="submit" value="Jump To TX">
		</form>
		<div class="menu_item">
			<p class="menu_desc"><center>Find out more on Peercoin (PPC)</center></p>
			<a href="http://peercoin.net" target="_blank"><center>Visit Peercoin.net Official Peercoin Website</center></a> 		</div>
			<a href="http://www.peercointalk.org" target="_blank"><center>Official Peercoin Forum</center></a> 	
		</div>
	</div>

<?php

	site_stats();
	
	// Total Coins
	$totalcoins = intval($network_info["moneysupply"]);
	$totalcoins = number_format($totalcoins, 0 , '.' , ',');

	//Minted Reward last 1h/24h
	$hours = 1;
	list ($POS1, $POW1, $POScoins1, $POWcoins1, $avgPOScoins1, $avgPOWcoins1) = get_num_pos($hours);
	list ($POS24, $POW24, $POScoins24, $POWcoins24, $avgPOScoins24, $avgPOWcoins24) = get_num_pos($hours * 24);

	// Total Blocks
	$totalblocks = intval($network_info["blocks"]);

	// POS:POW Ratio
	$ratio1 = ratio($POS1, $POW1); 
	$ratio24 = ratio($POS24, $POW24);
?>


<div class="coin-overview">
	<dl>
		<dt>Total Coins:</dt>
		<dd><?php echo $totalcoins; ?></dd>
	</dl>
	<dl>
		<dt>Price:</dt>
		<dd><span id="ticker">Loading...</span></dd>
	</dl>
	<dl>
		<dt>Market Capitalization:</dt>
		<dd><span id="marketcap">Loading...</span></dd>
	</dl>
	<dl>
		<dt>PoS Difficulty:</dt>
		<dd><?php echo $difficulty_info["proof-of-stake"]; ?></dd>
	</dl>
	<dl>
		<dt>PoW Difficulty:</dt>
		<dd><?php echo $difficulty_info["proof-of-work"]; ?></dd>
	</dl>
	<dl>
		<dt>PoS Minting Reward (last 1h/24h):</dt>
		<dd><?php echo $POScoins1 . "/" . $POScoins24; ?></dd>
	</dl>
	<dl>
		<dt>Average PoS Minting Reward (last 1h/24h):</dt>
		<dd><?php echo round($avgPOScoins1, 2) . "/" . round($avgPOScoins24, 2); ?></dd>
	</dl>
	<dl>
		<dt>PoW Mining Reward (last 1h/24h):</dt>
		<dd><?php echo $POWcoins1  . "/" . $POWcoins24; ?></dd>
	</dl>
	<dl>
		<dt>Average PoW Mining Reward (last 1h/24h):</dt>
		<dd><?php echo round($avgPOWcoins1, 2)  . "/" . round($avgPOWcoins24, 2); ?></dd>
	</dl>
	<dl>
		<dt>Total Blocks:</dt>
		<dd><?php echo number_format($totalblocks, 0 , '.' , ','); ?>Blocks</dd>
	</dl>
	<dl>
		<dt>PoS Blocks (last 1h/24h):</dt>
		<dd><?php echo $POS1 . "/" . $POS24; ?></dd>
	</dl>
	<dl>
		<dt>PoW Blocks (last 1h/24h):</dt>
		<dd><?php echo $POW1  . "/" . $POW24; ?></dd>
	</dl>
	<dl>
		<dt>PoS:PoW Ratio 1h/24:</dt>
		<dd><?php echo $ratio1 . "/" . $ratio24; ?></dd>
	</dl>
	<dl class="last">
		<dt>Connections:</dt>
		<dd><?php echo $network_info["connections"]; ?></dd>
	</dl>
	
	<p><a href="http://www.peercointalk.org" target="_blank">Brought to you by FuzzyBear and PeercoinTalk.org</a></p>
	<div class="logolink">
		<a href="http://peercoin.net" target="_blank"><img id="peercoin_logo" src="http://merchanttools.peercointalk.org/Logo/Logo.png" alt="Peercoin Logo" title="Peercoin Logo"></a>
	</div>
</div>

	
<?php

	}

	site_footer ();

/******************************************************************************
	This script is Copyright � 2013 Jake Paysnoe.
	I hereby release this script into the public domain.
	Jake Paysnoe Jun 26, 2013
******************************************************************************/
?>
