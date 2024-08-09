<?php
/**
 * This scripts export all the data from production database and import it development database
 */
 
define("PRODUCTION_DATABASE", "pam");
define("DEVELOPMENT_DATABASE", "pam_dev");

// Config values
$database_host = 'localhost';
$database_username = 'root';
$database_password = '';

// PRODUCTION DATABASE EXPORT --- START
echo "Connecting to production database...\n<br/>";
@mysql_connect($database_host, $database_username, $database_password) or die("Unable to connect to database");
mysql_select_db(PRODUCTION_DATABASE);
echo "Start exporting data...\n<br/>";

$rs_tables =  mysql_query("show tables");
$tables = array();
while ($row = mysql_fetch_row($rs_tables)) { $tables[] = $row[0]; }

$result_sql = "";
foreach ($tables as $table) {
    $rs_table = mysql_query("select * from $table");
    
    if (mysqli_num_rows($rs_table)) {
        // empty development db table
        $result_sql .= "TRUNCATE TABLE `$table`;\n";
        
        // start the insertin data
        $result_sql .= "INSERT INTO `$table` VALUES\n";
        while ($row = mysql_fetch_row($rs_table)) {
            $result_sql .= "('" .implode($row, "', '")."'),\n";
        }
        
        // replace last comma(,) with semi-colon(;)
        $result_sql = preg_replace("/,\n$/", ";\n", $result_sql);
    }
    $result_sql .= "\n";
}
echo "Prodcution database data export is completed...\n<br/>";
mysql_close();
// PRODUCTION DATABASE EXPORT --- END

// DEVELOPMENT DATABASE IMPORT --- START
echo "Connecting to development database...\n<br/>";
@mysql_connect($database_host, $database_username, $database_password) or die("Unable to connect to database");
mysql_select_db(DEVELOPMENT_DATABASE);
echo "Start importing data...\n<br/>";
mysql_query($result_sql);
echo "Production database data import is completed...\n<br/>";
mysql_close();
// DEVELOPMENT DATABASE IMPORT --- END

// log the data in sql file
$fp = fopen("data".date("YmdHis").".sql", "w");
fwrite($fp, $result_sql);
fclose($fp);

echo "*** Task Completed! Good Bye!! ***";
