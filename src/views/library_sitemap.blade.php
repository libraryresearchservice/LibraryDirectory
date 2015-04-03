<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
<?php
foreach ( $libraries as $v ) {
	?>
<url>
    <loc>http://find.coloradolibraries.org/library/view/<?php echo $v->id ?></loc>
    <lastmod>2015-02-10</lastmod>
    <changefreq>monthly</changefreq>
</url>
	<?php	
}
?>
</urlset>