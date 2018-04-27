<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */

    @set_time_limit(300);
    @ignore_user_abort();
    @libxml_use_internal_errors(true);

    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false, false, true);

    $Webfairy->updateOption('last_cron_job', time());

    $methods = array(
        'cleaner_cron' => '0 2 * * *',
        'fetcher_cron' => '*/5 * * * *',
    );

    foreach ($methods as $method => $cron_time) {
        $cron = $Webfairy->getOption($method, $cron_time);

        if ($Webfairy->is_time_cron(time(), $cron)) {
            switch ($method) {

                case 'cleaner_cron':
                    try {
                        Webfairy::cleanTmp(dirname(__FILE__).DS.'cache', array('index.php'));
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                break;

                case 'fetcher_cron':
                    if ((boolean) $Webfairy->getOption('fetcher_status', 0) == false) {
                        exit('fetcher_status:0');
                    }

                    $sources = array();

                    foreach ($Webfairy->db->sources('status', 1)->fetchPairs('id', 'title') as $id => $title) {
                        $sources[] = $id;
                    }

                    if (count($sources) == 0) {
                        exit('NO_ACTIVE_SOURCES');
                    }

                    $last_source_key = $Webfairy->getOption('last_source_key', 0);
                    $next_source_id = $last_source_key + 1;

                    if (isset($sources[$next_source_id]) == true) {
                        $source_id = $sources[$next_source_id];
                        $Webfairy->updateOption('last_source_key', $next_source_id);
                    } else {
                        $source_id = $sources[0];
                        $Webfairy->updateOption('last_source_key', 0);
                    }

                    $fetcher_processing = (int) $Webfairy->getOption('fetcher_processing');

                    if ((int) round((time() - $fetcher_processing) / 60, 0) >= 5) {
                        $fetcher_processing = 0;
                    }

                    if ($fetcher_processing == 0) {
                        $Webfairy->updateOption('fetcher_processing', time());
                        try {
                            $RssData = $Webfairy->FetchRSS($source_id,0);

                            foreach ($RssData['result']['items'] as $item) {
                                if ($item['fresh'] == true) {
                                    $records = $Webfairy->db_post_array($item['row']);

                                    $Webfairy->insert_post_to_db($records);
                                }
                            }

                            print_r($RssData['info']);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }

                        $Webfairy->updateOption('last_fetch_date', time());
                        $Webfairy->updateOption('fetcher_processing', 0);
                    } else {
                        echo 'Another process is already busy';
                    }
                break;
            }
        }
    }
