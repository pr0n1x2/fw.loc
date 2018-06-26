<?php

namespace fw\libs;

class Pagination
{
    public $currentPage;
    public $perpage;
    public $total;
    public $countPages;
    public $uri;

    public function __construct($page, $total, $perpage = 10)
    {
        $this->perpage = $perpage;
        $this->total = $total;
        $this->countPages = $this->getCountPages();
        $this->currentPage = $this->getCurrentPage($page);
        $this->uri = $this->getParams();
    }

    public function __toString()
    {
        return $this->getHtml();
    }

    public function getCountPages()
    {
        return ceil($this->total / $this->perpage) ?: 1;
    }

    public function getCurrentPage($page)
    {
        if (!$page || $page < 1) {
            $page = 1;
        }

        if ($page > $this->countPages) {
            $page = $this->countPages;
        }

        return $page;
    }

    public function getStart()
    {
        return ($this->currentPage - 1) * $this->perpage;
    }

    public function getParams()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = explode("?", $url);
        $uri = $url[0] . '?';

        if (isset($url[1]) && $url[1] != '') {
            $params = explode('&', $url[1]);

            foreach ($params as $param) {
                if (!preg_match("#page=#", $param)) {
                    $uri .= "{$param}&amp;";
                }
            }
        }

        return $uri;
    }

    public function getHtml()
    {
        $back = null;
        $forward = null;
        $startpage = null;
        $endpage = null;
        $page2left = null;
        $page1left = null;
        $page2right = null;
        $page1right = null;

        $startLi = '<li><a class="nav-link" href="' . $this->uri . 'page=';
        $endLi = '</a></li>';

        if ($this->currentPage > 1) {
            $back = $startLi . ($this->currentPage - 1) . '">&lt;' . $endLi;
        }

        if ($this->currentPage < $this->countPages) {
            $forward = $startLi . ($this->currentPage + 1) . '">&gt;' . $endLi;
        }

        if ($this->currentPage > 3) {
            $startpage = $startLi . '">&laquo;' . $endLi;
        }

        if ($this->currentPage < ($this->countPages - 2)) {
            $endpage = $startLi . $this->countPages . '">&raquo;' . $endLi;
        }

        if ($this->currentPage - 2 > 0) {
            $page2left = $startLi . ($this->currentPage - 2) . '">' . ($this->currentPage - 2) . $endLi;
        }

        if ($this->currentPage - 1 > 0) {
            $page1left = $startLi . ($this->currentPage - 1) . '">' . ($this->currentPage - 1) . $endLi;
        }

        if ($this->currentPage + 1 <= $this->countPages) {
            $page1right = $startLi . ($this->currentPage + 1) . '">' . ($this->currentPage + 1) . $endLi;
        }

        if ($this->currentPage + 2 <= $this->countPages) {
            $page2right = $startLi . ($this->currentPage + 2) . '">' . ($this->currentPage + 2) . $endLi;
        }

        return '<ul class="pagination">' . $startpage . $back . $page2left . $page1left .
            '<li class="active"><a>' . $this->currentPage . '</a></li>' . $page1right . $page2right .
            $forward . $endpage . '</ul>';
    }
}
