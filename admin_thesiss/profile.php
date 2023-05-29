<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('security.php');
?>

<div class="container-fluid">

    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <?php
                $query = "SELECT * FROM user_register";
                $query_run = mysqli_query($connection, $query);
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Card ID</th> <!-- New column header for cardID -->
                            <th>Email</th>
                            <th>Points Earned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                                <tr>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['cardID']; ?></td> <!-- Display cardID value -->
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['earned']; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "No Record Found";
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>