<?php
    session_start();
    if (!$_SESSION['is_logged']) {
        header("Location: login.php?redirect=monthly_investment");
    }

    include("header.php");
    include_once("db_connect.php");
    
    $search_query = "select month, excepted, actual from monthly_investment";
    
    $db = new db();
    
    $rs = $db->query($search_query);
    if (!$rs) {
        // TODO: handle error
        echo "Sorry, unable to genrate result at this time. Please try later";
    }
    
    $series_data = '';
    
    $xAxis_values = array();
    $series_values = array();
    
    $previous_amount = 360000;
    while ($rs_row = mysqli_fetch_object($rs)) {
        $xAxis_values[] = strtotime($rs_row->month) * 100;
        $series_values['Actual'][] = $rs_row->actual;
        $series_values['Excepted'][] = $rs_row->excepted;
        
        $series_values['Added'][] = $rs_row->actual - $previous_amount;
        $previous_amount = $rs_row->actual;
    }
    
    foreach ($series_values as $series=>$svalues) {
        $series_data .= "{";
        $series_data .= "name: '$series Investment',";
        $series_data .= "data: [" . implode(',', $svalues) . "]";
        $series_data .= "},";
    }
?>
<script type="text/javascript">
    $(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'result_chart',
                zoomType: 'x',
                animation: true,
            },
            title: {
                text: null,
            },
            xAxis: [{
                title: {text: "Month"},
                type: 'datetime',
            }],
            yAxis: {
                title: {
                    text: 'Rupees (In Thousands)'
                },
                min: 0,
                startOnTick: false,
                showFirstLable: false                
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y +' Rs';
                }
            },
            series: [<?php echo $series_data ?>]
        });
    });
});

</script>
<h2>Monthly Investment Analysis Report</h2>
<div id="result_chart">
    <em>rendering result... please wait...</em>
</div>
<br/>

<?php
    include("footer.php");
?>
