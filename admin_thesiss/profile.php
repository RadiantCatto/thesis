<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('security.php');
    //Database
include('displayUID.php');
include('database/database.php');
?>

<div class="container-fluid">

    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Card ID</th> <!-- New column header for cardID -->
                            <th>Name</th>
                            <th>Email</th>
                            <th>Points Earned</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $pdo = Database::connect();
                        $sql = 'SELECT * FROM user_register ORDER BY username ASC';
                        foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
                            echo '<td>'. $row['cardID'] . '</td>';
                            echo '<td>'. $row['username'] . '</td>';
                            echo '<td>'. $row['email'] . '</td>';
                            echo '<td>'. $row['earned'] . '</td>';
                            echo '<td>';
                            echo '</tr>';
                        }
                        Database::disconnect();
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