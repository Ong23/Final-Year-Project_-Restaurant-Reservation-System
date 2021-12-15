<?php

    session_start();

    if(isset($_SESSION['user_id'])){
        if(isset($_POST["report_submit"]))
        {
            $report = $_POST['report'];

            if($report=='guest_frequency')
            {
                $from_date = $_POST['from_date'];
                $until_date = $_POST['until_date'];
                $type = $_POST['type'];

                if($from_date < $until_date)
                {
                    header("Location: ../report.php?rpt=guests_freq&from=$from_date&to=$until_date&type=$type");
                    exit();
                }
                else
                {
                    header("Location: ../report.php?rpt=guests_freq&errorRpt=invalidbtwdate");
                    exit();
                }
                    
            }
            else if($report=='top_dishes')
            {
                $type = $_POST['d_type'];

                header("Location: ../report.php?rpt=top_dishes&type=$type");
                exit();
            }
        }
        else
        {
            header("Location: ../report.php?rpt=top_dishes");
            exit();
        }
    }

?>