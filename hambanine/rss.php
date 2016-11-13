<?php
header("Content-Type: application/rss+xml");
include("config.php");

echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title>Funeral Home RSS</title>
	<description>latest 10 obituaries</description>
	<link><?php echo $CONFIG["full_url"]; ?></link>
    <atom:link href="<?php echo $CONFIG["full_url"]; ?>rss.php" rel="self" type="application/rss+xml" />
<?php
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE status='Published' ORDER BY publish_date DESC";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL Query '.$sql.' have error: '.mysql_error());
	while ($Obituary = mysql_fetch_assoc($sql_result)) {
	$isPermaLink = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFG1234567890'), 0, 20);
?>
	<item>
		<guid isPermaLink='false'><?php echo $isPermaLink.$Obituary["id"]; ?></guid>
		<title><![CDATA[<?php echo ReadDB($Obituary["title"]); ?>]]></title>
        <link><?php echo $CONFIG["full_url"]; ?>preview.php?id=<?php echo $Obituary["id"]; ?></link>
		<description><![CDATA[<?php echo ReadDB($Obituary["summary"]); ?>]]></description>
        <pubDate><?php echo date("D, d M Y H:i:s O",strtotime($Obituary["publish_date"])); ?></pubDate>
        <?php if($Obituary["image"]!='') { ?>
        <enclosure url="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].$Obituary["image"]; ?>" length="0" type="image/jpeg" />
        <?php } ?>
	</item>
<?php } ?>
</channel>
</rss>