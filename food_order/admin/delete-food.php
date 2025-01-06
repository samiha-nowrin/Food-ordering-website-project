<?php

    include('../config/constants.php');

    if (isset($_GET['id']) AND isset($_GET['image_name'])) {
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        if (!empty($image_name)) {
            $path = "../images/food/" . $image_name;

            // Debugging: Show the image path and whether it exists
            echo "Debug: File Path -> " . $path . "<br>";
            if (file_exists($path)) {
                echo "Debug: File exists. Attempting to delete.<br>";

                $remove = unlink($path);
                if ($remove == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to Remove Image File.</div>";
                    header('location:' . SITEURL . 'admin/manage-food.php');
                    die();
                }
            } else {
                $_SESSION['upload'] = "<div class='error'>Image File Not Found. Debug Path: " . $path . "</div>";
                header('location:' . SITEURL . 'admin/manage-food.php');
                die();
            }
        }

        $sql = "DELETE FROM tbl_food WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if ($res == true) {
            $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
            header('location:' . SITEURL . 'admin/manage-food.php');
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Food.</div>";
            header('location:' . SITEURL . 'admin/manage-food.php');
        }
    } else {
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:' . SITEURL . 'admin/manage-food.php');
    }

?>
