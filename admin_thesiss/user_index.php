<?php
include('includes/user_header.php');
include('includes/user_navbar.php');

// Database
include('database/database.php');
?>

<div class="container-fluid">
    <!-- DataTables -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="text-align: center;">User Details</h6>
            <h6 class="m-0 font-weight-bold text-primary" style="text-align: center;" id="blink">
                <font color="black">Please Scan Tag to Display ID or User Data</font>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <p id="getUID" hidden></p>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Card ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Points Earned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="cardID">-------------------</td>
                            <td id="username">-------------------</td>
                            <td id="email">-------------------</td>
                            <td id="earned">-------------------</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
?>

<script>
    var blink = document.getElementById('blink');
    setInterval(function () {
        blink.style.opacity = (blink.style.opacity == 0 ? 1 : 0);
    }, 750);

    let getLogin = () => {
        let xml = new XMLHttpRequest();
        xml.onreadystatechange = () => {
            if (xml.readyState == 4 && xml.status == 200) {
                console.log(xml.responseText); // Add this line to log the response
                let result;
                try {
                    result = JSON.parse(xml.responseText);
                } catch (error) {
                    console.error("Error parsing JSON response:", error);
                    return;
                }
                if (result.status == 'no login' || !result.data || !result.data.cardID) {
                    console.log(result.status);
                    document.querySelector('#cardID').innerHTML = '-------------------';
                    document.querySelector('#username').innerHTML = '-------------------';
                    document.querySelector('#email').innerHTML = '-------------------';
                    document.querySelector('#earned').innerHTML = '-------------------';
                    document.querySelector('#blink').innerHTML = '<font color="red">Error: Need registration</font>';
                } else {
                    console.log(result.data);
                    document.querySelector('#cardID').innerHTML = result.data.cardID;
                    document.querySelector('#username').innerHTML = result.data.username || 'N/A';
                    document.querySelector('#email').innerHTML = result.data.email || 'N/A';
                    document.querySelector('#earned').innerHTML = result.data.earned || '0.00';
                }
            }
        };
        xml.open("POST", "scripts/get-login.php");
        xml.send();
    };

    setInterval(() => {
        getLogin();
    }, 1000);
</script>