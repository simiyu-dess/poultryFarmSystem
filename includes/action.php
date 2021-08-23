<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/database.php";
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/functions.php";

    class CrudOperation extends Database{
        // Insertion method 
        public function insertionMethod($table, $fields){
            // "INSERT INTO table_name (, , ) VALUES ('','')";
            $sql = "";
            $sql .="INSERT INTO " . $table;
            $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
            $sql .= "('".implode("','", array_values($fields))."')";
            // Execute the query
            $query = $this->connect()->query($sql);
            if($query){
                return true;
            }
        }
        // Method to Fetching data from the database
        public function viewMethod($table){
            // Writing the query
            $sql = "SELECT * FROM " . $table;
            $array = array();
            // Query execution
            $query = $this->connect()->query($sql);
            while($row = mysqli_fetch_assoc($query)){
                $array[] = $row;
            }
            return $array;
        }
        // Method to edit data 
        public function selectMethod($table,$where){
            $sql = "";
            $condition = "";
            foreach($where as $key => $value){
                // id = '5' AND FirstName = 'somename'
                // Concatenate the condition to dynamically generate id when edit button is clicked
                $condition .= $key . "='" . $value . "' AND ";
            }
            // Remove the last 5 characters from the condition
            $condition = substr($condition, 0, -5);
            // SELECT query
            $sql .= "SELECT * FROM " .$table. " WHERE " . $condition;
            // Execute SELECT query
            $query = $this->connect()->query($sql);
            $row = mysqli_fetch_array($query);
            return $row;           
        }
        public function selectMethodGreater($table, $where)
        {
            $sql = "";
            $condition = "";

            foreach($where as $key => $value)
            {
                $condition .= $key .">" .$value. " AND ";
            }
            $condition = substr(0,-5);
            $sql .= "SELECT * FROM ". $table. " WHERE ". $condition;

            $query = $this->connect()->query($sql);
            $row = mysqli_fetch_assoc($query);
            return $row;
        }
        // Method to update data
        public function updateMethod($table, $where, $fields){
            $sql = "";
            $condition = "";
            foreach($where as $key => $value){
                // id = '5' AND FirstName = 'somename'
                // Concatenate the condition to dynamically generate id when edit button is clicked
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            foreach($fields as $key => $value){
                // UPDATE table SET FirstName = '', LastName = '' WHERE id = '';
                $sql .= $key . "='" . $value . "', ";
            }
            // Remove extra , and space from the sql query above
            $sql = substr($sql, 0, -2);
            // Full/concatenated query to be executed
            $sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;
            // Execute the query
            if($query = $this->connect()->query($sql)){
                return true;
            }
        }
        // Deletion method
        public function deleteMethod($table, $where){
            $sql = "";
            $condition = "";
            foreach($where as $key => $value){
                $condition .= $key . "='" . $value . "' AND ";
            }
            $condition = substr($condition, 0, -5);
            $sql = "DELETE FROM " . $table . " WHERE " . $condition;
            if($query = $this->connect()->query($sql)){
                return true;
            }
        }
    }
    $databaseObject = new Database();
    function getUserName($userid)
    {
        $db = new Database();
        $sql = "SELECT Username FROM User WHERE User_ID = $userid";
        $query = $db->connect()->query($sql);
        $username = mysqli_fetch_array($query);
        return $username['Username'];

    }
    $adminObject = new CrudOperation();
    //handle the saving of the admin user
    if(isset($_POST["setup_admin"]))
    {
        $myArray = array(
            "Username" => sanitize($_POST['Username']),
            "Password" => password_hash($_POST['Password'], PASSWORD_DEFAULT),
            "Ugroup_ID" => "1",
            "Employee_ID" => "0",
            "setupDate" => date('Y-m-d')
        );
        if($adminObject->insertionMethod("User",$myArray))
        {
            header('Location:index.php');
        }
    }
    $userObject = new CrudOperation();
    //handle the saving of the user
    if(isset($_POST["save_User"]))
    {
        $myArray = array(
            "Username" => sanitize($_POST['username']),
            "Password" => password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT),
            "Ugroup_ID" => sanitize($_POST['ugroup']),
            "Employee_ID" => sanitize($_POST['employee_id']),
            "setupDate" => date('Y-m-d'),
            "Deactivate" => 0

        );
        if($userObject->insertionMethod("User", $myArray))
        {
            $_SESSION['msg'] = "User inserted successfully";
            header("location: ../setUser.php");
        }
    }

    if(isset($_POST["edit_User"]))
    {
        $deactivate_code = 0;
        if(isset($_POST['deactivate'])) $deactivate_code = 1;
        $id = $_POST['user_id'];
        $where = array("User_ID" => $id);
        $myArray= array(
            "Username" => sanitize($_POST['username']),
            "Password" => password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT),
            "Ugroup_ID" => sanitize($_POST['ugroup']),
            "Employee_ID" => sanitize($_POST['employee_id']),
            "setupDate" => date('Y-m-d'),
            "Deactivate" => $deactivate_code
        );

        if($userObject->updateMethod("User",$where,$myArray))
        {
            $_SESSION['msg'] = "User Edited Successfully";
            header("location: ../setUser.php");
        }
    }
    if(isset($_GET['delete_User']))
    {
        $id = $_GET['delete_User'] ?? null;
        $where = array("User_ID" => $id);

        if($userObject->deleteMethod("User", $where))
        {
            $_SESSION['msg'] = "User record deleted succeessfully";
            header("location: ../setUser.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the user record!";
            header('Location:../setUser.php');
        }
    }
  

    $ugroupObject = new CrudOperation();
    if(isset($_POST['save_Ugroup'])){
        
		$ugroup_admin =0;
        $ugroup_medicine = 0;
        $ugroup_disease = 0;
        $ugroup_feeds = 0;
        $ugroup_sales = 0;
        $ugroup_purchases = 0;
        $ugroup_eggs = 0;
        $ugroup_birds = 0;
        $ugroup_action = 0;
		$ugroup_id = $_POST['ugroup_id'];
		$ugroup_name = $_POST['ugroup_name'];
		if(isset($_POST['ugroup_admin'])) $ugroup_admin = '1';
		if(isset($_POST['ugroup_medicine'])) $ugroup_medicine = '1';
		if(isset ($_POST['ugroup_disease'])) $ugroup_disease = '1';
		if(isset ($_POST['ugroup_feeds'])) $ugroup_feeds ='1';
        if(isset ($_POST['ugroup_purchases'])) $ugroup_purchases ='1';
        if(isset ($_POST['ugroup_sales'])) $ugroup_sales = '1';
        if(isset ($_POST['ugroup_birds'])) $ugroup_birds = '1';
        if(isset ($_POST['ugroup_eggs'])) $ugroup_eggs = '1';
        if(isset($_POST['ugroup_edit&delete'])) $ugroup_action = '1';
		$date = date('Y-m-d');

        $myArray = array(
            "Ugroup_name" => $ugroup_name,
            "Ugroup_admin" => $ugroup_admin,
            "Ugroup_birds" => $ugroup_birds,
            "Ugroup_sales" => $ugroup_sales,
            "Ugroup_purchase" => $ugroup_purchases,
            "Ugroup_eggs" => $ugroup_eggs,
            "Ugroup_medicine" => $ugroup_medicine,
            "Ugroup_feeds" => $ugroup_feeds,
            "Ugroup_action" => $ugroup_action
            
        );
        if($ugroupObject->insertionMethod("Ugroup", $myArray))
        {
            $_SESSION['msg'] = "Ugroup record inserted successfully";
            header('Location: ../setUgroup.php');
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert the ugroup record";
            header('Location: ../setUgroup.php');
        }

    }
    if(isset($_POST['edit_Ugroup'])){
        
		$ugroup_admin =0;
        $ugroup_medicine = 0;
        $ugroup_disease = 0;
        $ugroup_feeds = 0;
        $ugroup_sales = 0;
        $ugroup_purchases = 0;
        $ugroup_birds = 0;
        $ugroup_eggs= 0;
        $ugroup_action = 0;
		$ugroup_id = $_POST['ugroup_id'];
		$ugroup_name = $_POST['ugroup_name'];
		if(isset ($_POST['ugroup_admin'])) $ugroup_admin = '1';
		if(isset ($_POST['ugroup_medicine'])) $ugroup_medicine = '1';
		if(isset ($_POST['ugroup_disease'])) $ugroup_disease = '1';
		if(isset ($_POST['ugroup_feeds'])) $ugroup_feeds ='1';
        if(isset ($_POST['ugroup_purchases'])) $ugroup_purchases ='1';
        if(isset ($_POST['ugroup_sales'])) $ugroup_sales = '1';
        if(isset ($_POST['ugroup_birds'])) $ugroup_birds = '1';
        if(isset ($_POST['ugroup_eggs'])) $ugroup_eggs = '1';
        if(isset($_POST['ugroup_edit&delete'])) $ugroup_action = '1';
		$date = date('Y-m-d');

        $where = array("Ugroup_ID" => $ugroup_id);

        $myArray = array(
            "Ugroup_name" => $ugroup_name,
            "Ugroup_admin" => $ugroup_admin,
            "Ugroup_birds" => $ugroup_birds,
            "Ugroup_sales" => $ugroup_sales,
            "Ugroup_purchase" => $ugroup_purchases,
            "Ugroup_medicine" => $ugroup_medicine,
            "Ugroup_eggs" => $ugroup_eggs,
            "Ugroup_feeds" => $ugroup_feeds,
            "Ugroup_action" => $ugroup_action
            
        );
        if($ugroupObject->updateMethod("Ugroup", $where, $myArray))
        {
            $_SESSION['msg'] = "User record Edited successfully";
            header('Location: ../setUgroup.php');
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the User group record";
            header('Location: ../setUgroup.php'); 
        }

    }
    if(isset($_GET['delete_Ugroup']))
    {
        $id = $_GET['delete_Ugroup'];
        $sql_group = "SELECT * FROM User WHERE Ugroup_ID = $id";
        $query = $databaseObject->connect()->query($sql_group);

        if(mysqli_num_rows($query))
        {
            $_SESSION['error_msg'] = "Failed to delete, Have active account!";
            header('Location: ../setUgroup.php');
        }
        else
        {
        $where = array("Ugroup_Id" => $id);
        
        if($ugroupObject->deleteMethod("Ugroup", $where))
        {
            $_SESSION['msg'] = "Ugroup record deleted successfully";
            header('Location:../setUgroup.php');
        }
    }
    
    }

    $employeeObject = new CrudOperation();
    // Handle the save button for form submission
    if(isset($_POST["emplSave"])){
        
        $myArray = array(
            "Employee_no" => sanitize($_POST["EmployeeNumber"]),
            "FirstName" => sanitize($_POST["FirstName"]),
            "LastName" => sanitize($_POST["LastName"]),
            "Gender" => sanitize($_POST["Gender"]),
            "Location" => sanitize($_POST["Location"]),
            "Phone" => sanitize($_POST["Phone"]),
            "Job" => sanitize($_POST["Job"]),
            "Salary" => sanitize($_POST["Salary"]),
            "startDate" => sanitize($_POST["StartDate"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($employeeObject->insertionMethod("Employee", $myArray)){
            $_SESSION['msg'] = "employee added successfully!";
            header("location: ../newEmployee.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert the employee record";
            header('Location: ../newEmployee.php');
        }
    }
    // Handle the edit button for record editing
    if(isset($_POST["emplEdit"])){
        $id = $_POST["id"];
        
        $where = array("Employee_ID" => $id);
        
        if($_POST['EndDate'] != "")
        {
        $myArray = array(
            "Employee_no" => sanitize($_POST["EmployeeNumber"]),
            "FirstName" => sanitize($_POST["FirstName"]),
            "LastName" => sanitize($_POST["LastName"]),
            "Gender" => sanitize($_POST["Gender"]),
            "Location"=> sanitize($_POST["Location"]),
            "Phone" => sanitize($_POST["Phone"]),
            "Job" => sanitize($_POST["Job"]),
            "Salary" => sanitize($_POST["Salary"]),
            "startDate" => sanitize($_POST["StartDate"]),
            "endDate" => sanitize($_POST["EndDate"]),
            "User_ID" =>  sanitize($_SESSION['loguser'])
        );
    }
    else
    {
        
        $myArray = array(
            "Employee_no" => sanitize($_POST["EmployeeNumber"]),
            "FirstName" => sanitize($_POST["FirstName"]),
            "LastName" => sanitize($_POST["LastName"]),
            "Gender" => sanitize($_POST["Gender"]),
            "Location"=> sanitize($_POST["Location"]),
            "Phone" => sanitize($_POST["Phone"]),
            "Job" => sanitize($_POST["Job"]),
            "Salary" => sanitize($_POST["Salary"]),
            "startDate" => sanitize($_POST["StartDate"]),
            "User_ID" =>  sanitize($_SESSION['loguser'])
        );

    }
    
        if($employeeObject->updateMethod("Employee", $where, $myArray)){
            $_SESSION['msg'] = "Employee record edited successfully!";
            header("location: ../currentEmployees.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to update the employee record";
            header('Location: ../currentEmployees.php');
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["emplDelete"])){
        $id = $_GET["id"] ?? null;
        $sqlFeeds = "SELECT * FROM FeedConsumption WHERE Employee = $id";
        $queryFeeds = $databaseObject->connect()->query($sqlFeeds);

        $sqlMedicine = "SELECT * FROM MedicineUsage WHERE Employee = $id";
        $queryMedicine = $databaseObject->connect()->query($sqlMedicine);

        if(mysqli_num_rows($queryFeeds) || mysqli_num_rows($queryMedicine))
        {
            $_SESSION['error_msg'] = "Failed to delete, Employee have active records!";
            header('Location: ../currentEmployees.php');
        }
        $where = array("Employee_ID" => $id);
        if($employeeObject->deleteMethod("Employee", $where)){
            $_SESSION['msg'] = "employee record deleted successfully!";
            header("location: ../currentEmployees.php");
        }
        
    }

    // FEED CONSUMPTION

    // New object to call methods on FeedConsumption table
    $feedConsumptionObject = new CrudOperation();

    if(isset($_POST["feedconssave"])){
        $foreignID = $_POST["Employee"];
        $myArray = array(
            "ConsDate" => sanitize($_POST["ConsDate"]),
            "Quantity" => sanitize($_POST["Quantity"]),
            "Feed_name" => sanitize($_POST["feedname"]),
            "Employee" => $foreignID,
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($feedConsumptionObject->insertionMethod("FeedConsumption", $myArray)){
            $_SESSION['msg'] = "Feed insertion was successfull";
            header("location: ../feedConsumption.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert the Feed consumption record";
            header('Location: ../feedConsumption.php');
        }
    }

    // Handle the edit button for record editing
    if(isset($_POST["feedconsedit"])){
        $foreignID = $_POST["Employee"];
        $id = $_POST["id"];
        $where = array("FeedConsumption_ID" => $id);
        $myArray = array(
            "ConsDate" => sanitize($_POST["ConsDate"]),
            "Quantity" => sanitize($_POST["Quantity"]),
            "Feed_name" => sanitize($_POST["feedname"]),
            "Employee" => $foreignID,
            "User_ID" =>  $_SESSION['loguser']
        );
        if($feedConsumptionObject->updateMethod("FeedConsumption", $where, $myArray)){
            $_SESSION['msg'] = "feed record inserted successfully";
            header("location: ../feedConsumption.php?msg=Updated Successfully!");
        }
        else
        
        {
            $_SESSION['error_msg'] = "Failed to update the feed consumption record";
            header('Location: ../feedConsumption.php');
        }
    }
    // Check if delete button was triggered
    if(isset($_GET["feedconsdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("FeedConsumption_ID" => $id);
        if($feedConsumptionObject->deleteMethod("FeedConsumption", $where)){
            $_SESSION['msg'] = "feed record deleted successfully";
            header("location: ../feedConsumption.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the feed consumption record";
            header('Location: ../feedConsumption.php');
        }
    }

    // FEED PURCHASE

    // Create object for feed purchase
    $feedPurchaseObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["feedpurchsave"])){
        $myArray = array(
            "Date" => sanitize($_POST["Date"]),
            "Quantity" => sanitize($_POST["Quantity"]),
            "Feed_name" => sanitize($_POST['feedname']),
            "Price" => sanitize($_POST["Price"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($feedPurchaseObject->insertionMethod("FeedPurchase", $myArray)){
            $_SESSION['msg'] = "Insertion was successfull!";
            header("location: ../feedPurchase.php?msg=Insertion was successfull!");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert the feed purchase record";
            header('Location: ../feeefPurchase.php');
        }
    }
    // Handle the edit button for record editing
    if(isset($_POST["feedpurchedit"])){
        $id = $_POST["id"];
        $where = array("FeedPurchase_ID" => $id);
        $myArray = array(
            "Date" => sanitize($_POST["Date"]),
            "Feed_name" =>sanitize($_POST['feedname']),
            "Quantity" => sanitize($_POST["Quantity"]),
            "Price" => sanitize($_POST["Price"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        if($feedPurchaseObject->updateMethod("FeedPurchase", $where, $myArray)){
            $_SESSION['msg'] = "Record updated successfully!";
            header("location: ../feedPurchase.php?msg=Updated Successfully!");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to update the record!";
            header('Location: ../feedPurchase.php');
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["feedpurchdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("FeedPurchase_ID" => $id);
        if($feedPurchaseObject->deleteMethod("FeedPurchase", $where)){
            header("location: ../feedPurchase.php?msg=Record deleted successfully!");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the feed record";
            header('Location: ../feedPurchase.php');
        }
    }

    // BIRDS PURCHASE

    // Create object for feed purchase
    $birdsPurchaseObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["birdspurchsave"])){
        $myArray = array(
            "Date" => sanitize($_POST["date"]),
            "Bird_type" => sanitize($_POST['typeofbirds']),
            "NumberOfBirds" => sanitize($_POST["numberofbirds"]),
            "Price" => sanitize($_POST["price"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($birdsPurchaseObject->insertionMethod("BirdsPurchase", $myArray)){
            $_SESSION['msg'] = "Birds record inserted successfully!";
            header("location: ../birdsPurchase.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert the bird record";
            header('Location: ../birdsPurchase.php');
        }
    }
    // Handle the edit button for record editing
    if(isset($_POST["birdspurchedit"])){
        $id = sanitize($_POST["id"]);
        $where = array("BirdsPurchase_ID" => $id);
        $myArray = array(
            "Date" => sanitize($_POST["date"]),
            "Bird_type" => sanitize($_POST['typeofbirds']),
            "NumberOfBirds" => sanitize($_POST["numberofbirds"]),
            "Price" => sanitize($_POST["price"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        if($birdsPurchaseObject->updateMethod("BirdsPurchase", $where, $myArray)){
            $_SESSION['msg'] = "Birds record updated successfully!";
            header("location: ../birdsPurchase.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to edit the birds record";
            header('Location: ../birdsPurchase.php');
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["birdspurchdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("BirdsPurchase_ID" => $id);
        if($birdsPurchaseObject->deleteMethod("BirdsPurchase", $where)){
            $_SESSION['msg'] = "Bird record deleted successfully!";
            header("location: ../birdsPurchase.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the bird record";
            header('Location: ../birdsPurchase.php');
        }
    }

    // BIRDS MORTALITY

    // Create object for feed purchase
    $birdsMortalityObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["birdsmortsave"])){
        $myArray = array(
            "Date" => sanitize($_POST["date"]),
            "Bird_type" => sanitize($_POST['typeofbirds']),
            "Deaths" => sanitize($_POST["number"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($birdsMortalityObject->insertionMethod("BirdsMortality", $myArray)){
            $_SESSION['msg'] = "Added new record Successfully!";
            header("location: ../birdsMortality.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to add new record";
            header("Location: ../birdsMortality.php");
        }
    }
    // Handle the edit button for record editing
    if(isset($_POST["birdsmortedit"])){
        $id = sanitize($_POST["id"]);
        $where = array("BirdsMortality_ID" => $id);
        $myArray = array(
            "Date" => sanitize($_POST["date"]),
            "Bird_type" => sanitize($_POST['typeofbirds']),
            "Deaths" => sanitize($_POST["number"]),
            "User_ID" => $_SESSION['loguser']
        );
        if($birdsMortalityObject->updateMethod("BirdsMortality", $where, $myArray)){
            $_SESSION['msg'] = "Record updated successfully!";
            header("location: ../birdsMortality.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to update the record";
            header('Location: ../birdsMortality.php');
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["birdsmortdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("BirdsMortality_ID" => $id);
        if($birdsPurchaseObject->deleteMethod("BirdsMortality", $where)){
            $_SESSION['msg'] = "Rcord deleted successfully!";
            header("location: ../birdsMortality.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the record";
            header('Location: ../birdsMortality.php');
        }
    }
    //Create object for medicine purchase
    $medicineObject = new CrudOperation();
    

    //handle the medicine purchase
    if(isset($_POST["medpurchSave"]))
    {
        $myArray = array(
            "MedicineName" => sanitize($_POST['MedName']),
            "Quantity" => sanitize($_POST['Quantity']),
            "Date" => sanitize($_POST['Date']),
            "Price" => sanitize($_POST['Price']),
            "User_ID" => $_SESSION['loguser']
        );
        if($medicineObject->insertionMethod("MedicinePurchase",$myArray))
        {
            $_SESSION['msg'] = "record inserted successfully!";
            header("location: ../MedicinePurchase.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the Medicine Record!";
            header('Location: ../MedicinePurchase.php');
        }
    }
 //handle the medicine edit
     if(isset($_POST["medpurchUpdate"]))
     {
         $id = sanitize($_POST['id']);
         $where = array(
             "MedicinePurchase_ID" => $id
         );
         $myArray =array(
             "MedicineName" => sanitize($_POST['MedName']),
             "Quantity" => sanitize($_POST['Quantity']),
             "Date" => sanitize($_POST['Date']),
             "Price" => sanitize($_POST['Price']),
             "User_ID" =>  $_SESSION['loguser']
             
         );

         if($medicineObject->updateMethod("MedicinePurchase",$where,$myArray))
         {
             $_SESSION['msg'] = "medicine record updated successfully!";
             header("location: ../MedicinePurchase.php");
         }
         else
         {
             $_SESSION['error_msg'] = "Failed to delete the record";
             header('Location: ../MedicinePurchase.php');
         }
     }

     //handle the medicine
     if(isset($_GET['medpurchDelete']))
     {
         $id = $_GET['id'] ?? null;
         $where = array("MedicinePurchase_ID" => $id);
         if($medicineObject->deleteMethod("MedicinePurchase", $where))
         {
             $_SESSION['msg'] = "medicine record deleted successfully!";
             header(("location: ../MedicinePurchase.php"));
         }
         else
         {
             $_SESSION['error_msg'] = "Failed to delete the record";
             header('Location: ../medicinePurchase.php');
         }
     }
     //medicine Usage

     if(isset($_POST['medusageSave']))
     {
         $myArray = array(
             "MedicineName" => sanitize($_POST['MedName']),
             "Quantity" => sanitize($_POST['Quantity']),
             "Date" => sanitize($_POST['ConsumpDate']),
             "Employee" => sanitize($_POST['Employee_incharge']),
             "User_ID" =>  $_SESSION['loguser']

         );
         if($medicineObject->insertionMethod("MedicineUsage",$myArray))
         {
             $_SESSION['msg'] = "record inserted successfully!";
             header("location: ../MedicineConsumption.php");
         }
         else
         {
             $_SESSION['error_msg'] = "Failed to insert the record";
             header('Location: ../MedicineConsumption.php');
         }
     }

     if(isset($_POST['medusageUpdate']))
     {
         $id = $_POST['id'];
         $where = array(
             "MedicineUsage_ID" => $id

         );
         $myArray = array(
             "MedicineName" => sanitize($_POST['MedName']),
             "Quantity" => sanitize($_POST['Quantity']),
             "Date" => sanitize($_POST['ConsumpDate']),
             "Employee" => sanitize($_POST['Employee_incharge']),
             "User_ID" =>  $_SESSION['loguser']
         );
         if($medicineObject->updateMethod("MedicineUsage", $where, $myArray))
         {
             $_SESSION['msg'] = "record updated successfully";
             header("location: ../MedicineConsumption.php");
         }
         else
         {
             $_SESSION['error_msg'] = "Failed to update the record";
             header('Location: ../MedicineConsumption.php');
         }
     }

     if(isset($_GET['medusageDelete']))
     {
         $id = $_GET['id'] ?? null;
         $where =array("MedicineUsage_ID" => $id);
         if($medicineObject->deleteMethod("MedicineUsage", $where))
         {
             $_SESSION['msg'] = "Record deleted successfully";
             header("location: ../MedicineConsumption.php");
         }
         else
         {
             $_SESSION['error_msg'] = "Failed to delete the record";
             header('Location: ../MedicineConsumption.php');
         }
     }
    // EGG SALES

    // Create object for egg sales
    $salesObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["salessave"])){
        $numbeOfEggs = sanitize($_POST["NumberOfEggs"]);
        $sales_date = sanitize($_POST["Date"]);
        $Revenue = $eggPrice * $_POST["NumberOfEggs"];
        $myArray = array(
            "Sales_Date" => $_POST["Date"],
            "NumberOfEggs" =>$_POST["NumberOfEggs"],
            "Revenue" => $Revenue,
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($salesObject->insertionMethod("Sales", $myArray)){
            $_SESSION['msg'] = "record inserted succesfully!";
            header("location: ../sales.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert the sales record!";
            header('Location: ../sales.php');
        }
    }
    // Handle the edit button for record editing
    if(isset($_POST["salesedit"])){
        $id = $_POST["id"];
        $Revenue = $eggPrice * $_POST["NumberOfEggs"];
        $where = array("Sales_ID" => $id);
        $myArray = array(
            "Sales_Date" => sanitize($_POST["Date"]),
            "NumberOfEggs" => sanitize($_POST["NumberOfEggs"]),
            "Revenue" => $Revenue,
            "User_ID" =>  $_SESSION['loguser']
        );
        if($salesObject->updateMethod("Sales", $where, $myArray)){
            $_SESSION['msg'] = "Record updated successfully!";
            header("location: ../sales.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to update the sales record!";
            header('Location: ../sales.php');
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["salesdelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("Sales_ID" => $id);
        if($birdsPurchaseObject->deleteMethod("Sales", $where)){
            $_SESSION['msg'] = "record deleted successfully!";
            header("location: ../sales.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the sales object";
            header('Location: ../sales.php');
        }
    }

    // EGG PRODUCTION

    // Create object for egg production
    $productionObject = new CrudOperation();

    // Handle the save button for form submission
    if(isset($_POST["productionsave"])){
        $myArray = array(
            "Date" => sanitize($_POST["Date"]),
            "NumberOfEggs" => sanitize($_POST["NumberOfEggs"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        // Call the insertion method to add record to the database
        if($productionObject->insertionMethod("Production", $myArray)){
            $_SESSION['msg'] = "Egg insertion was successfull!";
            header("location: ../production.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to insert production record!";
            header('Location: ../production.php');
        }
    }
    // Handle the edit button for record editing
    if(isset($_POST["productionedit"])){
        $id = $_POST["id"];
        $where = array("Production_ID" => $id);
        $myArray = array(
            "Date" => sanitize($_POST["Date"]),
            "NumberOfEggs" => sanitize($_POST["NumberOfEggs"]),
            "User_ID" =>  $_SESSION['loguser']
        );
        if($productionObject->updateMethod("Production", $where, $myArray)){
            $_SESSION['msg'] = "Updated Successfully";
            header("location: ../production.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to edit production record!";
            header('Location: ../production.php');
        }
    }

    // Check if delete button was triggered
    if(isset($_GET["productiondelete"])){
        $id = $_GET["id"] ?? null;
        $where = array("Production_ID" => $id);
        if($productionObject->deleteMethod("Production", $where)){
            $_SESSION['msg'] = "Record was deleted successfully!";
            header("location: ../production.php");
        }
        else
        {
            $_SESSION['error_msg'] = "Failed to delete the production record!";
            header('Location: ../production.php');
        }
    }
    $feeObject = new CrudOperation();
    if(isset($_POST['saveFee']))
    {
     $where = array("Fee_type" => "eggFee");
        $myArray = array(
            "Fee_type" => "eggFee",
            "Fee_Amount" => sanitize($_POST['eggprice'])
        );
        if($feeObject->updateMethod("Fees",$where,$myArray))
        {
            $_SESSION['msg'] = "Fee record inserted successfully";
            header('Location: ../setFees.php');
        }
        else
        {
            $_SESSION['error_msg'] = "record insertion failed";
            header('Location: ../setFees.php');
        }
    }

    $incomeObject = new CrudOperation();

    if(isset($_POST['incomesave']))
    {
        $myArray = array(
            "Incomes_date" => sanitize($_POST['date']),
            "Incomes_type" => sanitize($_POST["incometype"]),
            "Amount" => sanitize($_POST['amount']),
            "User_ID" =>  $_SESSION['loguser']
        );
        if($incomeObject->insertionMethod("Incomes", $myArray))
        {
            $_SESSION['msg'] = "Farm income inserted successfully!";
            header('Location: ../incomes.php');
        }
        else
        {
            $_SESSION['error_msg'] = "Error, Failed to insert the income";
            header('Location: ../incomes.php');
        }
    }

    if(isset($_POST['incomeedit']))
    {
        $id = sanitize($_POST["id"]);
        $where = array(
            "Incomes_ID" => $id

        );

        $myArray = array( 
            "Incomes_date" => sanitize($_POST['date']),
            "Incomes_type" => sanitize($_POST['incometype']),
            "Amount" => sanitize($_POST['amount']),
            "User_ID" =>  $_SESSION['loguser']
        );
        if($incomeObject->updateMethod("Incomes", $where, $myArray))
        {
            $_SESSION['msg'] = " Income record updated successfully";
            header('Location: ../incomes.php');
        }
        else
        {
            $_SESSION['error_msg'] = "Error, Failed to update the income record";
            header('Location: ../incomes.php');
        }
    }

    if(isset($_GET['incomedelete']))
    {
        $id = $_GET['id'] ?? null;
        $where = array("Incomes_ID" => $id);

        if($incomeObject->deleteMethod("Incomes", $where))
        {
            $_SESSION['msg'] = "Income record deleted successfully";
            header('Location: ../incomes.php');
        }
        else{
            $_SESSION['error_msg'] = "Error, failed to delete the record";
            header("Location: ../incomes.php");
        }
    }

    $expenseObject = new CrudOperation();
    if(isset($_POST["expensesave"]))
    {
        $myArray = array(
            "Expense_date" => sanitize($_POST['date']),
            "Expense_type" => sanitize($_POST["expensetype"]),
            "Amount" => sanitize($_POST["amount"]),
            "User_ID" =>  $_SESSION['loguser']
        );

        if($expenseObject->insertionMethod("Expenses", $myArray))
        {
            $_SESSION['msg'] = "Expense record inserted successfully";
            header('Location: ../expenses.php');
        }
        else
        {
            $_SESSION['error_msg'] = "Error, failed to insert the expense record";
            header('Location; ../expenses.php');
        }
    }

    if(isset($_POST["expenseedit"]))
    {
        $id = sanitize($_POST["id"]);
        $where = array("Expense_ID" => $id);
        $myArray = array(
            "Expense_date" => sanitize($_POST['date']),
            "Expense_type" => sanitize($_POST["expensetype"]),
            "Amount" => sanitize($_POST['amount']),
            "User_ID" =>  $_SESSION['loguser']
        );

        if($expenseObject->updateMethod("Expenses", $where, $myArray))
        {
            $_SESSION['msg'] = "Expense record updated successfully!";
            header('Location: ../expenses.php');
        }
        else
        {
            $_SESSION['error_msg'] = "Error, failed to update the expense record";
            header('Location: ../expenses.php');
        }
    }
    if(isset($_GET['expensedelete']))
    {
        $id = $_GET['id'] ?? null;
        $where = array("Expense_ID" => $id);

        if($expenseObject->deleteMethod("Expenses", $where))
        {
            $_SESSION['msg'] = "Expense record deleted successfully";
            header('Location: ../expenses.php');
        }

        else{
            $_SESSION['error_msg'] = "Error, failed to delete the expense record";
            header('Location: ../expenses.php');
        }

    }

    $sql = "SELECT Fee_Amount FROM Fees WHERE Fee_ID > 0";
	$query_sql = $databaseObject->connect()->query($sql);
	$array_fee = mysqli_fetch_array($query_sql);
	$eggPrice = $array_fee['Fee_Amount'];

    // INSIGHTS

    // Returning the total number of birds purchased
    $seconds = 31 * 24 *60*60;
   
    $timestamp = strtotime(date('Y-m-d')) - $seconds;
    $query = "SELECT SUM(NumberOfBirds) AS sum FROM `BirdsPurchase`"; 
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalNumberOfBirds = $row['sum'];
    };

    // Returning the total number of eggs
    $query = "SELECT SUM(NumberOfEggs) AS sum FROM `Production`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalNumberOfEggs = $row['sum'];
    }

    // Returning the mortality rate

    $query = "SELECT SUM(Deaths) AS sum FROM `BirdsMortality`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalDeaths = $row['sum'];
    }

    if($totalDeaths <= $totalNumberOfBirds){
        $mortalityRate = round($totalDeaths / $totalNumberOfBirds * 100 , 1);
        $totalNumberOfBirds = $totalNumberOfBirds - $totalDeaths;
    }else{
        $mortalityRate = 0;
    }
    $remainingBirds = $totalNumberOfBirds - $totalDeaths;
    
    // Returning the total number of wages
    $totalWages = 0;
    $query = "SELECT SUM(Salary) AS sum FROM `Employee` WHERE endDate IS Null ";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalWages = $row['sum'];
    }
    $sixtydays = 61 * 24 * 3600;
    $lastsixtydays = strtotime(date('Y-m-d')) - $sixtydays;

    //returning total expense
    $expense = 0;
    $query = "SELECT SUM(Amount) AS sum FROM Expenses WHERE UNIX_TIMESTAMP(Expense_date) > $lastsixtydays";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result))
    {
        $expense = $row['sum'];
    }

    //returning expense from purchasing birds in last sixty days
    $birdsExpense = 0;
    $query = "SELECT SUM(Price) AS sum FROM BirdsPurchase WHERE UNIX_TIMESTAMP(Date) > $lastsixtydays";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result))
    {
      $birdsExpense = $row['sum'];
    }

    //returning the expense from feed purchase
    $feedExpense = 0;
    $query = "SELECT SUM(Price) AS sum FROM FeedPurchase WHERE UNIX_TIMESTAMP(Date) > $lastsixtydays";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result))
    {
        $feedExpense = $row['sum'];
    }

    //returning the total expense from medicine purchase
    $medicineExpense = 0;
    $query = "SELECT SUM(Price) AS sum FROM MedicinePurchase WHERE UNIX_TIMESTAMP(Date) > $lastsixtydays";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result))
    {
        $medicineExpense = $row['sum'];
    }

    //Total expensse
    $totalExpense = $expense + $birdsExpense + $feedExpense + $medicineExpense + $totalWages;

    // Returning total revenue
    $sales = 0;
    $query = "SELECT SUM(Revenue) AS sum FROM `Sales` WHERE UNIX_TIMESTAMP(Sales_Date) > $timestamp";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $sales += $row['sum'];
    }
    //Returning the other additional incomes
    $incomes = 0;
    $sql = "SELECT SUM(Amount) AS sum FROM Incomes WHERE UNIX_TIMESTAMP(Incomes_date) > $timestamp";
    $result = $databaseObject->connect()->query($sql);
    while($row = mysqli_fetch_assoc($result))
    {
        $incomes = $row['sum'];
    }

    $totalRevenue = $sales + $incomes;


    // Returning remaining feed in the stock
    $query = "SELECT SUM(Quantity) AS sum FROM `FeedPurchase`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalFeedPurchased = $row['sum'];
    }

    $query = "SELECT SUM(Quantity) AS sum FROM `FeedConsumption`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalFeedConsumed = $row['sum'];
    }
    $remainingFeed = $totalFeedPurchased - $totalFeedConsumed;

    // Returning number of eggs available for sale
    $query = "SELECT SUM(NumberOfEggs) AS sum FROM `Production`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalEggsProduced = $row['sum'];
    }

    $query = "SELECT SUM(NumberOfEggs) AS sum FROM `Sales`";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalEggsSold = $row['sum'];
    }
    $remainingEggs = $totalEggsProduced - $totalEggsSold;

    // Getting the total number of employees working in the farm
    $query = "SELECT COUNT(*) AS sum FROM `Employee` WHERE endDate IS Null";
    $result = $databaseObject->connect()->query($query);
    while($row = mysqli_fetch_assoc($result)){
        $totalNumberOfEmployees = $row['sum'];
    }
    
   
?>