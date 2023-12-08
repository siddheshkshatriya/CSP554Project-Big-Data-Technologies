<?php  include 'header.php'; ?>
<?php
if ((isset($_SESSION['username'])) )
    {

    }
    else{
        echo"<script>window.location.href='index.php';</script>";
        
    }
    


require 'vendor/autoload.php'; // include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database and collection
$databaseName = 'inventory';
$collectionName = 'product';

$prd_code = $_REQUEST["id"];
$filter = ['prd_code' => $prd_code];
$query = new MongoDB\Driver\Query($filter);


$cursor = $manager->executeQuery("$databaseName.$collectionName", $query);


foreach ($cursor as $document) {

     $prd_name = $document->prd_name;
     $prd_code = $document->prd_code;
     $prd_disc = $document->prd_disc;
     $prd_cost = $document->prd_cost;
     $prd_inventory = $document->prd_inventory;

}


?>

<!DOCTYPE html>

<html lang="en">

<body class="fix-sidebar">

<div id="wrapper">


  <!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Edit Details</h4>
        </div>
        </div>
	  
      <!-- /row -->
	   

   <div class="row">
        <div class="col-md-12">
          <div class="white-box">
		      <div class="row">
		  <!-- Nav tabs -->
           
    <!-- Tab panes -->

   <div class="col-md-12">
   <form class="form-material form-horizontal" action="edit_back.php" method="POST" enctype="multipart/form-data" onsubmit="validateform()" >
				
			<input type="hidden" name="id" value="<?php echo $prd_code; ?>">
            <input type="hidden" name="old_prd_inventory" value="<?php echo $prd_inventory; ?>">

            
      <div class="form-group">
            <div class="col-md-2">
            <label for="event_title">Product Name<span class="text-danger"></span></label></div>
                <div class="col-md-3">
                   <input type="text" class="form-control" id="prd_name" name="prd_name"  value="<?php echo $prd_name; ?>" required  ><br>
                </div>
      </div>





      <div class="form-group">
            <div class="col-md-2">
            <label for="event_title">Product Description<span class="text-danger"></span></label></div>
                <div class="col-md-3"> 
                   <input type="textarea" class="form-control" id="prd_disc" name="prd_disc" value="<?php echo $prd_disc; ?>"  required  ><br>
                </div>      
      </div>

          <div class="form-group">
            <div class="col-md-2">
            <label for="event_title">Product Cost<span class="text-danger"></span></label></div>
                <div class="col-md-3">
                   <input type="text" class="form-control" id="prd_cost" name="prd_cost" value="<?php echo $prd_cost; ?>" required  ><br>
                </div>
      </div>


          <div class="form-group">
            <div class="col-md-2">
            <label for="event_title">Product Quantity<span class="text-danger"></span></label></div>
                <div class="col-md-3">
                   <input type="text" class="form-control" id="prd_inventory" name="prd_inventory" value="<?php echo $prd_inventory; ?>"  required  ><br>
                </div>
      </div>



	    <div class="form-group" style="margin-left:400px">
	             

			<button type="submit" id="submit" name="submit" class="btn btn-success waves-effect waves-light m-r-10">Submit</button>
  
      </div>
	   
	    </form>

              </div>
             </div>
            </div>
          </div>
        </div>
      <!-- /.row -->
   
   

  <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="js/jquery.slimscroll.js"></script>

<!-- Sweet-Alert  -->
<script src="plugins/bower_components/sweetalert/sweetalert.min.js"></script>
<script src="plugins/bower_components/sweetalert/jquery.sweet-alert.custom.js"></script>
<!--Wave Effects -->
<script src="js/waves.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/custom.min.js"></script>
<script src="plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script src="js1/dataTables.buttons.min.js"></script>
<script src="js1/buttons.flash.min.js"></script>
<script src="js1/jszip.min.js"></script>
<script src="js1/pdfmake.min.js"></script>
<script src="js1/vfs_fonts.js"></script>
<script src="js1/buttons.html5.min.js"></script>
<script src="js1/buttons.print.min.js"></script>
<script src="js/jasny-bootstrap.js"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="plugins/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
<script src="plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end - This is for export functionality only -->

<script>
	
	// Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
      });
      
    jQuery('#date-range').datepicker({
        toggleActive: true
      });
    jQuery('#datepicker-inline').datepicker({
        
        todayHighlight: true
      });

// Daterange picker

$('.input-daterange-datepicker').daterangepicker({
          buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse'
        });
            $('.input-daterange-timepicker').daterangepicker({
                timePicker: true,
                format: 'MM/DD/YYYY h:mm A',
                timePickerIncrement: 30,
                timePicker12Hour: true,
                timePickerSeconds: false,
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse'
            });
            $('.input-limit-datepicker').daterangepicker({
                format: 'MM/DD/YYYY',
                minDate: '06/01/2015',
                maxDate: '06/30/2015',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                dateLimit: {
                    days: 6
                }
            });
</script>
  
<!--Style Switcher -->
<script src="plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

</body>
</html>