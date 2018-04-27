<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */

    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false, false, false, array());

        $posts = $Webfairy->post_db_rows(
             $Webfairy->db->posts()->order('`publishedon` DESC')->limit(25),
             array(
                'simple' => true,
             )
        );

        $last_post = end($posts);

        include dirname(__FILE__).DS.'lib'.DS.'RSSWriter'.DS.'Autoload.php';

        $feed = new RSSWriter\Feed();

        $channel = new RSSWriter\Channel();
        $channel
            ->title($Webfairy->getOption('site_name'))
            ->description($Webfairy->getOption('description'))
            ->url($Webfairy->absolute_url($Webfairy->getOption('base_url')))
            ->language($Webfairy->get_locale())
            ->copyright($Webfairy->getOption('copyrights'))
            ->lastBuildDate($last_post['unixtime'])
            ->ttl(60)
            ->appendTo($feed);

        foreach ($posts as $post_item) {
            $item = new RSSWriter\Item();
            $item
                ->title($post_item['title'])
                ->url($post_item['absolute_url'])
                ->pubDate($post_item['unixtime'])
                ->guid($post_item['absolute_url'], true);
                
            if(in_array($post_item['type'],array(1,3,5,6)) == true){
                $item->enclosure(
                    $Webfairy->_uploaded('url',$post_item['file']['file_physical_name']),
                    $post_item['file']['file_size'],
                    $post_item['file']['file_mime_type']
                );
            }    

            $description = '';
            
            if(empty($post_item['thumb']) == false){
                $description .= $Webfairy->image($post_item['thumb'],'medium',true,array('alt' => $post_item['title']));
            }
            
            if (empty($post_item['description']) == false) {
                $description .= '<p>'.$post_item['description'].'</p>';
            }
            
            if(empty($description) == false){
                $item->description($description);
            }

            $item->appendTo($channel);
        }

        echo $feed;
