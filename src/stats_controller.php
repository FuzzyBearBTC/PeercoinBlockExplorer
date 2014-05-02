<?php
/*
I would love to see this thing similar to bitinfocharts.com/ppcoin.
I (Hibero) will be working on this but I am not completely familiar with PHP or HTML. So this is a way I thought could open it up to more people.
So I was thinking there are a lot of statistics that are currently not readily available for Peercoin and I feel we can make this blockchain explorer great by 
adding some of them.

Here are some stats we can maybe add over time:
Peercoin Price
Market Capitalization
Transactions last 24h
Transactions avg. per hour
Peercoins sent avg. per hour (last 24h)
Avg. transaction value (last 24h)
Avg. Confirmation Time last 24h
Block Count
Blocks last 24h
Blocks avg. per hour (last 24h)
Total Reward (last 24h)
Orphaned BLocks (last 24h)
Difficulty
Network Hashrate (avg. yesterday)
Top 100 Richest Addresses
Addresses richer than 1/100/1,000/10,000
Active Addresses last 24h
100 Largest Transactions
Date of Peercoin First Block
Blockchain Size
Peercoin Days Destroyed last 24h / Total Peercoins

The stats that would be unique to POS/POW hybrid (The ones that are not out there):
POW:POS Block Ratio last 1h/24h/7d/1m/1y/all time
Avg. Minted Reward last 1h/24h
Avg. Mined Reward last 1h/24h
 
*/
/*

POW vs PoS 24hr

*/

/*Iheart Comment: Note that the RPC interface is not able to achieve all of the stats Hibero is interested in.  We will have to add some sort of SQL database to parse the blockchain.
See http://github.com/snakie/blockparser as possible solution to incorporate
*/

require_once ("src/PPC_daemon.php");
require_once ("src/PPC_layout.php");

	/**
	* Get the number of pos block in the last @param hours
	*
	* @param	int	$hours
	*
	* @return	int
	*/
	function get_num_pos($hours) 
	{
		$network_info = getinfo ();

		$currentblock = $network_info["blocks"];
		
		$iblock = intval($currentblock) - 6*$hours;
		
		$POScoins = 0;
		$POWcoins = 0;
		$POS = 0;
                $POW = 0;
                $avgPOScoins = 0;
                $avgPOWcoins = 0;
		while ($iblock != intval($currentblock))
		{
			$flag = block_flag($iblock);
			$coins = block_mint($iblock);
			if (strpos($flag ,"proof-of-stake") !== false)
			{
				$POS++;
				$POScoins += $coins;
			}
			else {
                                $POW++;
				$POWcoins += $coins;
			}
			$iblock++;
		}
                if ($POS > 0)
                    $avgPOScoins = $POScoins / $POS;
                if ($POW > 0)
                    $avgPOWcoins = $POWcoins / $POW;

                return array($POS, $POW, $POScoins, $POWcoins, $avgPOScoins, $avgPOWcoins);
	}
	
	//Find the flag for a block
	
	function block_flag($block_id)
	{
		$block_hash = getblockhash($block_id);
		$raw_block = getblock($block_hash);
		$flags = $raw_block["flags"];
		return $flags;
	}
	
	// Find the minted or mined coins
	function block_mint($block_id)
	{
		$block_hash = getblockhash($block_id);
		$raw_block = getblock($block_hash);
		$mint = $raw_block["mint"];
		return $mint;
	}
	
		function ratio($a, $b) {
    $_a = $a;
    $_b = $b;

    while ($_b != 0) {

        $remainder = $_a % $_b;
        $_a = $_b;
        $_b = $remainder;   
    }

    $gcd = abs($_a);

    return ($a / $gcd)  . ':' . ($b / $gcd);

}
?>
