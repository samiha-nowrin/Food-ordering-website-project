<?php include("partials/menu.php");?>  
<div class="main-content"></div>
   <div class="wrapper">
       <h1>Change Password</h1>
       <br><br>

       <?php  
          if(isset($_GET['id']))
          {
            $id=$_GET['id'];
          }
       
       ?>

       <form action="" method="POST">

          <table class="tbl-30">
            <tr>
                <td>CurrentPassword: </td>
                <td>
                    <input type="password" name="current_password" placeholder="Current Password">
                </td>
            </tr>
            <tr>
                <td>New Password:</td>
                <td>
                <input type="password" name="New_password" placeholder="New Password">
                </td>
            </tr>
            <tr>
                 <td>ConfirmPassword:</td>
                 <td>
                    <input type="password" name="Confirm_password" placeholder="Confirm Password">
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="submit" name="submit" value="Change Password" class="btn-secondary">

                </td>
            </tr>

          </table>
       </form>

</div>

<?php
    if(isset($_POST['submit']))
    {
       $id=$_POST['id'];
       $current_password = isset($_POST['current_password']) ? md5($_POST['current_password']) : null;
       $new_password = isset($_POST['new_password']) ? md5($_POST['new_password']) : null;
       $confirm_password = isset($_POST['confirm_password']) ? md5($_POST['confirm_password']) : null;

       $sql ="SELECT * FROM tbl_admin WHERE id=$id AND password='$current_password'";

       $res= mysqli_query($conn,$sql);

       if($res==TRUE)
       {

        $count=mysqli_num_rows($res);
        if($count==1)
        {
            //echo "User Found";
            if ($new_password == $confirm_password)
             {
               
                $sql2 ="UPDATE tbl_admin SET
                      PASSWORD='$new_password'
                      WHERE id=$id
                ";

                $res2=mysqli_query($conn,$sql2);


                if($res2==TRUE)
                {
                    $_SESSION['change-pwd'] = "<div class='success'>Password changed successfully.</div>";
                    header('location:'.SITEURL.'admin/mange-admin.php');
                }
                else
                {
                    $_SESSION['change-pwd'] = "<div class='error'> Failed to change Password .</div>";
                    header('location:'.SITEURL.'admin/mange-admin.php');
                }
             }
             else
             {
                $_SESSION['pwd-not-match'] = "<div class='error'>Password did not match.</div>";
            
                
                header('location:'.SITEURL.'admin/mange-admin.php');
             }
        }
        else
        {
           $_SESSION['user-not-found']="<div class='error'>User Not Found.</div>";
           header('location:'.SITEURL.'admin/mange-admin.php');
        }
       }

    }


?>


<?php include("partials/footer.php");?>  