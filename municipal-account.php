<?php
session_start(); // Start the session to access session variables

// Check if email is set in session
if (isset($_SESSION['email'])) {
  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "sk_database";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }



  $email = $_SESSION['email'];
  
  $username = $_SESSION['username'];

  // Use a prepared statement for security
  $sql1 = "SELECT * FROM municipality WHERE username = ?";
  $stmt1 = $conn->prepare($sql1);
  $stmt1->bind_param("s", $username);

  if ($stmt1->execute()) {
      $result1 = $stmt1->get_result();
      if ($result1->num_rows > 0) {
          $row1 = $result1->fetch_assoc();
          $municipal = $row1['municipal']; // Fix: Access $row1 correctly

          // You can display the email wherever you want on the page
          echo "<span>" . $municipal . "</span>";
      } else {
          // Handle the case where no matching username is found
          echo "<span>Municipal not found</span>";
      }
  }


  // Fetch additional information based on the email
  $sql = "SELECT street, municipal, province, first_name, middle_name, last_name FROM barangay WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $street = $row['street'];
    $municipal = $row['municipal'];
    $province = $row['province'];
    $first_name = $row['first_name'];
    $middle_name = $row['middle_name'];
    $last_name = $row['last_name'];
  }


  // Select with valid_id from database

  $sql = "SELECT * FROM barangay WHERE municipal = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $municipal); // Assuming $municipal is a string 
$stmt->execute();
$result2 = $stmt->get_result(); 
  // end  Select with valid_id from database



} else {
  // If email is not set in session, redirect to login page
  header("Location: barangay-login.php");
  exit();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Municipal Management Account</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

<?php
include("./include/municipal-header.php")
?>


<?php
include("./include/municipal-sidebar.php")
?>

  <main id="main" class="main">

  

    <div class="pagetitle">
      <h1>Barangay Account List</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="municipal-dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Manage Account</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row"> <?php
        // Check if email is set in session
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            // Use a prepared statement for security
            $sql1 = "SELECT * FROM municipality WHERE username = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("s", $username);

            if ($stmt1->execute()) {
                $result1 = $stmt1->get_result();
                if ($result1->num_rows > 0) {
                    $row1 = $result1->fetch_assoc();
                    $municipal = $row1['municipal']; // Fix: Access $row1 correctly

                    // You can display the email wherever you want on the page
                    echo "<span>" . $municipal . "</span>";
                } else {
                    // Handle the case where no matching username is found
                    echo "<span>Municipal not found</span>";
                }
            } else {
                // Handle database execution error
                echo "<span>Error retrieving municipal</span>";
            }

            $stmt1->close();  // Close the statement
        }
        ?>

        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Barangay Account List</h5>

              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Barangay</th>
                    <th>Purok Total</th>
                    <th>Resident Total</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Contact No.</th>
                    <th>ValidId</th>
                    <!-- <th>Status</th> -->
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Check if there are any records fetched
                  if ($result2->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result2->fetch_assoc()) {
                      echo "<tr>";
                      // Make barangay clickable
                      echo "<td><a href='#' onclick='showBarangayInfo(\"" . $row["street"] . "\", \"" . $row["municipal"] . "\", \"" . $row["province"] . "\")' data-bs-toggle='modal' data-bs-target='#barangayInfoModal'>" . $row["street"] . " " . $row["municipal"] . ", " . $row["province"] . "</a></td>";


                      // echo "<td>" . $row["street"] . ' ' . $row["street"] . "</td>";

                      $street = $row["street"];
                      $municipal = $row["municipal"];

                      echo "<td>";
                      // i want to show in this the purok count select from the table barangay_purok where street and municipality is

                      // Query to fetch Purok count
                      $purokCountQuery = "SELECT COUNT(*) as purok_count FROM barangay_purok WHERE street = '$street' AND municipal = '$municipal'";
                      $purokCountResult = $conn->query($purokCountQuery); // Assuming $conn is your database connection

                      if ($purokCountResult->num_rows > 0) {
                        $countRow = $purokCountResult->fetch_assoc();
                        echo $countRow['purok_count'];
                      } else {
                        echo "0"; // Display 0 if no Purok found
                      }


                      echo "</td>";

                      echo "<td>";
                      // i want to show in this the purok count select from the table barangay_purok where street and municipality is

                      // Query to fetch Purok count
                      $purokCountQuery = "SELECT COUNT(*) as resident_count FROM youth_barangay WHERE street = '$street' AND municipal = '$municipal'";
                      $purokCountResult = $conn->query($purokCountQuery); // Assuming $conn is your database connection

                      if ($purokCountResult->num_rows > 0) {
                        $countRow = $purokCountResult->fetch_assoc();
                        echo $countRow['resident_count'];
                      } else {
                        echo "0"; // Display 0 if no Purok found
                      }


                      echo "</td>";

                      echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
                      echo "<td>" . $row["gender"] . "</td>";
                      echo "<td>" . $row["contact_no"] . "</td>";
                      // Display image
                      echo '<td><img src="uploads/' . $row["valid_id"] . '" alt="Valid ID" class="valid-id-img" style="max-width: 100px; max-height: 100px;"></td>';
                      // echo "<td>" . $row["status"] . "</td>";
                      // echo "<td><button onclick=\"editRecord('" . $row["id"] . "')\">Edit</button> </td>";
                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="barangayInfoModal" tabindex="-1" aria-labelledby="barangayInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="barangayInfoModalLabel">Barangay Information</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p id="purokCount"></p>
              <p id="residentCount"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Include jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

      <script>
        // Function to show barangay information modal
        function showBarangayInfo(barangayName) {
          // AJAX request to fetch number of puroks
          $.ajax({
            url: 'fetch-purok-count.php', // Change the URL to the script that fetches the purok count
            method: 'POST',
            data: {
              barangay: barangayName
            }, // Send the selected barangay name to the server
            success: function(response) {
              // Parse the response as JSON
              const data = JSON.parse(response);
              // Set modal content
              $('#purokCount').text("Number of Puroks: " + data.purokCount);
              $('#residentCount').text("Number of Residents: " + data.residentCount);
              // Set modal title to barangay name
              $('#barangayInfoModalLabel').text("Barangay Information - " + data.barangayName);

              // For Modal Buttons
              $('#exportButtons').remove();

              var exportButtonsHtml = `
                <div id="exportButtons" style="text-align: left;">
                    Export into: 
                    <button onclick="exportRecord('${data.id}','csv','${data.barangayName}','${data.purokCount}','${data.residentCount}')">CSV</button>
                    <button onclick="exportRecord('${data.id}', 'pdf','${data.barangayName}','${data.purokCount}','${data.residentCount}')">PDF</button>
                    <button onclick="exportRecord('${data.id}', 'json','${data.barangayName}','${data.purokCount}','${data.residentCount}')">JSON</button>
                    <button onclick="exportRecord('${data.id}', 'xlsx','${data.barangayName}','${data.purokCount}','${data.residentCount}')">XLSX</button>
                </div>
            `;
              $('.modal-body').append(exportButtonsHtml); // Append buttons to modal



            },
            error: function(xhr, status, error) {
              console.error(xhr.responseText);
              // Show error message or handle the error as needed
            }
          });
        }

        async function exportRecord(id, format, barangayName, purokCount, residentCount) {
          try {
            // AJAX request to fetch purok count data
            const response = await $.ajax({
              url: 'fetch-purok-count.php',
              method: 'POST',
              data: {
                barangay: barangayName
              }
            });

            // Parse the response as JSON
            const data = JSON.parse(response);


            // Stringify the data object back into a JSON string
            const jsonString = JSON.stringify(data);

            // Prompt the user for the file name, defaulting to the barangay name
            var fileName = prompt("Enter file name:", barangayName);

            // Make sure the user entered a file name
            if (fileName !== null && fileName !== "") {
              // Send the file name along with the export request
              const exportResponse = await $.ajax({
                url: 'export.php',
                method: 'POST',
                data: {
                  fileName: fileName,
                  format: format,
                  barangayName: barangayName,
                  jsonString: jsonString // Pass the jsonString as a parameter
                }
              });

              // Handle success (e.g., display a success message)
              alert("File exported successfully!");
              const blob = new Blob([exportResponse]);

              // Create a link element to initiate download
              const link = document.createElement('a');
              link.href = URL.createObjectURL(blob);
              link.download = fileName + '.' + format;

              // Append the link to the document body and trigger a click event
              document.body.appendChild(link);
              link.click();

              // Cleanup: remove the link from the document body and revoke the blob URL
              document.body.removeChild(link);
              URL.revokeObjectURL(link.href);
            }
          } catch (error) {
            // Log the error object to the console for debugging
            console.error("Error exporting file:", error);
            // Display a generic error message
            alert("Error exporting file. See console for details.");
          }
        }
      </script>


    </section>


  </main><!-- End #main -->


  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>LSPU BSIT- 3A_WAM</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="#">3A WAM</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>