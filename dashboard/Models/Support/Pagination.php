<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 15.02.19
 * Time: 1:57
 */

namespace Models\Support;


class Pagination
{

    private $currentPage;
    private $totalPagesCount;
    const PAGE_INTERVAL = 10;
    const PAGING = 20;
    const FIRST_PAGE_NUMBER = 1;
    private $numbers = [];

    /**
     * Pagination constructor.
     * @param $currentPage
     * @param $totalPagesCount
     */
    public function __construct($currentPage, $totalPagesCount)
    {
        $this->currentPage = $currentPage;
        $this->totalPagesCount = $totalPagesCount;
    }

    public function makeNumbers(){
        if($this->totalPagesCount >= 7) {
            if($this->currentPage < 4) {
                for($page_num = 1; $page_num < 7; $page_num++)
                    $this->numbers[] = $page_num;

                if($this->totalPagesCount < 16)
                    $this->numbers[] = $this->totalPagesCount;
                else
                    $this->numbers[] = 16;
            }
            else if ($this->currentPage > $this->totalPagesCount - 3) {
                if($this->totalPagesCount - 5 - static::PAGE_INTERVAL <= 0)
                    $this->numbers[] = static::FIRST_PAGE_NUMBER;
                else
                    $this->numbers[] = $this->totalPagesCount - 5 - static::PAGE_INTERVAL;
                for($page_num = $this->totalPagesCount - 5; $page_num <= $this->totalPagesCount; $page_num++)
                    $this->numbers[] = $page_num;
            }
            else {
                if($this->currentPage - 2 - static::PAGE_INTERVAL <= 0) {
                    $this->numbers[] = static::FIRST_PAGE_NUMBER;

                    if(($this->currentPage - 2 > static::FIRST_PAGE_NUMBER) &&
                        ($this->currentPage + 2 < $this->totalPagesCount)) {
                        $this->numbers[] = $this->currentPage - 2;
                        $this->numbers[] = $this->currentPage - 1;

                    }
                    else {
                        for($page_num = 2; $page_num < $this->currentPage; $page_num++)
                            $this->numbers[] = $page_num;
                    }
                }
                else {
                    $this->numbers[] = $this->currentPage - 2 - static::PAGE_INTERVAL;
                    $this->numbers[] = $this->currentPage - 2;
                    $this->numbers[] = $this->currentPage - 1;
                }

                if($this->currentPage != static::FIRST_PAGE_NUMBER)
                    $this->numbers[] = $this->currentPage;

                if($this->currentPage + 2 + static::PAGE_INTERVAL > $this->totalPagesCount) {
                    if(($this->currentPage + 2 < $this->totalPagesCount) &&
                        ($this->currentPage - 2 > 1)) {
                        $this->numbers[] = $this->currentPage + 1;
                        $this->numbers[] = $this->currentPage + 2;
                    }
                    $this->numbers[] = $this->totalPagesCount;
                }
                else {
                    $this->numbers[] =$this->currentPage + 1;
                    $this->numbers[] =$this->currentPage + 2;
                    $this->numbers[] =$this->currentPage + 2 + static::PAGE_INTERVAL;
                }
            }
        }
        else {
            for($page_num = static::FIRST_PAGE_NUMBER;
                $page_num <= $this->totalPagesCount; $page_num++)
                $this->numbers[] = $page_num;
        }
        return $this->numbers;
    }


    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return mixed
     */
    public function getTotalPagesCount()
    {
        return $this->totalPagesCount;
    }

    /**
     * @param mixed $totalPagesCount
     */
    public function setTotalPagesCount($totalPagesCount)
    {
        $this->totalPagesCount = $totalPagesCount;
    }




}