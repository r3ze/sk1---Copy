<?php
session_start(); // Start the session to access session variables

// Check if email is set in session
if (isset($_SESSION['session_id'])) {
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


  if (isset($_GET['purok'])) {
    $purok_name = $_GET['purok'];
    $email = $_SESSION['session_id'];

    // Fetch additional information based on the email
    $sql = "SELECT street, municipal, province, first_name, middle_name, last_name FROM barangay WHERE session_id = '$email'";
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


    $sql = "SELECT * FROM youth_barangay WHERE session_id = '$email' AND barangay_purok_no = '$purok_name'";
    $result = $conn->query($sql);
  }
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

  <title>Youth List</title>
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

  <!-- ======= Header ======= -->
  <?php
  include('./include/barangay-header.php')
  ?>

<?php
include("./include/barangay-sidebar.php");
  ?>
 


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Resident List</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Youth Resident List</li>
          <div>
            <label><input type="checkbox" id="osyCheckbox"> OSY</label>
            <label><input type="checkbox" id="wpCheckbox"> WP</label>
            <label><input type="checkbox" id="ypCheckbox"> YP</label>
            <label><input type="checkbox" id="pwdCheckbox"> PWD</label>
          </div>
          <script>
            // Function to sort table based on checkbox states and hide rows with "No" values
            function sortAndHideTable() {
              var table = document.querySelector(".datatable");
              var rows = Array.from(table.querySelectorAll("tbody tr"));

              rows.forEach(function(row) {
                var osyValue = row.querySelector("td:nth-child(12)").textContent.trim();
                var wpValue = row.querySelector("td:nth-child(13)").textContent.trim();
                var ypValue = row.querySelector("td:nth-child(14)").textContent.trim();
                var pwdValue = row.querySelector("td:nth-child(15)").textContent.trim();

                var showRow = true;

                // Check if any checkbox is checked and has "No" value in the database
                if (document.getElementById("osyCheckbox").checked && osyValue === "No") {
                  showRow = false;
                }
                if (document.getElementById("wpCheckbox").checked && wpValue === "No") {
                  showRow = false;
                }
                if (document.getElementById("ypCheckbox").checked && ypValue === "No") {
                  showRow = false;
                }
                if (document.getElementById("pwdCheckbox").checked && pwdValue === "No") {
                  showRow = false;
                }

                // Hide row if it should not be shown
                if (!showRow) {
                  row.style.display = "none";
                  return; // Exit the function for this row
                }

                // Show row if any checkbox is checked and has "Yes" value
                if (document.getElementById("osyCheckbox").checked && osyValue !== "Yes") {
                  showRow = false;
                }
                if (document.getElementById("wpCheckbox").checked && wpValue !== "Yes") {
                  showRow = false;
                }
                if (document.getElementById("ypCheckbox").checked && ypValue !== "Yes") {
                  showRow = false;
                }
                if (document.getElementById("pwdCheckbox").checked && pwdValue !== "Yes") {
                  showRow = false;
                }

                // Hide row if it should not be shown
                if (!showRow) {
                  row.style.display = "none";
                  return; // Exit the function for this row
                }

                // Show row if it should be shown
                row.style.display = "";
              });

              // After hiding rows, sort the displayed rows
              var displayedRows = Array.from(table.querySelectorAll("tbody tr:not([style='display: none;'])"));
              displayedRows.sort(function(a, b) {
                var aValue = 0;
                var bValue = 0;

                if (document.getElementById("osyCheckbox").checked) {
                  aValue += (a.querySelector("td:nth-child(14)").textContent === "Yes") ? 1 : 0;
                  bValue += (b.querySelector("td:nth-child(14)").textContent === "Yes") ? 1 : 0;
                }
                if (document.getElementById("wpCheckbox").checked) {
                  aValue += (a.querySelector("td:nth-child(14)").textContent === "Yes") ? 1 : 0;
                  bValue += (b.querySelector("td:nth-child(14)").textContent === "Yes") ? 1 : 0;
                }
                if (document.getElementById("ypCheckbox").checked) {
                  aValue += (a.querySelector("td:nth-child(14)").textContent === "Yes") ? 1 : 0;
                  bValue += (b.querySelector("td:nth-child(14)").textContent === "Yes") ? 1 : 0;
                }
                if (document.getElementById("pwdCheckbox").checked) {
                  aValue += (a.querySelector("td:nth-child(15)").textContent === "Yes") ? 1 : 0;
                  bValue += (b.querySelector("td:nth-child(15)").textContent === "Yes") ? 1 : 0;
                }

                return bValue - aValue;
              });

              // Reorder the table rows based on sorted array
              displayedRows.forEach(function(row) {
                table.querySelector("tbody").appendChild(row);
              });

              // Always display all rows when sorting
              displayedRows.forEach(function(row) {
                row.style.display = "";
              });

              // Hide pagination controls
              document.getElementById("pagination").style.display = "none";
            }

            // Event listeners for checkboxes
            document.getElementById("osyCheckbox").addEventListener("change", sortAndHideTable);
            document.getElementById("wpCheckbox").addEventListener("change", sortAndHideTable);
            document.getElementById("ypCheckbox").addEventListener("change", sortAndHideTable);
            document.getElementById("pwdCheckbox").addEventListener("change", sortAndHideTable);

            // Initial sorting and hiding based on checkbox states
            sortAndHideTable();
          </script>

        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">
        <div class="datatable-top">
          <div class="datatable-dropdown">
            <label>
              <select class="datatable-selector">
                <option value="5">5</option>
                <option value="10" selected>10</option> <!-- Set the selected attribute here -->
                <option value="15">15</option>
                <option value="-1">All</option>
              </select> entries per page
            </label>
          </div>
          <div class="datatable-top"></div>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              var selector = document.querySelector('.datatable-selector');
              var defaultPageSize = parseInt(selector.value); // Get the default page size

              // Function to handle the selection of entries per page
              selector.addEventListener('change', function() {
                var selectedValue = parseInt(this.value);
                var rows = document.querySelectorAll('.datatable-table tbody tr');

                // Hide all rows initially
                rows.forEach(function(row) {
                  row.style.display = 'none';
                });

                // Show selected number of rows per page
                if (selectedValue === -1) { // Show all rows if -1 is selected
                  rows.forEach(function(row) {
                    row.style.display = '';
                  });
                } else {
                  var currentPage = 1;
                  var rowCount = rows.length;
                  var pageCount = Math.ceil(rowCount / selectedValue);

                  // Update pagination buttons
                  var pagination = document.querySelector('.datatable-pagination-list');
                  pagination.innerHTML = '';

                  for (var i = 1; i <= pageCount; i++) {
                    var li = document.createElement('li');
                    li.className = 'datatable-pagination-list-item';
                    var button = document.createElement('button');
                    button.className = 'datatable-pagination-list-item-link';
                    button.setAttribute('data-page', i);
                    button.textContent = i;
                    button.addEventListener('click', function() {
                      currentPage = parseInt(this.getAttribute('data-page'));
                      showPage(currentPage, selectedValue);
                    });
                    li.appendChild(button);
                    pagination.appendChild(li);
                  }

                  showPage(currentPage, selectedValue);
                }
              });

              // Trigger change event to display 10 entries per page initially
              selector.dispatchEvent(new Event('change')); // Trigger change event after setting the value

              // Function to show the specified page
              function showPage(pageNumber, pageSize) {
                var startIndex = (pageNumber - 1) * pageSize;
                var endIndex = startIndex + pageSize;
                var rows = document.querySelectorAll('.datatable-table tbody tr');

                // Hide all rows initially
                rows.forEach(function(row, index) {
                  if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                  } else {
                    row.style.display = 'none';
                  }
                });
              }
            });
          </script>





          <div class="datatable-search">
            <input class="datatable-input" placeholder="Search..." type="search" title="Search within table">
          </div>
        </div>
        <div class="datatable-container">
          <table class="table datatable datatable-table">
            <thead>
              <tr>
                <th data-sortable="true" style="width: 11.256830601092895%;">
                  <button class="datatable-sorter">Full Name</button>
                </th>
                <th data-sortable="true" style="width: 11.256830601092895%;">
                  <button class="datatable-sorter">Purok</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">First Name</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">Middle Name</button>
                </th>
                <th hidden="" data-sortable="true" class="red">
                  <button class="datatable-sorter">Last Name</button>
                </th>
                <th data-sortable="true" style="width: 9.617486338797814%;">
                  <button class="datatable-sorter">Gender</button>
                </th>
                <th data-sortable="true" style="width: 18.688524590163937%;">
                  <button class="datatable-sorter">Contact No.</button>
                </th>
                <th data-type="date" data-format="YYYY/DD/MM" data-sortable="true" style="width: 11.147540983606557%;">
                  <button class="datatable-sorter">Birthdate</button>
                </th>
                <th data-sortable="true" style="width: 6.775956284153005%;">
                  <button class="datatable-sorter">Age</button>
                </th>
                <th data-sortable="true" style="width: 11.147540983606557%;">
                  <button class="datatable-sorter">Marital Status</button>
                </th>
                <th data-sortable="true" style="width: 10.382513661202186%;">
                  <button class="datatable-sorter">Religion</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">osy</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">ws</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">yp</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">pwd</button>
                </th>
                <th hidden="" data-sortable="true">
                  <button class="datatable-sorter">session_id</button>
                </th>
                <th data-sortable="true" style="width: 9.726775956284154%;">
                  <button class="datatable-sorter">Action</button>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              function calculateAge($birthdate)
              {
                // Calculate age based on the birthdate
                $birthDate = new DateTime($birthdate);
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthDate)->y;

                return $age;
              }

              // Check if there are any records fetched
              if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
                  echo "<td>" . $row["barangay_purok_no"] . "</td>";
                  echo "<td hidden>" . $row["first_name"] . "</td>";
                  echo "<td hidden>" . $row["middle_name"] . "</td>";
                  echo "<td hidden>" . $row["last_name"] . "</td>";
                  echo "<td>" . $row["gender"] . "</td>";
                  echo "<td>" . $row["contact_no"] . "</td>";
                  echo "<td>" . $row["birthdate"] . "</td>";
                  $age = calculateAge($row["birthdate"]);
                  echo "<td>" . $age . "</td>";

                  // Check if age is greater than 30 and trigger archiveRecord function
                  if ($age > 30) {
                    echo "<script>archiveRecord(" . $row["id"] . ");</script>";
                  }

                  echo "<td>" . $row["marital_status"] . "</td>";
                  echo "<td>" . $row["religion"] . "</td>";
                  echo "<td hidden>" . $row["osy"] . "</td>";
                  echo "<td hidden>" . $row["ws"] . "</td>";
                  echo "<td hidden>" . $row["yp"] . "</td>";
                  echo "<td hidden>" . $row["pwd"] . "</td>";
                  echo "<td hidden>" . $row["session_id"] . "</td>";

                  echo "<td><button onclick=\"editRecord('" . $row["id"] . "')\">Edit</button> <button onclick=\"archiveRecord('" . $row["id"] . "')\">Archive</button></td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='6'>No records found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="datatable-bottom">
          <div class="datatable-info">Showing 1 to 10 of 24 entries</div>
          <nav class="datatable-pagination">
            <ul class="datatable-pagination-list">
              <!-- <li class="datatable-pagination-list datatable-active">
                    <button data-page="1" class="datatable-pagination-list-item-link" aria-label="Page 1">‹</button>
                </li> -->
              <li class="datatable-pagination-list-item datatable-active">
                <button data-page="1" class="datatable-pagination-list-item-link" aria-label="Page 1">1</button>
              </li>
              <li class="datatable-pagination-list-item">
                <button data-page="2" class="datatable-pagination-list-item-link" aria-label="Page 2">2</button>
              </li>
              <li class="datatable-pagination-list-item">
                <button data-page="3" class="datatable-pagination-list-item-link" aria-label="Page 3">3</button>
              </li>
              <li class="datatable-pagination-list-item">
                <button data-page="2" class="datatable-pagination-list-item-link" aria-label="Page 2">›</button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <script>
        // Function to perform search
        function performSearch() {
          var input, filter, table, tr, td, i, txtValue;
          input = document.getElementsByClassName("datatable-input")[0];
          filter = input.value.toUpperCase();
          table = document.getElementsByClassName("datatable-table")[0];
          tr = table.getElementsByTagName("tr");

          // Loop through all table rows, and hide those who don't match the search query
          for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0]; // Assuming the name is in the first column
            if (td) {
              txtValue = td.textContent || td.innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
              } else {
                tr[i].style.display = "none";
              }
            }
          }
        }

        // Add event listener for input changes
        document.getElementsByClassName("datatable-input")[0].addEventListener('input', function() {
          performSearch();
        });

        // Add event listener for initial search
        window.addEventListener('DOMContentLoaded', function() {
          performSearch();
        });

        // Function to handle pagination
        function goToPage(pageNumber) {
          var rows = document.getElementsByClassName('datatable-table')[0].rows;
          var rowsPerPage = 10;
          var startIndex = (pageNumber - 1) * rowsPerPage;
          var endIndex = startIndex + rowsPerPage;

          for (var i = 0; i < rows.length; i++) {
            if (i >= startIndex && i < endIndex) {
              rows[i].style.display = "";
            } else {
              rows[i].style.display = "none";
            }
          }
        }

        // Add event listeners to pagination buttons
        var paginationButtons = document.querySelectorAll('.datatable-pagination-list-item-link');
        paginationButtons.forEach(function(button) {
          button.addEventListener('click', function() {
            var pageNumber = parseInt(button.getAttribute('data-page'));
            goToPage(pageNumber);
          });
        });

        // Show the first page initially
        goToPage(1);
      </script>



      <div id="editForm" style="display: none;">
        <!-- Edit Form will be displayed here -->
      </div>

      <script>
        function editRecord(id) {
          // AJAX request to fetch record details
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("editForm").innerHTML = this.responseText;
              document.getElementById("editForm").style.display = "block";
            }
          };
          xhttp.open("GET", "edit-youth.php?id=" + id, true);
          xhttp.send();
        }

        function archiveRecord(id) {
          // AJAX request to archive record
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              // Refresh the page after archiving
              window.location.reload();
            }
          };
          xhttp.open("GET", "archive-youth.php?id=" + id, true);
          xhttp.send();
        }
      </script>
      <script>
        // Function to calculate age
        function calculateAge(birthdate) {
          var birthDate = new Date(birthdate);
          var currentDate = new Date();
          var age = currentDate.getFullYear() - birthDate.getFullYear();

          // Check if the birthday has occurred for this year
          if (currentDate.getMonth() < birthDate.getMonth() || (currentDate.getMonth() === birthDate.getMonth() && currentDate.getDate() < birthDate.getDate())) {
            age--;
          }

          return age;
        }

        // Function to automatically archive records with age > 30 on page load
        window.onload = function() {
          var tableRows = document.querySelectorAll(".datatable tbody tr");

          for (var i = 0; i < tableRows.length; i++) {
            var birthdateCell = tableRows[i].querySelector("td:nth-child(8)");
            var age = calculateAge(birthdateCell.textContent);

            if (age > 30) {
              var idCell = tableRows[i].querySelector("td:last-child button:last-child").getAttribute("onclick");
              var id = idCell.match(/\d+/)[0]; // Extracting the ID from the onclick attribute
              archiveRecord(id);
            }
          }
        };

        // ... (Your existing functions remain unchanged)
      </script>
      </div>
      </div>

      </div>
      </div>
    </section>

  </main><!-- End #main -->
  <?php
include("./include/barangay-footer.php");
  ?>
 

<?php
// Close database connection
$conn->close();
?>