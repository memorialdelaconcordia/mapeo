<?php

function pagineo($targetpage,
                 $page,
                 $lastpage,
                 $lpm1,
                 $prev,
                 $next,
                 $adjacents) {
    
    $pagination = "";
    
    if($lastpage > 1) {
        
        $pagination .= '<nav aria-label=""><ul class="pagination justify-content-center">';
        if ($page > $counter + 1) {
            $pagination.= '<li class="page-item">';
            $pagination.= '<a class="page-link" href="'.$targetpage.'&page='.$prev.'">';
            $pagination.= '<span aria-hidden="true">&laquo;</span>';
            $pagination.= '<span class="sr-only">Previous</span>';
            $pagination.= '</a></li>';
        }
        
        if ($lastpage < 7 + ($adjacents * 2))
        {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= '<li class="page-item active"><a class="page-link" href="#">'.$counter.'</a></li>';
                    else
                        $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$counter.'">'.$counter.'</a></li>';
            }
        } else if($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
            
            //close to beginning; only hide later pages
            if($page < 1 + ($adjacents * 2))
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li class="page-item active"><a class="page-link" href="#">'.$counter.'</a></li>';
                        else
                            $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$counter.'">'.$counter.'</a></li>';
                }
                $pagination.= '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">...</a></li>';
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$lpm1.'">'.$lpm1.'</a></li>';
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$lastpage.'">'.$lastpage.'</a></li>';
            }
            //in middle; hide some front and some back
            else if($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page=1">1</a></li>';
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page=2">2</a></li>';
                $pagination.= '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">...</a></li>';
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li class="page-item active"><a class="page-link" href="#">'.$counter.'</a></li>';
                        else
                            $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$counter.'">'.$counter.'</a></li>';
                }
                $pagination.= '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">...</a></li>';
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$lpm1.'">'.$lpm1.'</a></li>';
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$lastpage.'">'.$lastpage.'</a></li>';
            }
            //close to end; only hide early pages
            else
            {
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page=1\">1</a></li>';
                $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page=2\">2</a></li>';
                $pagination.= '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">...</a></li>';
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination.= '<li class="page-item active"><a class="page-link" href="#">'.$counter.'</a></li>';
                    } else {
                        $pagination.= '<li class="page-item"><a class="page-link" href="'.$targetpage.'&page='.$counter.'">'.$counter.'</a></li>';
                    }
                }
            }
        }
        
        //next button
        if ($page < $counter - 1) {
            $pagination.= '<li class="page-item">';
            $pagination.= '<a class="page-link" href="'.$targetpage.'&page='.$next.'" aria-label="Next">';
            $pagination.= '<span aria-hidden="true">&raquo;</span>';
            $pagination.= '<span class="sr-only">Next</span>';
            $pagination.= '</a></li>';
        } else {
            $pagination.= '';
        }
        $pagination.= '</ul></nav>';
    }
    
    return $pagination;

}

?>