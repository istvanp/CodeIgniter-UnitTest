<!DOCTYPE html>
<html>
<head>
<?php $this->load->helper('url');?>
<title>Unit Testing &rsaquo; <?=ucfirst($type)?></title>
<link rel="stylesheet" type="text/css" href="<?=site_url("assets/css/unit-tests/unit_test.css")?>">
</head>
<body>
<div id="header">
	<h2>
		Unit Testing &rsaquo; <?=ucfirst($type)?>
	</h2>
</div>
<div id="nav">
	<input type="button" value="All" onclick="window.location='<?=site_url($this->uri->rsegment(1)."/all")?>'" />
	<input type="button" value="Models" onclick="window.location='<?=site_url($this->uri->rsegment(1)."/models")?>'" />
	<input type="button" value="Views" onclick="window.location='<?=site_url($this->uri->rsegment(1)."/views")?>'" />
	<input type="button" value="Helpers" onclick="window.location='<?=site_url($this->uri->rsegment(1)."/helpers")?>'" />
	<input type="button" value="Libraries" onclick="window.location='<?=site_url($this->uri->rsegment(1)."/libraries")?>'" />
	<select id="jump">
		<optgroup label="Test groups">
			<option value="<?=site_url($this->uri->rsegment(1)."/all")?>"<?php if ($type == 'all') echo ' selected="selected"'?>>All</option>
		<?php foreach($tests as $test_type => $testgroup) { ?>
			<option value="<?=site_url($this->uri->rsegment(1)."/$test_type")?>"<?php if ($type == $test_type) echo ' selected="selected"'?>><?=ucfirst($test_type)?></option>
		<?php } ?>

		</optgroup>
		<?php foreach($tests as $test_type => $testgroup) { ?>
		<optgroup label="<?=ucfirst($test_type)?>">
			<?php foreach($testgroup as $test) { ?>
			<option value="<?=site_url($this->uri->rsegment(1)."/$test")?>"<?php if ($type == $test) echo ' selected="selected"'?>><?=$test?></option>
			<?php } ?>

		</optgroup>
		<?php } ?>

	</select>
	<input type="button" value="Run" onclick="window.location=document.getElementById('jump').value" />
</div>
<?php if (isset($msg) && strlen($msg) > 0) { ?>
<div id="message">
	<?=$msg?>
</div>
<?php } ?>
<?php if ($totals['all'] > 0) { ?>
<div id="report">
	<div class="summary <?=($totals['failed'] > 0) ? 'fail' : 'pass' ?>">
		<?=$totals['passed']?> / <?=$totals['all']?> tests passed in <?=$total_time?> seconds
	</div>
	
	<?php foreach($report as $key => $test) {
		if (array_key_exists($key, $headings['types']))
			echo "<h1>{$headings['types'][$key]}</h1>\n";
		if (array_key_exists($key, $headings['tests']))
			echo "<h2>{$headings['tests'][$key]}</h2>\n";
	?>
	<div class="test <?=($test['Result'] == 'Passed') ? 'pass' : 'fail' ?>">
		<div class="result"><?=strtoupper($test['Result'])?></div>
		<h3><?=$test['Test Name']?></h3>
		<div class="details">
			<div class="time"><?=$timings[$key]?></div>
			Expected
				<strong><?=var_export($test['Expected Value'])?></strong> (<?=strtolower($test['Expected Datatype'])?>),
			returned
				<?php if (is_string($test['Test Value'])): ?>
				<?=highlight_code($test['Test Value'])?>
				<?php else: ?>
				<strong><?=var_export($test['Test Value'])?></strong> (<?=strtolower($test['Test Datatype'])?>)<br />
				<?php endif; ?>
				<?php if ( ! empty($test['SQL Error'])): ?>
				<code><?=$test['SQL Error']?></code>
				<?php endif; ?>
				<?php if ( ! empty($test['SQL Query'])): ?>
				<?=highlight_code($test['SQL Query'])?>
				<?php endif; ?>
			<em><?=substr($test['File Name'], strlen(FCPATH))?></em> on line <?=$test['Line Number']?>
		</div>
	</div>
	<?php } ?>
</div>
<?php } ?>
</body>
</html>