<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/action.php";

include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/functions.php";

checkLogin();

?>
<!DOCTYPE html>
<html lang="en">
<!-- head -->
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php";?>
<body id="body">
    <div class="container">
        <!-- top navbar -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar_accounting.php";?>
        <main>
            <div class="main__container">
            <?php if(isset($_SESSION['msg'])): ?>
                    <div class="msg">
                    <p>
                        <?php 
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']);
                        ?>
                    </p>
                    </div>
                <?php endif ?>
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Income Type</th>
                        <th>Amount</th>
                        <th>Update by:</th>
                        <th colspan="2">Action</th>
                        
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $productionObject->viewMethod("Incomes");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Incomes_date'];?></td>
                                <td><?php echo $row['Incomes_type'];?></td>
                                <td><?php echo $row['Amount'];?></td>
                                <td>
                                <?php
                                $user_id = $row['User_ID'];
                                $sql = "SELECT Username FROM User WHERE User_ID = $user_id";
                                $query = $databaseObject->connect()->query($sql);
                                $username = mysqli_fetch_array($query);

                                echo $username['Username'];?>
                                </td>
                                <td>
                                    <a class="edit_btn" href="incomes.php?incomeupdate=1&id=<?php echo $row["Incomes_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?incomedelete=1&id=<?php echo $row["Incomes_ID"]; ?>">Delete</a>
                                </td>
                                
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["incomeupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("Incomes_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $salesObject->selectMethod("Incomes", $where);
                        ?>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="date" id="date" max ="<?php echo date('Y-m-d'); ?>" value="<?php echo $row["Incomes_date"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Income Type</label>
                                    <input type="text" id="incometype" step="any" name="incometype" value="<?php echo $row["Incomes_type"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorAmount"></div>
                                    <label for="">Amount</label>
                                    <input type="number" id="amount" step="any" name="amount" value="<?php echo $row["Amount"]; ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="incomeedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="date" id="date" max="<?php echo date('Y-m-d'); ?>" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Income Type</label>
                                    <input type="text" step="any" id="incometype" name="incometype" value="">
                                <div class="input-group">
                                <div class="my-div-error" id="errorAmount"></div>
                                    <label for="">Amount</label>
                                    <input type="number" step="any"id="amount" name="amount" value="">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="incomesave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script>
    function validate(){
                        var dates = document.getElementById("date").value;
                        var type = document.getElementById("incometype").value;
                        var amount = document.getElementById("amount").value;
                       
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errorAmount = document.getElementById("errorAmount");
                        var errorType = document.getElementById("errorType");
                       
                        
                        var truth = true;
                        if(dates == ""){
                            errordate.innerHTML = "This field is required";
                            truth = false;
                        }
                       if(amount < 1)
                       {
                           errorAmount.innerHTML = "Amount must be greater than zero";
                           truth = false;
                       }
                        if(amount =="")
                        {
                            errorAmount.innerHTML = "Amount field is required";
                            truth = false;
                        }
                        if(type == "")
                        {
                            errorType.innerHTML = "Type field is required";
                            truth = false;
                        }
                        
                        
                        

                    


                        return truth;

                    }
                    </script>
    <script src="script.js"></script>
</body>
</html>