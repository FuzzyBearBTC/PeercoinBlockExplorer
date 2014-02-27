<div class="detail_display">

	<div class="detail_title">
		<?php echo $title; ?>
	</div>

	<div class="detail_data">
		<?php
			if($wordwrap > 0)
			{
				echo wordwrap($data, $wordwrap, "<br>", TRUE);
			}
			else
			{
				echo $data;
			}
		?>
	</div>
</div>