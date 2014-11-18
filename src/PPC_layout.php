<?php

date_default_timezone_set("UTC");

/**
* Output the site header HTML
*
* @param	string	$title	Page title
*/
function site_header ($title, $auth_list="")
{
	include('views/site_header.php');
}


/**
* Output the site footer HTML
*/
function site_footer ()
{
	include('views/site_footer.php');
	exit;
}

function site_stats ()
{
	include('views/site_stats.php');
}


/**
* Output the block detail information HTML/PHP code
*
* @param	int		$block_id
* @param	bool	$hash
*/
function block_detail($block_id, $hash=FALSE)
{
	if ($hash == TRUE) {
		$raw_block = getblock ($block_id);
	}
	else {
		$block_hash = getblockhash(intval ($block_id));
		$raw_block = getblock($block_hash);
	}

        if (isset($raw_block["hash"]))
            include('views/block_detail.php');
        else
        {
            section_head("Error");
            section_subhead("This block is not in the blockchain");
        }
}


/**
* Output transaction details via HTML code
*
* @param	string	$tx_id
*/	
function tx_detail ($tx_id)
{
	$raw_tx = getrawtransaction ($tx_id);

        if (!isset($raw_tx["txid"]))
        {
            section_head("Error");
            section_subhead("This transaction is not in the blockchain");
            return;
        }

	section_head ("Transaction: ".$raw_tx["txid"]);
	
	section_subhead ("Detailed Description");

	detail_display ("TX Version", $raw_tx["version"]);
	
	detail_display ("TX Time", date ("F j, Y, H:i:s", $raw_tx["time"]));
	
	detail_display ("Lock Time", $raw_tx["locktime"]);
	
	detail_display ("Confirmations", $raw_tx["confirmations"]);
	
	detail_display ("Block Hash", blockhash_link ($raw_tx["blockhash"]));
	
//	Florin Coin Feature
	if (isset ($raw_tx["tx-comment"]) && $raw_tx["tx-comment"] != "")
	{
		detail_display ("TX Message", htmlspecialchars ($raw_tx["tx-comment"]));
	}
	
	detail_display ("HEX Data", $raw_tx["hex"], 50);
	
	section_head ("Transaction Inputs");		
	
	foreach ($raw_tx["vin"] as $key => $txin)
	{
		section_subhead ("Input Transaction ".$key);

		if (isset ($txin["coinbase"]))
		{
			detail_display ("Coinbase", $txin["coinbase"]);
	
			detail_display ("Sequence", $txin["sequence"]);
		}
		
		else
		{
			detail_display ("TX ID", tx_link ($txin["txid"]));
	
			detail_display ("TX Output", $txin["vout"]);
	
			detail_display ("TX Sequence", $txin["sequence"]);
	
			detail_display ("Script Sig (ASM)", $txin["scriptSig"]["asm"], 50);
	
			detail_display ("Script Sig (HEX)", $txin["scriptSig"]["hex"], 50);
		}
	}
	
	section_head ("Transaction Outputs");
	
	foreach ($raw_tx["vout"] as $key => $txout)
	{
		section_subhead ("Output Transaction ".$key);
	
		detail_display ("TX Value", $txout["value"]);
	
		detail_display ("TX Type", $txout["scriptPubKey"]["type"]);
	
                if (isset ($txout["scriptPubKey"]["reqSigs"]))
                        detail_display ("Required Sigs", $txout["scriptPubKey"]["reqSigs"]);
	
		detail_display ("Script Pub Key (ASM)", $txout["scriptPubKey"]["asm"], 50);
	
		detail_display ("Script Pub Key (HEX)", $txout["scriptPubKey"]["hex"], 50);
	
		if (isset ($txout["scriptPubKey"]["addresses"]))
		{
			foreach ($txout["scriptPubKey"]["addresses"] as $key => $address);
			{
				detail_display ("Address ".$key, $address);
			}
		}
		
		}
	/* Commented as all the raw info is already presented above
	section_head ("Raw Transaction Detail");
	
	echo "	<textarea name=\"rawtrans\" rows=\"25\" cols=\"80\" style=\"text-align:left;\">\n";
	print_r ($raw_tx);
	echo "	\n</textarea><br><br>\n";*/
}


/**
* Output a detail element on the page
*
* @param	string	$title		Title/name of detail
* @param	string	$data		Detail data
* @param	int		$wordwrap	Word-wrap maximum length
*/
function detail_display ($title, $data, $wordwrap=0)
{
	include('views/detail_display.php');
}


/**
* Output a link to view a transaction ID
*
* @param	string	$tx_id
*/
function tx_link ($tx_id)
{
	return "<a href=\"?transaction=".$tx_id."\" title=\"View Transaction Details\">".$tx_id."</a>\n";
}


/**
* Output a link to view a block with a certain height
*
* @param	int		$block_height
*/
function blockheight_link ($block_height)
{
	return "<a href=\"".$_SERVER["PHP_SELF"]."?block_height=".$block_height."\" title=\"View Block Details\">".$block_height."</a>\n";
}


/**
* Output a link to view a block hash
*
* @param	int		$block_hash
*/
function blockhash_link ($block_hash)
{
	return "<a href=\"".$_SERVER["PHP_SELF"]."?input=".$block_hash."\" title=\"View Block Details\">".$block_hash."</a>\n";
}


/**
* Output the HTML for a section heading
*
* @param	string	$heading
*/
function section_head ($heading)
{
	echo "		<div class=\"section_head\">\n";
	echo "			".$heading."\n";
	echo "		</div>\n";
	echo "\n";
}


/**
* Output the HTML for a section subheading
*
* @param	string	$heading
*/
function section_subhead ($heading)
{
	echo "		<div class=\"section_subhead\">\n";
	echo "			".$heading."\n";
	echo "		</div>\n";
	echo "\n";
}
	
/******************************************************************************
	This script is Copyright � 2013 Jake Paysnoe.
	I hereby release this script into the public domain.
	Jake Paysnoe Jun 26, 2013
******************************************************************************/
?>
