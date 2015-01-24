<html>
STUDENT(CR) VIEW PAGE
<br />
<?php
    echo "Welcome, ".$FirstName." ".$LastName;
    echo "<br />";
?>
    <br />
    <a href = "<?php echo site_url();?>main/myCredits">Click to view Credits</a>
    <br />
    <a href = "<?php echo site_url();?>Events/register">Click to view Events</a>
     <br/>
        <a href="<?php echo site_url();?>accounts/changePassword">Click to change password</a>
        <br />
        <a href="<?php echo site_url();?>accounts/logout">Click to logout</a>

 
</html>