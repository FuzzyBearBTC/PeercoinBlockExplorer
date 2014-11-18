<?php

	require_once ("src/PPC_daemon.php");
	require_once ("src/PPC_layout.php");
	require_once ("src/stats_controller.php");
		
// define variables and set to empty values
$inputErr = "";
$input= "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (!empty($_GET["input"])) {
    $input = test_input($_GET["input"]);
		site_header ("Peercoin Block Detail Page");
  }
}
// Trim; strip etc for input safety
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  return $data;
}
//	Capture any input, check which search to perform
	if (isset ($_GET["input"]))
	{
		//site_header ("Peercoin Block Detail Page");
        $show_error=false;
        
        // Block height provided
        if (strlen($input)<=7 && (is_numeric($input) || empty($input)))
        {              
        $block_height = $input;
        if(empty ($block_height))
		{
			$network_info = getinfo ();
			// Default to the latest block
			$block_height = intval($network_info["blocks"]);
		} 
		//site_header ("Block Detail Page");

		block_detail ($block_height);
        }
        
        // Block hash provided
        elseif (strlen($input)==64 && is_array(getblock($_GET["input"])))
	    {
            $info = getblock($_GET["input"]);
		 //   site_header ("Block Detail Page");
		    $block_hash = $_GET["input"];             

		    block_detail ($block_hash, TRUE);
	    }
        
        //	If a TXid was provided the TX Detail is shown
	    elseif (strlen($input)<=64 && is_array(getrawtransaction($_GET["input"])))
	    {
		//site_header ("Peercoin Transaction Detail Page");

		  tx_detail ($_REQUEST["input"]);
	    }
        
        // Incorrect input, return to index
        else
        {
            //header('Location:index.php');
             $show_detail=false;
            $show_error=true;
            $input_error = "not a block nor a transaction, please try again";
        }
	}
	
//	If there were no request parameters the menu is shown
	if (!$show_detail) {
		site_header("Peercoin Block Viewer");
				
		$network_info = getinfo ();
		$difficulty_info = getdifficulty ();
		$net_speed = getnetworkhashps ();
?>
<div id="site_menu">
	<p><center>Explore the Peercoin blockchain by looking for a Block Number (Index), Block Hash, or Transaction ID.</center></p>
    
    <form class="form-horizontal" role="form" action="index.php" method="get">
    <div class="form-group col-xs-12">

			<label class="sr-only" for="input" class="menu_desc">One button to rule them all</label> 
			<div class="col-xs-10 col-xs-offset-1"><input class="form-control" type="text" name="input" id="input" placeholder="block height, block hash or transaction id"></div>
			<div class="col-xs-1 no-padding"><input class="btn btn-ppc" type="submit" name="submit" value="Search"></div>
        <center><span class="error"><?php echo $input_error;?></span></center>
	</div>    
    </form>

	<div class="menu_item">
		<br>
        <p class="menu_desc"><center>Find out more about Peercoin (PPC):</center></p>
		<center><a href="http://peercoin.net" target="_blank">Visit Peercoin.net Official Peercoin Website</a></center>
		<center><a href="http://www.peercointalk.org" target="_blank">Official Peercoin Forum</a></center>
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
                <dt>Network Hashrate:</dt>
                <dd>
                <?php
                if (intval($net_speed) < 1024) {
                echo "".number_format($net_speed,2)." GH/s";
                } else {
                $net_speed = number_format(($net_speed / 1024),2);
                echo "".$net_speed." TH/s";
                }
                ?>
                </dd>
        </dl>
	<dl>
		<dt>Total Coins:</dt>
		<dd><?php echo $totalcoins; ?></dd>
	</dl>
	<dl>
		<dt>Price:</dt>
		<dd><span id="ticker">Loading...</span> / <span id="tickerbtc">Loading...</span></dd>
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
		<dd><?php echo $POScoins1 . " / " . $POScoins24; ?></dd>
	</dl>
	<dl>
		<dt>Average PoS Minting Reward (last 1h/24h):</dt>
		<dd><?php echo round($avgPOScoins1, 2) . " / " . round($avgPOScoins24, 2); ?></dd>
	</dl>
	<dl>
		<dt>PoW Mining Reward (last 1h/24h):</dt>
		<dd><?php echo $POWcoins1  . " / " . $POWcoins24; ?></dd>
	</dl>
	<dl>
		<dt>Average PoW Mining Reward (last 1h/24h):</dt>
		<dd><?php echo round($avgPOWcoins1, 2)  . " / " . round($avgPOWcoins24, 2); ?></dd>
	</dl>
	<dl>
		<dt>Total Blocks:</dt>
		<dd><?php echo number_format($totalblocks, 0 , '.' , ','); ?></dd>
	</dl>
	<dl>
		<dt>PoS Blocks (last 1h/24h):</dt>
		<dd><?php echo $POS1 . " / " . $POS24; ?></dd>
	</dl>
	<dl>
		<dt>PoW Blocks (last 1h/24h):</dt>
		<dd><?php echo $POW1  . " / " . $POW24; ?></dd>
	</dl>
	<dl class="last">
		<dt>PoS:PoW Ratio 1h/24:</dt>
		<dd><?php echo $ratio1 . " / " . $ratio24; ?></dd>
	</dl>
	<p><center><a href="http://www.peercointalk.org" target="_blank">Brought to you by FuzzyBear and PeercoinTalk.org</a>
    </center>
    </p>
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