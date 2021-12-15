<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

require('dbh.inc.php');
require('../fpdf/fpdf.php');

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

if(isset($_POST['export-reserv']))
{
    $reserv = $_POST['reservation'];
    $file_type = $_POST['file_type'];

    $sql = "";
    $title = "";

    

    if($reserv == 'upcoming')
    {
        $curr_date = date('Y-m-d');

        $sql = "SELECT * FROM reservation WHERE rdate >= '$curr_date' AND status = 'Confirmed' ORDER BY rdate DESC";
        $title = "Upcoming (Confirmed) Reservation List";
    }
    else
    {
        $sql = "SELECT * FROM reservation ORDER BY rdate DESC";
        $title = "All Reservation List";
    }

    if($file_type == 'excel')
    {
        $fileName =  ".$title._".date('Y-m-d').".xls"; 

        //Header Start
        $fields = array('Reserv. ID', 'First Name', 'Last Name', 'Guests', 'Reserv. Date', 'Time', 'Telephone', 'Comments', 'Register Date', 'User',  'Status'); 
    
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n"; 
        
    
        $result = $conn->query($sql);
        $number_of_rows = mysqli_num_rows($result);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {   /*
                if($row['last_rdate']==NULL)
                {
                    $rdate = NULL;
                }
                else
                {
                    $rdate = $row['last_rdate'];
                }*/
    
                $lineData = array($row['reserv_id'], $row['f_name'], $row['l_name'], $row['num_guests'], $row['rdate'], $row['time_zone'], $row['telephone'], $row['comment'], 
                $row['reg_date'], $row['user_fk'], $row['status']);
    
                array_walk($lineData, 'filterData'); 
                $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    
            }
        }
        else
        { 
            $excelData .= 'No records found...'. "\n"; 
        } 
         
        // Headers for download 
        header("Content-Type: application/vnd.ms-excel"); 
        header("Content-Disposition: attachment; filename=\"$fileName\""); 
         
        // Render excel data 
        echo $excelData; 
         
        exit;   
    }
    else if($file_type == 'pdf')
    {
        $pdf= new FPDF();
        $pdf->AddPage();
        
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(190,10,$title,0,1,'C');
        $pdf->Line(10,20,200,20);
        $pdf->Cell(30,10,'',0,1,'L');

        $pdf->SetFont('Arial', '', 12);
        $fill=false;
        
        //For coloring
        $counter = 1;

        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {   
                if($counter % 2 == 0)
                {
                    $pdf->setFillColor(255,255,255);
                }
                else
                {
                    $pdf->setFillColor(230,230,230);
                }
                    
                //Reservation ID
                $pdf->Cell(30,10,'Reserv. ID:',1,0,'L',1);
                $pdf->Cell(70,10,$row['reserv_id'],0,0,'L',1);

                //Date and Time
                $pdf->Cell(30,10,'Date & Time: ',1,0,'L',1);
                $pdf->Cell(25,10,$row['rdate'],0,0,'L',1);
                $pdf->Cell(5,10,'at',0,0,'L',1);
                $pdf->Cell(70,10,$row['time_zone'],0,1,'L',1);

                //Name
                $array = array($row['f_name'], $row['l_name']);
                array_walk($array, 'filterData');
                $name = $array[0].$array[1];

                $pdf->Cell(30,10,'Guest Name:',1,0,'L',1);
                $pdf->Cell(70,10,$name,0,0,'L',1);

                //Telephone
                $pdf->Cell(30,10,'Telephone:',1,0,'L',1);
                $pdf->Cell(70,10,$row['telephone'],0,1,'L',1);

                //No. of Guest
                $pdf->Cell(30,10,'No. of Guest:',1,0,'L',1);
                $pdf->Cell(70,10,$row['num_guests'],0,0,'L',1);

                //Status
                $pdf->Cell(30,10,'Status:',1,0,'L',1);
                if($row['status'] == 'Rejected' || $row['status'] == 'Cancelled')
                {
                    $pdf->SetTextColor(255,0,0);
                }
                else if($row['status'] == 'Confirmed')
                {
                    $pdf->SetTextColor(5,102,8);
                }
                else if($row['status'] == 'Edited')
                {
                    $pdf->SetTextColor(0,0,255);
                }
                $pdf->Cell(70,10,$row['status'],0,1,'L',1);


                $pdf->SetTextColor(0,0,0); //recover color

                //Comments
                if($row['comment']==NULL)
                {
                    $comm = '-';
                }
                else
                {
                    $comm = $row['comment'];
                }
                $pdf->Cell(30,20,'Comments:',1,0,'L',1);
                $pdf->MultiCell(170,20,$comm,0,1,'L',1);

                //Empty space
                $pdf->Cell(30,10,'',0,1,'L');
                if($counter % 4 == 0)
                {
                    $pdf->AddPage();
                }
                $counter++;
            }
        }
        else
        {
            if($reserv == 'upcoming')
            {
                $pdf->Cell(190,10,"No upcoming reservations",0,1,'C');
            }
        }

        $pdf->Output();
    }
}

//Reporting Feature Export

if(isset($_POST['excel-submit']))
{
    //https://youtu.be/l6_7O5Uz8TY?t=238
    
    $report = $_POST['rpt'];

    if($report=='guests_freq')
    {
        $f_date = $_POST['from_date'];
        $t_date = $_POST['to_date'];
        $type = $_POST['type'];
        if($type == 'top_10')
        {
            $title = '(TOP 10 Customers)';
            $sql_limit = 'LIMIT 10';
        }
        else if($type == 'all')
        {
            $title = '';
            $sql_limit = '';
        }

        $sql = "SELECT users.user_id, users.uidUsers, max(reservation.rdate) AS `last_rdate`,users.emailUsers, COUNT(reservation.user_fk) AS number_of_reservation 
                FROM users LEFT JOIN reservation 
                ON (users.user_id = reservation.user_fk) 
                AND reservation.rdate BETWEEN '$f_date' AND '$t_date'
                WHERE users.role_id != 2
                GROUP BY users.user_id 
                ORDER BY number_of_reservation DESC 
                $sql_limit";

        $counter = 1;

        // Excel file name for download 
        $fileName =  "$title _table_booking_freq_from_".$f_date."_to_".$t_date.".xls"; 

        //Header Start
        $fields = array('No', 'ID', 'User Name', 'Email', 'Last Reserv. Date', 'No. of Reserv'); 

        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n"; 
        
        $total_no_of_reserv = 0;

        $result = $conn->query($sql);
        $number_of_rows = mysqli_num_rows($result);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {   /*
                if($row['last_rdate']==NULL)
                {
                    $rdate = NULL;
                }
                else
                {
                    $rdate = $row['last_rdate'];
                }*/

                $lineData = array($counter, $row['user_id'], $row['uidUsers'], $row['emailUsers'], $row['last_rdate'], $row['number_of_reservation']);

                array_walk($lineData, 'filterData'); 
                $excelData .= implode("\t", array_values($lineData)) . "\n"; 

                $total_no_of_reserv += $row['number_of_reservation'];
                $counter++;
            }
        }
        else
        { 
            $excelData .= 'No records found...'. "\n"; 
        } 
         
        // Headers for download 
        header("Content-Type: application/vnd.ms-excel"); 
        header("Content-Disposition: attachment; filename=\"$fileName\""); 
         
        // Render excel data 
        echo $excelData; 
         
        exit;

        //https://www.youtube.com/watch?v=EYYZFRRdR6A
        //https://www.youtube.com/watch?v=XD8OOSwjMDs&t=557s

    }
    else if($report=='top_dishes')
    {
        $title = '';
        $title_end = '';
        $type = $_POST['type'];

        if($_POST['type'] == 'top_10')
        {
            $title = 'TOP 10';
            $sql_limit = 'ORDER BY number_of_dishes DESC LIMIT 10';
        }
        else if($_POST['type'] == 'all')
        {
            $title = '';
            $sql_limit = 'ORDER BY number_of_dishes DESC';
        }
        else if($_POST['type'] == 'by_category')
        {
            $title_end = 'By Dish Category';
            $sql_limit = 'ORDER BY dish.dish_cat_id ASC, number_of_dishes DESC';
        }

        $sql = "SELECT dish.dish_id, dish.dish_name, dish.dish_cat_id, COUNT(pod.dish_id) AS number_of_dishes 
        FROM dish LEFT JOIN `preorder dish` AS pod 
        ON (dish.dish_id = pod.dish_id)
        GROUP BY dish.dish_id 
        $sql_limit";

        $counter = 1;


        // Excel file name for download 
        $fileName =  "$title most_popular_food_(Preorder)_$title_end.xls"; 

        //Header Start
        if($_POST['type'] == 'by_category')
        {
            $fields = array('No', 'Dish ID', 'Dish Name',  'Dish Category', 'No. of Pre-Orders'); 
        }
        else
        {
            $fields = array('No', 'Dish ID', 'Dish Name', 'No. of Pre-Orders'); 
        }

        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n"; 
        

        //For report by dish category
        $repeat = false;
        $id = 0;

        $result = $conn->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()) 
            {   /*
                if($_POST['type'] == 'by_category')
                {
                    if($id != $row['dish_cat_id'])
                    {
                        $repeat = false;
                        $counter = 1;
                    }

                    if(!$repeat)
                    {
                        
                        $id = $row['dish_cat_id'];
                        $sql2 = "SELECT * FROM `dish category` WHERE dish_cat_id = $id";
                        $result2 = $conn->query($sql2);
                        while ($row2 = $result2->fetch_assoc()) {
                            $cat_desc = $row2['category_desc'];
                        }        

                        $repeat = true;
                    }
                }
                */
                if($_POST['type'] == 'by_category')
                {
                    $id = $row['dish_cat_id'];
                    $sql2 = "SELECT * FROM `dish category` WHERE dish_cat_id = $id";
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $cat_desc = $row2['category_desc'];
                    }    

                    $lineData = array($counter, $row['dish_id'], $row['dish_name'], $cat_desc, $row['number_of_dishes']);

                    array_walk($lineData, 'filterData'); 
                    $excelData .= implode("\t", array_values($lineData)) . "\n"; 
                }
                else
                {
                    
                    $lineData = array($counter, $row['dish_id'], $row['dish_name'], $row['number_of_dishes']);

                    array_walk($lineData, 'filterData'); 
                    $excelData .= implode("\t", array_values($lineData)) . "\n"; 
                }

                $counter++;
            }
        }
        else
        { 
            $excelData .= 'No records found...'. "\n"; 
        } 
         
        // Headers for download 
        header("Content-Type: application/vnd.ms-excel"); 
        header("Content-Disposition: attachment; filename=\"$fileName\""); 
         
        // Render excel data 
        echo $excelData; 
         
        exit;
    }


    
}

if(isset($_POST['pdf-submit']))
{   

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);

    $report = $_POST['rpt'];

    if($report=='guests_freq')
    {
        $f_date = $_POST['from_date'];
        $t_date = $_POST['to_date'];
        $type = $_POST['type'];
        if($type == 'top_10')
        {
            $title = '(TOP 10 Customers)';
            $sql_limit = 'LIMIT 10';
        }
        else if($type == 'all')
        {
            $title = '';
            $sql_limit = '';
        }

        $sql = "SELECT users.user_id, users.uidUsers, max(reservation.rdate) AS `last_rdate`,users.emailUsers, COUNT(reservation.user_fk) AS number_of_reservation 
                FROM users LEFT JOIN reservation 
                ON (users.user_id = reservation.user_fk) 
                AND reservation.rdate BETWEEN '$f_date' AND '$t_date'
                WHERE users.role_id != 2
                GROUP BY users.user_id 
                ORDER BY number_of_reservation DESC 
                $sql_limit";

        $total_no_of_reserv = 0;
        $counter = 1;

        $result = $conn->query($sql);
        $number_of_rows = mysqli_num_rows($result);

        //$pdf->Cell(40,10,''.$title.' Table Booking Frequency'); 
        //$pdf->Cell(0,30,'From '.$f_date.' To '.$t_date.''); 



        $pdf= new FPDF();
        $pdf->AddPage();

        $width_cell = array(10, 10, 40, 60, 40, 30); //total 190
        $pdf->SetFont('Arial', 'B', 16);

        //Title
        //Cell(width, height, text, border, newline, alignment)
        $pdf->Cell(200,10,$title,0,1,'C');
        $pdf->Cell(200,10,'Table Booking Frequency',0,1,'C');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(200,10,'From '.$f_date.' To '.$t_date.'',0,1,'C');

        //Header Start
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($width_cell[0],10,'No.',1,0,'C');
        $pdf->Cell($width_cell[1],10,'ID',1,0,'C');
        $pdf->Cell($width_cell[2],10,'User Name',1,0,'C');
        $pdf->Cell($width_cell[3],10,'Email',1,0,'C');
        $pdf->Cell($width_cell[4],10,'Last Reserv. Date',1,0,'C');
        $pdf->Cell($width_cell[5],10,'No. of Reserv',1,1,'C');

        $pdf->SetFont('Arial', '', 10);
        $fill=false;

        while($row = $result->fetch_assoc()) 
        {
            $pdf->Cell($width_cell[0],10, $counter,1,0,'C');
            $pdf->Cell($width_cell[1],10, $row['user_id'],1,0,'C');
            $pdf->Cell($width_cell[2],10, $row['uidUsers'],1,0,'C');
            $pdf->Cell($width_cell[3],10, $row['emailUsers'],1,0,'C');

            if($row['last_rdate'] == NULL)
            {
                $pdf->Cell($width_cell[4],10, 'No reservation made',1,0,'C');
            }
            else
            {
                $pdf->Cell($width_cell[4],10, $row['last_rdate'],1,0,'C');
            }

            $pdf->Cell($width_cell[5],10, $row['number_of_reservation'],1,1,'C');

            $total_no_of_reserv += $row['number_of_reservation'];
            $counter++;
        }

        $pdf->Cell($width_cell[0],10,'',0,0,'C');
        $pdf->Cell($width_cell[1],10,'',0,0,'C');
        $pdf->Cell($width_cell[2],10,'',0,0,'C');
        $pdf->Cell($width_cell[3],10,'',0,0,'C');
        $pdf->Cell($width_cell[4],10,'Total: ',1,0,'C');
        $pdf->Cell($width_cell[5],10,$total_no_of_reserv,1,1,'C');

        //https://www.youtube.com/watch?v=EYYZFRRdR6A
        //https://www.youtube.com/watch?v=XD8OOSwjMDs&t=557s

        $pdf->Output();

    }
    else if($report=='top_dishes')
    {
        $title = '';
        $title_end = '';
        $type = $_POST['type'];

        if($_POST['type'] == 'top_10')
        {
            $title = 'TOP 10';
            $sql_limit = 'ORDER BY number_of_dishes DESC LIMIT 10';
        }
        else if($_POST['type'] == 'all')
        {
            $title = '';
            $sql_limit = 'ORDER BY number_of_dishes DESC';
        }
        else if($_POST['type'] == 'by_category')
        {
            $title_end = 'By Dish Category';
            $sql_limit = 'ORDER BY dish.dish_cat_id ASC, number_of_dishes DESC';
        }

        $sql = "SELECT dish.dish_id, dish.dish_name, dish.dish_cat_id, COUNT(pod.dish_id) AS number_of_dishes 
        FROM dish LEFT JOIN `preorder dish` AS pod 
        ON (dish.dish_id = pod.dish_id)
        GROUP BY dish.dish_id 
        $sql_limit";

        $counter = 1;

        $result = $conn->query($sql);
        $number_of_rows = mysqli_num_rows($result);

        $pdf= new FPDF();
        $pdf->AddPage();

        $width_cell = array(20, 40, 80, 50); //total 190
        $pdf->SetFont('Arial', 'B', 18);

        //Title
        //Cell(width, height, text, border, newline, alignment)
        $pdf->Cell(200,10,''.$title.' Most Popular Dish (Pre-Ordered)',0,1,'C');
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(200,10,$title_end,0,1,'C');

        //Header Start
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($width_cell[0],10,'No.',1,0,'C');
        $pdf->Cell($width_cell[1],10,'Dish ID',1,0,'C');
        $pdf->Cell($width_cell[2],10,'Dish Name',1,0,'C');
        $pdf->Cell($width_cell[3],10,'No. of Pre-Orders',1,1,'C');

        //Display data table
        $fill=false;

        //For report by dish category
        $repeat = false;
        $id = 0;

        while($row = $result->fetch_assoc()) 
        {
            if($_POST['type'] == 'by_category')
            {
                if($id != $row['dish_cat_id'])
                {
                    $repeat = false;
                    $counter = 1;
                }

                if(!$repeat)
                {
                    
                    $id = $row['dish_cat_id'];
                    $sql2 = "SELECT * FROM `dish category` WHERE dish_cat_id = $id";
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $cat_desc = $row2['category_desc'];
                    }        
                    $pdf->SetFont('Arial', 'B', 12);

                    $pdf->setFillColor(230,230,230); 
                    $pdf->Cell(190,10, '   Dish Category: '.$cat_desc.'',1,1,'L', TRUE);

                    $repeat = true;
                }
            }
            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell($width_cell[0],10, $counter,1,0,'C');
            $pdf->Cell($width_cell[1],10, $row['dish_id'],1,0,'C');
            $pdf->Cell($width_cell[2],10, $row['dish_name'],1,0,'C');
            $pdf->Cell($width_cell[3],10, $row['number_of_dishes'],1,1,'C');

            $counter++;
        }

        $pdf->Output();
    }


}
mysqli_close($conn);

?>