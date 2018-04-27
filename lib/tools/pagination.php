<?php
/**
 * @author Webfairy MediaT CMS - www.Webfairy.net
 */
class pagination
{
    var $object,$url,$page,$limit,$start;

    public function __construct($object, $limit = 5, $page = 0)
    {
        $this->object = & $object;
        if ($page > 0) {
            $this->page = $page;
        } else {
            $this->page = (isset($_GET["page"]) == true) ? (int) $_GET["page"] : 1;
        }
        $this->limit = $limit;
        $this->start = ($this->page * $this->limit) - $this->limit;
        $this->count = $this->object->count('id');
    }

    public function href($page)
    {
        parse_str(parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY), $output);
        $output['page'] = $page;

        return '?'.http_build_query($output);
    }

    public function get_page_title()
    {
        return ($this->page > 1) ? "صفحة {$this->page}" : '';
    }

    public function get_page()
    {
        return $this->page;
    }

    public function rows()
    {
        return $this->object->limit($this->limit, $this->start);
    }

    public function display()
    {
        $adjacents = 2;

        $prev = $this->page - 1;
        $next = $this->page + 1;
        $lastpage = ceil($this->count/$this->limit);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination pagination-sm'>";

            if ($this->page > 1) {
                $pagination .= sprintf("<li><a href='%s' class='previous'>%s</a>", $this->href($prev), tr('p_previous'));
            } else {
                $pagination .= sprintf("<li><a>%s</a>", tr('p_previous'));
            }

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $this->page) {
                        $pagination .= "<li class='active'><a>{$counter}</a></li>";
                    } else {
                        $pagination .= "<li><a href='{$this->href($counter)}'>{$counter}</a></li>";
                    }
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($this->page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $this->page) {
                            $pagination .= "<li class='active'><a>{$counter}</a></li>";
                        } else {
                            $pagination .= "<li><a href='{$this->href($counter)}'>{$counter}</a></li>";
                        }
                    }
                    $pagination .= "<li class='dots'><a>...</a></li>";
                    $pagination .= "<li><a href='{$this->href($lpm1)}'>{$lpm1}</a></li>";
                    $pagination .= "<li><a href='{$this->href($lastpage)}'>{$lastpage}</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $this->page && $this->page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='{$this->href(1)}'>1</a></li>";
                    $pagination .= "<li><a href='{$this->href(2)}'>2</a></li>";
                    $pagination .= "<li class='dots'><a>...</a></li>";
                    for ($counter = $this->page - $adjacents; $counter <= $this->page + $adjacents; $counter++) {
                        if ($counter == $this->page) {
                            $pagination .= "<li class='active'><a>{$counter}</a></li>";
                        } else {
                            $pagination .= "<li><a href='{$this->href($counter)}'>{$counter}</a></li>";
                        }
                    }
                    $pagination .= "<li class='dots'><a>...</a></li>";
                    $pagination .= "<li><a href='{$this->href($lpm1)}'>{$lpm1}</a></li>";
                    $pagination .= "<li><a href='{$this->href($lastpage)}'>{$lastpage}</a></li>";
                } else {
                    $pagination .= "<li><a href='{$this->href(1)}'>1</a></li>";
                    $pagination .= "<li><a href='{$this->href(2)}'>2</a></li>";
                    $pagination .= "<li class='dots'><a>...</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $this->page) {
                            $pagination .= "<li class='active'><a>{$counter}</a></li>";
                        } else {
                            $pagination .= "<li><a href='{$this->href($counter)}'>{$counter}</a></li>";
                        }
                    }
                }
            }

            if ($this->page < $counter - 1) {
                $pagination .= sprintf("<li><a href='%s' class='next'>%s</a></li>", $this->href($next), tr('p_next'));
            } else {
                $pagination .= sprintf("<li><a>%s</a></li>", tr('p_next'));
            }

            $pagination .= "</ul>\n";
        }

        return $pagination;
    }
}
