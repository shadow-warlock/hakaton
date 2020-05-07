<?php
/**
 * Created by PhpStorm.
 * User: nik
 * Date: 19.02.19
 * Time: 22:38
 */

namespace Views;


use Models\Support\Pagination;

class PaginationView
{

    /**
     * @var Pagination $pagination
     */
    private $pagination;

    private $clickFunction;

    /**
     * PaginationView constructor.
     * @param Pagination $pagination
     * @param $clickFunction
     */
    public function __construct(Pagination $pagination, $clickFunction)
    {
        $this->pagination = $pagination;
        $this->clickFunction = $clickFunction;
    }


    public function drawPaging(){
        $nums = $this->pagination->makeNumbers();
        if(empty($nums))
            return;
        if(count($nums) == 1){
            $this->makeButton($nums[0], $this->pagination->getCurrentPage());
        }else{
            if($nums[0] + 1 == $nums[1]){
                $this->makeButton($nums[0], $this->pagination->getCurrentPage());
            }else{
                $this->makeButton($nums[0], $this->pagination->getCurrentPage(), "paging-button__after");
            }

            for($i = 1; $i < count($nums) - 1; $i++){
                $this->makeButton($nums[$i], $this->pagination->getCurrentPage());
            }

            if($nums[count($nums)  - 2] + 1 == $nums[count($nums)  - 1]){
                $this->makeButton($nums[count($nums) - 1], $this->pagination->getCurrentPage());
            }else{
                $this->makeButton($nums[count($nums) - 1], $this->pagination->getCurrentPage(), "paging-button__before");
            }
        }

        // foreach ($nums as $number){
        //     $this->makeButton($number, $this->pagination->getCurrentPage());
        // }
    }

    public function makeClass($page_num,$cur_page) {
        $class = "";
        switch($page_num) {
            case ($cur_page):
                $class = "paging-button toolbar-button selected";
                break;
            default:
                $class = "paging-button toolbar-button";
                break;
        }
        if(abs($page_num - $cur_page) > 5){
            $class .= " last";
        }
        return $class;
    }

    public function makeButton($page_num, $cur_page, $tmp="") {
        $class_name = $this->makeClass($page_num,$cur_page);
        ?><div onclick='<?=$this->clickFunction?>(<?=$page_num?>)' class='<?=$class_name?> <?=$tmp?>'><?=$page_num?></div><?
    }
}