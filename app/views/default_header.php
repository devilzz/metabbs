<? if (!isset($head_only)) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?=$title?></title>
<? } ?>
	<link rel="stylesheet" href="<?=$style_dir?>/style.css" type="text/css" />
	<script type="text/javascript" src="<?=METABBS_BASE_PATH?>app/views/prototype.js"></script>
<? if ($skin[0] == '_') { ?>
	<script type="text/javascript" src="<?=METABBS_BASE_PATH?>skins/_admin/script.js"></script>
<? } else { ?>
	<script type="text/javascript" src="<?=METABBS_BASE_PATH?>app/views/script.js"></script>
<? } ?>
<? if (isset($board) && $skin[0] != '_') { ?>
	<link rel="alternate" href="<?=url_for($board, 'rss')?>" type="application/rss+xml" title="RSS" /> 
<? } ?>
<? if (!isset($head_only)) { ?>
</head>
<body>
<? } ?>
