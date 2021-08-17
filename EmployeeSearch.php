<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/action.php";


if(isset($_POST['searchEmployee']))
{
$searchFname = $_POST['searchFname'];
$searchLname = $_POST['searchLname'];
$searchNumber = $_POST['searchNumber'];

$where = "";
$title = "Employees";

if($searchFname != "")
{

    $where = $where." FirstName LIKE '%$searchFname%'";

}

if($searchFname != "" && $searchLname != "") $where = $where. ' AND ';

if($searchLname != "")
{
  $where = $where." LastName LIKE '%$searchLname%'";
}
if($searchNumber != "")
{
   $where = $where." Employee_no LIKE '%$searchNumber%'";
}

$employeeSearch = "SELECT * FROM Employee WHERE $where ORDER BY Employee.Employee_ID";
$queryEmployees = $databaseObject->connect()->query($employeeSearch);


}

?>

<!DOCTYPE HTML>
<html>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php"; ?>

<body class="body">
<div class="container">
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar.php"; ?>
<main>
<div class="main__container">
            
            <table id="tb_table">
                <thead>
                <th>Employee no:</th>
                    <th>Name:</th>
                    <th>Location</th>
                    <th>Gender</th>
                    <th>Salary</th>
                    <th>Start Date</th>
                    <th colspan="2">Action</th>
                </thead>
                <tbody>
                <?php
                    // calling viewMethod() method
                    
                    foreach($queryEmployees as $row){
                        // breaking point
                        ?>
                        <tr>
                            <td><?php echo $row['Employee_no']; ?></td>
                            <td><?php echo $row['FirstName'].' '.$row['LastName'];?></td>
                            <td><?php echo $row['Location'];?></td>
                            <td><?php echo $row['Gender'];?></td>
                            <td><?php echo $row['Salary'];?></td>
                            <td><?php echo $row['startDate'];?></td>      
                            <td>
                                <a class="edit_btn" href="currentEmployees.php?emplUpdate=1&id=<?php echo $row["Employee_ID"]; ?>">Edit</a>
                            </td>
                            <td>
                                <a class="del_btn" href="includes/action.php?emplDelete=1&id=<?php echo $row["Employee_ID"]; ?>">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>
</div>
</main>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php"; ?>
</div>
</body>

</html>