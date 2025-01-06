<?php
    include('../config/constants.php');

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $image_name = isset($_GET['image_name']) ? $_GET['image_name'] : "";

        // Check if image name is not empty
        if ($image_name != "") {
            $path = "../images/category/" . $image_name;
            $remove = unlink($path);

            if ($remove === false) {
                $_SESSION['remove'] = "<div class='error'>Failed to Remove Category Image.</div>";
                header('location:' . SITEURL . 'admin/manage-category.php');
                die();
            }
        }

        // Delete data from database
        $sql = "DELETE FROM tbl_category WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if ($res == true) {
            $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully.</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Category.</div>";
        }

        // Redirect to Manage Category
        header('location:' . SITEURL . 'admin/manage-category.php');
    } else {
        // Redirect to Manage Category if no ID provided
        header('location:' . SITEURL . 'admin/manage-category.php');
    }
?>
