<?php
session_start();
if (!$_SESSION['is_logged']) {
    header("Location: login.php?redirect=edit_fd");
}

// set the default timezone as 'Asia/Calcutta';
date_default_timezone_set('Asia/Kolkata');

include_once("db_connect.php");

$db = new db();

$fd_id = $_REQUEST["fdid"];

if (!is_numeric($fd_id)) {
    header("Location: index.php");
}

$ref_id_msg = "";
$name_msg = "";
$deposite_scheme_msg = "";
$deposite_date_msg = "";
$renewal_date_msg = "";
$deposite_period_msg = "";
$maturity_date_msg = "";
$deposite_amount_msg = "";
$rate_of_interest_msg = "";
$interest_type_msg = "";
$total_interest_msg = "";
$maturity_amount_msg = "";

// check for POST request
if (isset($_POST['submit'])) {

    require_once("validation.php");

    // assign post values
    $ref_id = trim($_POST['ref_id']);
    $name = trim($_POST['name']);
    $deposite_scheme = trim($_POST['deposite_scheme']);
    $deposite_date = trim($_POST['deposite_date']);
    $renewal_date = trim($_POST['renewal_date']);
    $period = trim($_POST['period']);
    $period_type = trim($_POST['period_type']);
    $maturity_date = trim($_POST['maturity_date']);
    $deposite_amount = trim($_POST['deposite_amount']);
    $rate_of_interest = trim($_POST['rate_of_interest']);
    $interest_type = trim($_POST['interest_type']);
    $total_interest = trim($_POST['total_interest']);
    $maturity_amount = trim($_POST['maturity_amount']);

    /*$name_msg = "";
    $deposite_scheme_msg = "";
    $deposite_date_msg = "";
    $renewal_date_msg = "";
    $deposite_period_msg = "";
    $maturity_date_msg = "";
    $deposite_amount_msg = "";
    $rate_of_interest_msg = "";
    $total_interest_msg = "";
    $maturity_amount_msg = "";*/

    $is_valid = true;

    if (!is_not_empty($name)) {
        $name_msg = "Name should be valid.";
        $is_valid = false;
    }
    if (!is_not_empty($deposite_scheme)) {
        $deposite_scheme_msg = "Please select the Deposite Scheme.";
        $is_valid = false;
    }
    if (!is_valid_date($deposite_date)) {
        $deposite_date_msg = "Deposite date should be valid.";
        $is_valid = false;
    }
    if (!is_valid_date($renewal_date)) {
        $deposite_date_msg = "Renewal date should be valid.";
        $is_valid = false;
    }
    if (!is_valid_number($period)) {
        $deposite_period_msg = "Please select the period > 0.";
        $is_valid = false;
    }
    if (!is_not_empty($period_type)) {
        if ($deposite_period_msg) {
            $deposite_period_msg .= "<br/>Please select the period type.";
        } else {
            $deposite_period_msg = "Please select the period type.";
        }
        $is_valid = false;
    }
    if (!is_valid_date($maturity_date)) {
        $maturity_date_msg = "Maturity date should be valid.";
        $is_valid = false;
    }
    if (!is_valid_number($deposite_amount)) {
        $deposite_amount_msg = "Deposite amount should be valid (and > 0).";
        $is_valid = false;
    }
    if (!is_valid_number($rate_of_interest)) {
        $rate_of_interest_msg = "ROI should be valid (and > 0).";
        $is_valid = false;
    }
    if (!is_not_empty($interest_type)) {
        $interest_type_msg = "<span class=\"error\">Please select the Interest type.</span>";
        $is_valid = false;
    }
    if (!is_valid_number($total_interest)) {
        $total_interest_msg = "Total interest should be valid (and > 0).";
        $is_valid = false;
    }
    if (!is_valid_number($maturity_amount)) {
        $maturity_amount_msg = "Maturity amount should be valid (and > 0).";
        $is_valid = false;
    }

    if ($is_valid) {
        $name = ucwords(strtolower($name));
        $update_fd_query = "update accounts set name='$name', deposite_scheme='$deposite_scheme', deposite_date='$deposite_date', renewal_date='$renewal_date', period='$period', period_type='$period_type', maturity_date='$maturity_date', rate_of_interest='$rate_of_interest', interest_type='$interest_type', deposite_amount='$deposite_amount', total_interest='$total_interest', maturity_amount='$maturity_amount', ref_id='$ref_id' where id = '$fd_id'";

        if ($db->query($update_fd_query)) {
            $_SESSION['msg'] = "Accounts edited successfully";
            $_SESSION['fd_id'] = $fd_id;
            header("Location: ./index.php");
        } else {
            echo "Error while updating FD details.";
        }
    }
} else {
    $get_fd_data = "select * from accounts where id = '$fd_id'";
    $fd_rs = $db->query($get_fd_data);

    if (!mysqli_num_rows($fd_rs)) {
        header("Location: index.php");
    }

    $fd_rec = mysqli_fetch_object($fd_rs);

    // initialize variables
    $ref_id = $fd_rec->ref_id;
    $name = $fd_rec->name;
    $deposite_scheme = $fd_rec->deposite_scheme;
    $deposite_date = $fd_rec->deposite_date;
    $renewal_date = $fd_rec->renewal_date;
    $period = $fd_rec->period;
    $period_type = $fd_rec->period_type;
    $maturity_date = $fd_rec->maturity_date;
    $deposite_amount = $fd_rec->deposite_amount;
    $rate_of_interest = $fd_rec->rate_of_interest;
    $interest_type = $fd_rec->interest_type;
    $total_interest = $fd_rec->total_interest;
    $maturity_amount = $fd_rec->maturity_amount;
}

$user_rs = $db->query("select id, name from acc_users where is_active = 'y'");
$deposite_schemes_rs = $db->query("select id, scheme_name from deposite_schemes where is_active = 'y'");

$page_title = "Edit FD";
include("header.php");
?>

<form action="" method="post">
<h1 align="center">Edit FD</h1>
<table class="update_fd">
    <tr>
        <th>Account ID:</th>
        <td><input type="text" name="ref_id" id="ref_id" value="<?php echo $ref_id?>" style="width:160px" /></td>
        <td><?php echo $ref_id_msg ?></td>
    </tr>
    <tr>
        <th width="120px">Name:</th>
        <td width="180px">
            <select name="name" id="name" style="width:160px">
                <option value=""></option>
                <?php
                while ($user_row = mysqli_fetch_object($user_rs)) {
                    if ($name == $user_row->id) {
                        echo "<option value=\"$user_row->id\" selected>$user_row->name</option>";
                    } else {
                        echo "<option value=\"$user_row->id\">$user_row->name</option>";
                    }
                }
                ?>
            </select>
        </td>
        <td width="250px"><?php echo $name_msg?></td>
    </tr>
    <tr style="background-color:#3780B0">
        <th>Deposite Scheme:</th>
        <td>
            <select name="deposite_scheme" id="deposite_scheme" style="width:160px">
                <option value=""></option>
                <?php
                    while ($ds_row = mysqli_fetch_object($deposite_schemes_rs)) {
                        if ($deposite_scheme == $ds_row->id) {
                            echo "<option value=\"$ds_row->id\" selected>$ds_row->scheme_name</option>";
                        } else {
                            echo "<option value=\"$ds_row->id\">$ds_row->scheme_name</option>";
                        }
                    }
                ?>
            </select>
        </td>
        <td><?php echo $deposite_scheme_msg?></td>
    </tr>
    <tr>
        <th>Deposite Date:</th>
        <td><input type="text" name="deposite_date" id="deposite_date" value="<?php echo $deposite_date?>" readonly="readonly" style="width:140px" /></td>
        <td><?php echo $deposite_date_msg?></td>
    </tr>
    <tr style="background-color:#3780B0">
        <th>Renewal Date:</th>
        <td><input type="text" name="renewal_date" id="renewal_date" value="<?php echo $renewal_date?>" readonly="readonly" style="width:140px" /></td>
        <td><?php echo $renewal_date_msg?></td>
    </tr>
    <tr>
        <th>Period:</th>
        <td>
            <input type="text" name="period" id="period" value="<?php echo $period?>" style="width:75px" />
            <select name="period_type" id="period_type" style="width:80px" >
                <option value=""></option>
                <option value="d"<?php if ($period_type == "d") echo "selected"?>>Days</option>
                <option value="m"<?php if ($period_type == "m") echo "selected"?>>Months</option>
                <option value="y"<?php if ($period_type == "y") echo "selected"?>>Years</option>
            </select>
        </td>
        <td><?php echo $deposite_period_msg?></td>
    </tr>
    <tr style="background-color:#3780B0">
        <th>Maturity Date:</th>
        <td><input type="text" name="maturity_date" id="maturity_date" value="<?php echo $maturity_date?>" readonly="readonly" style="width:140px" /></td>
        <td><?php echo $maturity_date_msg?></td>
    </tr>
    <tr>
        <th>Deposite Amount:</th>
        <td><input type="text" name="deposite_amount" id="deposite_amount" value="<?php echo $deposite_amount?>" style="width:160px" /></td>
        <td><?php echo $deposite_amount_msg?></td>
    </tr>
    <tr style="background-color:#3780B0">
        <th>Rate of Interest:</th>
        <td><input type="text" name="rate_of_interest" id="rate_of_interest" value="<?php echo $rate_of_interest?>" style="width:150px" /> %</td>
        <td><?php echo $rate_of_interest_msg?></td>
    </tr>
    <tr>
        <th>Interest Type:</th>
        <td><select name="interest_type" id="interest_type">
            <option value=""></option>
            <option value="0" <?php if ($interest_type == "0") echo "selected"?>>Simple Interest</option>
            <option value="4" <?php if ($interest_type == "4") echo "selected"?>>Compound Interest (Queartly)</option>
            <option value="2" <?php if ($interest_type == "2") echo "selected"?>>Compound Interest (Half Yearly)</option>
            <option value="1" <?php if ($interest_type == "1") echo "selected"?>>Compound Interest (Yearly)</option>
            </select>
        </td>
        <td><?php echo $interest_type_msg?></td>
    </tr>
    <tr style="background-color:#3780B0">
        <th>Total Interest:</th>
        <td><input type="text" name="total_interest" id="total_interest" value="<?php echo $total_interest?>" style="width:160px" /></td>
        <td><?php echo $total_interest_msg?></td>
    </tr>
    <tr>
        <th>Maturity Amount:</th>
        <td><input type="text" name="maturity_amount" id="maturity_amount" value="<?php echo $maturity_amount?>" style="width:160px" /></td>
        <td><?php echo $maturity_amount_msg?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Update Fix Deposite" /></td>
        <td>&nbsp;</td>
    </tr>
</table>
</form>

<?php
    include("footer.php");
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#deposite_date').datepicker({
            dateFormat: 'yy-mm-dd',
            buttonImage: './media/images/calendar.gif',
            buttonImageOnly: true,
            showOn: 'button'
        });
        $('#renewal_date').datepicker({
            dateFormat: 'yy-mm-dd',
            buttonImage: './media/images/calendar.gif',
            buttonImageOnly: true,
            showOn: 'button'
        });
        $('#maturity_date').datepicker({
            dateFormat: 'yy-mm-dd',
            buttonImage: './media/images/calendar.gif',
            buttonImageOnly: true,
            showOn: 'button'
        });

        $('#period').change(cal_maturity_date1);
        $('#period_type').change(cal_maturity_date1);
        $('#renewal_date').change(cal_maturity_date1);

        $('#period').change(cal_maturity_amount);
        $('#period_type').change(cal_maturity_amount);
        $('#deposite_amount').change(cal_maturity_amount);
        $('#rate_of_interest').change(cal_maturity_amount);
        $('#interest_type').change(cal_maturity_amount);
    });
</script>
