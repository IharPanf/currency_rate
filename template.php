<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
</head>
<body>
    <?php  $curRate = $_POST['selectRate']; ?>
    <form action = "" method = "post">
        <select name="selectRate">
            <?php  while (list($key, $value) = each($massRateDate)) { ?>
                <option value="<?php echo $key?>" <?php if($key == $curRate) {echo ' selected';} ?> ><?php echo $value ?></option>
            <?php }?>
        </select>
        <input type="submit" value="Get currency rate">
    </form>
    <p>
         Currency rate:
    </p>
    <?php
        if ($curRate != '')
        {
            echo  $curValuta->getCurrencyRate("$curRate");
        }
    ?>
</body>
</html>