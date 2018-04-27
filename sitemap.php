<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */

    @set_time_limit(300);
    @ignore_user_abort();

    define('POSTS_MAX_LIMIT', 5000);
    define('POSTS_PER_SITEMAP', 500);

    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false, false);

    if (isset($_GET['sitemap']) == true) {
        $sitemap = intval($_GET['sitemap']);
        $offset = ($sitemap * POSTS_PER_SITEMAP) - POSTS_PER_SITEMAP;

        $rows = $Webfairy->post_db_rows(
            $Webfairy->db->posts()->order('`id` DESC')->limit(POSTS_PER_SITEMAP, $offset),
            array( 'simple' => true)
        );

        if (count($rows) > 0) {
            header('Content-Type: application/xml; charset=utf-8');

            echo "<"."?xml version='1.0' encoding='UTF-8'?".">\n";
            echo "<!-- Generated-On='".date("F j, Y, g:i a")."' Count='".count($rows)."' -->\n";

            echo "<urlset xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd' xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

            foreach ($rows as $row) {
                echo "<url>\n";
                echo "     <loc>".$row['absolute_url']."</loc>\n";
                echo "     <priority>1.0</priority>\n";
                echo "     <changefreq>daily</changefreq>\n";
                echo "     <lastmod>".date("c", $row['unixtime'])."</lastmod>\n";
                echo "</url>\n";
            }

            echo "</urlset>\n";
        } else {
            $Webfairy->go_to('/sitemap.xml');
        }
    } else {
        header('Content-Type: application/xml; charset=utf-8');

        echo "<"."?xml version='1.0' encoding='UTF-8'?".">\n";
        echo "<!-- generated-on='".date("F j, Y, g:i a")."' -->\n";
        echo "<sitemapindex xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd' xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

        $db = $Webfairy->db->posts(array('published' => 1))->order('`id` DESC')->limit(POSTS_MAX_LIMIT);
        $sitemaps = ceil($db->count('id') / POSTS_PER_SITEMAP);

        for ($i = 1; $i <= $sitemaps; $i++) {
            $offset = ($i * POSTS_PER_SITEMAP) - POSTS_PER_SITEMAP;
            $publishedon = $db->limit(1, $offset)->fetchSingle('publishedon');

            echo "<sitemap>\n";
            echo "<loc>".$Webfairy->absolute_url($Webfairy->url("sitemap_{$i}.xml"))."</loc>\n";
            echo "<lastmod>".date("c", $publishedon)."</lastmod>\n";
            echo "</sitemap>\n";
        }

        echo "</sitemapindex>\n";
    }
