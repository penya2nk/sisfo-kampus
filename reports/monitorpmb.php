<?php
//start kolom 1
echo"<div class='row'>
        <div class='col-md-6'>"; //end
        include "reports/monitorpmb_reg.php";
        //close kolom 1
        echo"</div>";

        //start kolom 2
        echo "<div class='col-md-6'>"; 
        include "reports/monitorpmb_reg_ul.php";
        //tutup kolom 2
        echo"</div>
     </div>";
?>