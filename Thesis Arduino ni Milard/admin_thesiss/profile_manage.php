<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
include('database/database.php');

    $Write="<?php $" . "cardID=''; " . "echo $" . "cardID;" . " ?>";
    file_put_contents('UIDContainer.php',$Write);

?>

<div class="modal fade" id="adduserprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code1.php" method="POST">
        <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="username" class="form-control" placeholder="Enter Name">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control checking_email" placeholder="Enter Email">
                <small class="error_email" style="color: red;"></small>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                    <script>
                        $(document).ready(function(){
                            $("#getUID").load("UIDContainer.php");
                            setInterval(function() {
                                $("#getUID").load("UIDContainer.php");
                            }, 500);
                        });
                    </script>
            <div class="form-group">
                <label>Card ID</label>
                <textarea name="cardID" id="getUID" placeholder="Please Scan your Card / Key Chain to display ID" rows="1" cols="1" required class="form-control-plaintext"></textarea>
            </div>
            <div class="form-group">
                <label>Points Earned</label>
                <input type="number" name="earned" class="form-control" placeholder="Enter Points Earned">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="user_registerbtn" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="container-fluid">
    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Profile > Account Management</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adduserprofile">
                    Add User Data
              </button>
              <?php
                $query = "SELECT * FROM user_register";
                $query_run = mysqli_query($connection, $query);
              ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Card ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Points Earned</th>
                            <th>EDIT</th>
                            <th>DELETE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($query_run) > 0) {
                            while($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                            <tr>
                                <td><?php echo $row['cardID']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['earned']; ?></td>
                                <td>
                                    <form action="profile_edit.php" method="post">
                                        <input type="hidden" name="edit_cardID" value="<?php echo $row['cardID']; ?>">
                                        <button type="submit" name="user_edit_btn" class="btn btn-success">EDIT</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="code1.php" method="post">
                                        <input type="hidden" name="delete_cardID" value="<?php echo $row['cardID']; ?>">
                                        <button type="submit" name="user_delete_btn" class="btn btn-danger">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                            } 
                        }
                        else {
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
