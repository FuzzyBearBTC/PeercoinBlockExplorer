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
 
So far I have started on the ratio part specifically the POW:POS last 24h. This is all sudo code at the moment.

	Peercoin PoS vs PoW Blocks
	Overview:
	Get the last 180 blocks //because the current block time is an average of 8 minutes (when I checked) 60 minutes * 24 hours = 1440. 1440/8 = 180. Could make 
this dynamic.
	Check flags
	Use accumulators to count how many PoW blocks and PoS blocks
	Return bar graph

	A little more detailed.
	
	Get the last 180 blocks
	-Set time to currenttime
	-Find current block
	-Set iblock to currentblock - 180 
	#-Set Totalblocks to 180
	-Initialize PoW and PoS to 0
	Check flags
	-Look at first block and get flag
	-Accumulate respective block type
	#-Decrease Total blocks by 1
	-Increase iblock by 1
	-Repeat until iblock == currentblock
	Return bar graph
	-Make bar graph of each separate total with currentblock and currenttime
*/
/*

POW vs PoS 24hr

*/

require_once ("src/bc_daemon.php");
require_once ("src/bc_layout.php");

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
		
		$POW = $POS = 0;
		while ($iblock != intval($currentblock))
		{
			$flag = block_flag($iblock);
			if ($flag == "proof-of-work")
			{
				$POW++;
			}
			else 
			{
				$POS++;
			}
			$iblock++;
		}
		return $POS;
		
	}
		
	function block_flag($block_id)
	{
		$block_hash = getblockhash($block_id);
		$raw_block = getblock($block_hash);
		$flags = $raw_block["flags"];
		return $flags;
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
