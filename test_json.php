<?php
    include_once("db_connect.php");
    
    #$search_query = "select month, excepted, actual from monthly_investment";
    $search_query = "select unix_timestamp(str_to_date(month, '%b %Y')) as month, actual from monthly_investment";
    
    $db = new db();
    
    $rs = $db->query($search_query);
    if (!$rs) {
        // TODO: handle error
        echo "Sorry, unable to genrate result at this time. Please try later";
    }
    echo "?([\n";
    while ($rs_row = mysqli_fetch_object($rs)) {
        echo "[$rs_row->month, $rs_row->actual],\n";
    }
    echo "]);\n";
    