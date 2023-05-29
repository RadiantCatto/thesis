<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('security.php');
?>

<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">EDIT USER DETAILS</h6>
        </div>
        <div class="card-body">
            <?php
            if (isset($_POST['user_edit_btn'])) {
                $id = $_POST['user_edit_id'];

                $query = "SELECT * FROM user_register WHERE user_id='$id' ";
                $query_run = mysqli_query($connection, $query);

                foreach ($query_run as $row) {
                    ?>
                    <form action="code1.php" method="POST">
                        <input type="hidden" name="user_edit_id" value="<?php echo $row['user_id'] ?>">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="edit_username" value="<?php echo $row['username'] ?>" class="form-control" placeholder="Enter Username">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="edit_email" value="<?php echo $row['email'] ?>" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label>Card ID</label>
                            <input type="text" name="edit_cardID" value="<?php echo $row['cardID'] ?>" class="form-control" placeholder="Enter Card ID">
                        </div>
                        <div class="form-group">
                            <label>Points Earned</label>
                            <input type="number" name="edit_earned" value="<?php echo $row['earned'] ?>" class="form-control" placeholder="Enter Points Earned">
                        </div>
                        <a href="profile_manage.php" class="btn btn-danger">CANCEL</a>
                        <button type="submit" name="user_updatebtn" class="btn btn-primary">Update</button>
                    </form>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
