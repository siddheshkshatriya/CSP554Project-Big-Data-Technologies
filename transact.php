<?php  include 'header.php'; ?>
<html>
<?php

 
 require_once('connection.php');


?>

  <!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <div class="row bg-title">
       
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
          <h4 class="page-title">Cart Details </h4>
        </div>
        <!-- /.col-lg-12 -->
      </div>
      <!-- .row -->
     <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
          <div class="white-box">
           
            <!-- Nav tabs -->
            
            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="home">
                <div class="col-md-12">
        
                  <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">All User list</h4>
                  </div>
    <div class="fetched-data"></div>
                 
                </div>
              </div>
            </div>
    
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
   
            <div class="table-responsive" style="overflow-x: hidden;">
            <table id="myTable" class="table table-striped">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Action</th>
                  <th>Quantity</th>

                  <th>Time</th>
                  <th>Options</th>
                </tr>
              </thead>
              <tbody>
        

<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database
$databaseName = 'inventory';

// Function to format the date and time
function formatDateAndTime($timestamp) {
    return date('Y-m-d H:i:s', $timestamp);
}

// Fetch transaction records
$filter = [];
$options = ['sort' => ['time' => 1]];
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery("$databaseName.transact", $query);

// Loop through transaction records
foreach ($cursor as $document) {
    $date_time = formatDateAndTime($document->time);

    echo "<tr>";
    echo "<td>{$document->prd_code}</td>";
    echo "<td>{$document->prd_name}</td>";
    echo "<td>{$document->state}</td>";
    echo "<td>{$document->prd_quantity}</td>";

    echo "<td>{$date_time}</td>";
    echo "<td>";
    echo '<div class="btn-group m-r-12">';
    echo '<button aria-expanded="false" data-toggle="dropdown" class="btn btn-info dropdown-toggle waves-effect waves-light" type="button">Options <span class="caret"></span></button>';
    echo '<ul role="menu" class="dropdown-menu">';
    echo "<li><a href='delete_log.php?id={$document->_id}'>Delete Log</a></li>";
    echo '</ul>';
    echo '</div>';
    echo "</td></tr>";
}
?>

              
              </tbody>
            </table>
      <br>
      <br>
      <br>
      <br>
            </div>
          </div>
        </div>
        
      
      </div>
                </div>
                
                <div class="clearfix"></div>
        
              </div>
             
              
             
            </div>
          </div>
        
        </div>
      <!-- /.row -->
   
   
    </div>
    <!-- /.container-fluid -->
    
  </div>
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

<!-- Date Picker Plugin JavaScript -->
<script src="../plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="../plugins/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
<script src="../plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- end - This is for export functionality only -->

<script>
    $(document).ready(function(){
      $('#myTable').DataTable();
      $(document).ready(function() {
        var table = $('#example').DataTable({
          "columnDefs": [
          { "visible": false, "targets": 2 }
          ],
          "order": [[ 2, 'asc' ]],
          "displayLength": 25,
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
              if ( last !== group ) {
                $(rows).eq( i ).before(
                  '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                  );

                last = group;
              }
            } );
          }
        } );

    // Order by the grouping
    $('#example tbody').on( 'click', 'tr.group', function () {
      var currentOrder = table.order()[0];
      if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
        table.order( [ 2, 'desc' ] ).draw();
      }
      else {
        table.order( [ 2, 'asc' ] ).draw();
      }
    });
  });
    });
    $('#example23').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
  
  
// Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose1').datepicker({
        autoclose: true,
        todayHighlight: true
      });
      
    jQuery('#date-range').datepicker({
        toggleActive: true
      });
    jQuery('#datepicker-inline').datepicker({
        
        todayHighlight: true
      });
    
  // Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose2').datepicker({
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


 <script>
  
  function ask(c_id)
  { 


    var id=student_id;

    if(confirm("Please Confirm to Delete Customer"))
    {
      window.location.href='delete_student.php?student_id='+id;
      return true;
    }
  
  }
   
  </script>


<!--Style Switcher -->
<script src="plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

</body>
</html>