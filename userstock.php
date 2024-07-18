<?php

session_start();

if (isset($_SESSION['login'])) {
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];

?>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "bloodbank";

    $mysqli = new mysqli($servername, $username, $password, $db);

    if ($mysqli->connect_error) {
        die("Connection Failed " . $mysqli->connect_error);
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BloodBank - Stocks</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style2.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!--Script-->
        <script type="text/javascript">
            $(document).ready(function() {
                $("#request").click(function(e) {
                    e.preventDefault(); // Prevent default form submission
                    if ($("#mobile_no").val().length != 10) {
                        $("#add_err2").html('<div class="alert alert-danger"> <strong>Mobile Number</strong> must be 10 digits. </div>');
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        url: "makereq.php",
                        data: {
                            name: $("#name").val(),
                            bloodgroup: $("#bloodgroup").val(),
                            mobile_no: $("#mobile_no").val()
                        },
                        success: function(response) {
                            if (response === 'true') {
                                $("#add_err2").html('<div class="alert alert-success"> <strong>Request</strong> Sent. </div>');
                                setTimeout(function() {
                                    window.location.href = "userdashboard.php"; // Redirect after success
                                }, 1000); // Delay for 1 second
                            } else if (response === 'false') {
                                $("#add_err2").html('<div class="alert alert-danger"><strong>Request</strong> Not Sent </div>');
                            } else if (response === 'name') {
                                $("#add_err2").html('<div class="alert alert-danger">  <strong>Name</strong> is required.  </div>');
                            } else if (response === 'bg') {
                                $("#add_err2").html('<div class="alert alert-danger"> <strong>Blood Group </strong> is required. </div>');
                            } else if (response === 'mob') {
                                $("#add_err2").html('<div class="alert alert-danger"><strong>Mobile Number </strong> is required.  </div>');
                            } else {
                                $("#add_err2").html('<div class="alert alert-danger"> <strong>Error</strong> processing request. Please try again.  </div>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText); // Log any error messages to console
                            $("#add_err2").html('<div class="alert alert-danger">An error occurred while processing your request. Please try again.</div>');
                        },
                        beforeSend: function() {
                            $("#add_err2").html("loading...");
                        }
                    });
                });
            });
        </script>

        <style>
            .container {
                padding-top: 20px;
            }

            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                background-color: transparent;
            }

            .table-hover tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table th,
            .table td {
                padding: 0.75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .col-lg-12,
            .col-lg-12.order-sm-2,
            .col-lg-12.order-sm-12,
            .col {
                position: relative;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-control {
                display: block;
                width: 100%;
                height: calc(1.5em + 0.75rem + 2px);
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .form-control:focus {
                color: #495057;
                background-color: #fff;
                border-color: #80bdff;
                outline: 0;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .btn {
                display: inline-block;
                font-weight: 400;
                color: #212529;
                text-align: center;
                vertical-align: middle;
                user-select: none;
                background-color: transparent;
                border: 1px solid transparent;
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                line-height: 1.5;
                border-radius: 0.25rem;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .btn-primary {
                color: #fff;
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-primary:hover {
                color: #fff;
                background-color: #0069d9;
                border-color: #0062cc;
            }

            .btn-primary:focus,
            .btn-primary.focus {
                color: #fff;
                background-color: #0069d9;
                border-color: #0062cc;
                box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
            }

            .btn-primary.disabled,
            .btn-primary:disabled {
                color: #fff;
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-primary:not(:disabled):not(.disabled):active,
            .btn-primary:not(:disabled):not(.disabled).active,
            .show>.btn-primary.dropdown-toggle {
                color: #fff;
                background-color: #0062cc;
                border-color: #005cbf;
            }

            .btn-primary:not(:disabled):not(.disabled):active:focus,
            .btn-primary:not(:disabled):not(.disabled).active:focus,
            .show>.btn-primary.dropdown-toggle:focus {
                box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
            }
        </style>

    </head>

    <body>
        <?php include "UserNavbar.php" ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 order-sm-2">
                    <?php
                    $query = "SELECT * FROM stock";
                    $result = $mysqli->query($query);
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-hover' border='1'>
                    <thead>
                        <tr>
                        <th scope='col'> Blood Group </th>
                        <th scope='col'> Stock </th>
                        <tr>
                    </thead>";

                    if ($result->num_rows > 0) {
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['bloodgroup'] . "</td>";
                            echo "<td>" . $row['stock'] . "</td>";
                            echo "</tr>";
                        }
                        echo "<tbody";
                    }
                    echo "</table>";
                    echo "</div>";
                    ?>
                </div>
                <div class="col order-sm-12">
                    <h1 class="intro-text text-center">Request Form</h1>
                    <div id="add_err2"></div>
                    <form role="form" method="post">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label class="lb">Name</label>
                                <input type="text" id="name" name="name" maxlength="30" class="form-control">
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="lb">Blood Group</label>
                                <select id="bloodgroup" name="bloodgroup" class="form-control">
                                    <option selected>Choose...</option>
                                    <option value="A ve">A+</option>
                                    <option value="A -ve">A-</option>
                                    <option value="B ve">B+</option>
                                    <option value="B -ve">B-</option>
                                    <option value="AB ve">O+</option>
                                    <option value="AB -ve">O-</option>
                                    <option value="O ve">AB+</option>
                                    <option value="O -ve">AB-</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="lb">Mobile Number</label>
                                <input type="text" id="mobile_no" name="mobile_no" maxlength="25" class="form-control">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary" id="request">Make Request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php require_once "footer.php" ?>
    </body>

<?php
} else {
    header("location:index.php");
}


?>