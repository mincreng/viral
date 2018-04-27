<?php
    function smarty_function_type2icon($params, $template)
    {
        switch ((int) $params['type']) {
            case 1:
                return 'picture-o';
            break;

            case 2:
                $provider = (empty($params['provider']) == false) ? strtolower($params['provider']) : 'code';

                switch ($provider) {
                    case 'youtube':
                    case 'yfrog':
                    case 'wordpresstv':
                    case 'vimeo':
                    case 'videojug':
                    case 'videofork':
                    case 'viddler':
                    case 'ustream':
                    case 'bliptv':
                    case 'ustream':
                    case 'dailymotion':
                        return 'play';
                    break;

                    case 'soundcloud':
                    case 'audiosnaps':
                    case 'scribd':
                    case 'Rdio':
                        return 'headphones';
                    break;

                    case 'deviantart':
                    case 'flickr':
                    case 'Instagram':
                    case 'gettyimages':
                        return 'picture-o';
                    break;
                }

                return $provider;
            break;

            case 3:
                return 'flash';
            break;

            case 4:
                return 'file-text';
            break;

            case 5:
                return 'play';
            break;

            case 6:
                return 'headphones';
            break;

            case 7:
                return 'file';
            break;

            case 8:
                return 'link';
            break;

            default :
                return 'file-o';
        }
    }
