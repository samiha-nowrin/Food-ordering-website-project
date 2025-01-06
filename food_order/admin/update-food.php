<?php include('partials/menu.php'); ?>

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query to get the current food details
    $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
    $res2 = mysqli_query($conn, $sql2);

    if ($res2 == true) {
        $row2 = mysqli_fetch_assoc($res2);

        // Retrieve existing data
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active'];
    }
} else {
    // Redirect if no ID provided
    header('location:' . SITEURL . 'admin/manage-food.php');
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php
                        if ($current_image == "") {
                            echo "<div class='error'>Image not Available.</div>";
                        } else {
                            echo "<img src='" . SITEURL . "images/food/" . $current_image . "' width='150px'>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Select New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">
                            <?php
                            // Fetch categories
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            $res = mysqli_query($conn, $sql);

                            if ($res == true) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $category_id = $row['id'];
                                    $category_title = $row['title'];

                                    // Mark the current category as selected
                                    echo "<option " . ($current_category == $category_id ? "selected" : "") . " value='$category_id'>$category_title</option>";
                                }
                            } else {
                                echo "<option value='0'>Category Not Available.</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Featured:</td>
                    <td>
                        <input <?php if ($featured == "Yes") echo "checked"; ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if ($featured == "No") echo "checked"; ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <input <?php if ($active == "Yes") echo "checked"; ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if ($active == "No") echo "checked"; ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            // Get updated values
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $current_image = $_POST['current_image'];
            $category = $_POST['category'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // Image handling
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = $_FILES['image']['name'];

                // Rename the image
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = "Food-Name-" . rand(0000, 9999) . '.' . $ext;

                // Upload image
                $src_path = $_FILES['image']['tmp_name'];
                $dest_path = "../images/food/" . $image_name;
                $upload = move_uploaded_file($src_path, $dest_path);

                if ($upload == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to Upload New Image.</div>";
                    header('location:' . SITEURL . 'admin/manage-food.php');
                    die();
                }

                // Remove old image
                if ($current_image != "") {
                    $remove_path = "../images/food/" . $current_image;
                    unlink($remove_path);
                }
            } else {
                $image_name = $current_image;
            }

            // Update database
            $sql3 = "UPDATE tbl_food SET
                        title = '$title',
                        description = '$description',
                        price = $price,
                        image_name = '$image_name',
                        category_id = '$category',
                        featured = '$featured',
                        active = '$active'
                        WHERE id=$id";

            $res3 = mysqli_query($conn, $sql3);

            // Redirect with success/failure message
            if ($res3 == true) {
                $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to Update Food.</div>";
            }
            header('location:' . SITEURL . 'admin/manage-food.php');
        }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>
