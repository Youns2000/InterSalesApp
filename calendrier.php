<?php
session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  $_SESSION['currentPage'] = "calendrier";
require_once('calendar/bdd.php');


$sql = "SELECT id, title, start, end, color FROM events ";
$sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';

$data=array();
$db = include 'db_mysql.php';

      try { 
        if(isset($_POST['modifmdp'])){
              $db = include 'db_mysql.php';
                $mail = $_SESSION['email'];
                $stmt = $db->prepare($sql_data);
                $stmt->execute(array($mail));
                $data= $stmt->fetchAll(PDO::FETCH_ASSOC);
                unset($db);
                if(count($data)==1 and password_verify($_POST['mdpactu'], $data[0]['mdp']) and $_POST['newmdp']==$_POST['confirm']){
                  $dbco = include 'db_mysql.php';
                  $sth = $dbco->prepare('UPDATE comptes SET mdp=? WHERE email=?');
                  $sth->execute(array(password_hash($_POST['newmdp'], PASSWORD_DEFAULT),$_SESSION['email']));
                  unset($dbco);
                }
                else{
                  echo "mdp incorect";
                }
            } 
            $req = $bdd->prepare($sql);
            $req->execute();

            $events = $req->fetchAll();
      } catch (Exception $e) {
             print "Erreur ! " . $e->getMessage() . "<br/>";
          }




?>

<!DOCTYPE html>
<html lang="en">

<head>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Younes Benreguieg">
    <title>Integral Sales App</title>
    <link href="css/style_menu.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <meta name="theme-color" content="#563d7c">
    <script src="js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <link rel="apple-touch-icon" href="logos/logo.JPG" sizes="180x180">
    <link rel="icon" href="logos/logo.JPG" sizes="32x32" type="image/png">
    <link rel="icon" href="logos/logo.JPG" sizes="16x16" type="image/png">
    <link rel="icon" href="logos/logo.JPG">

    <title>Bare - Start Bootstrap Template</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

	
	<!-- FullCalendar -->
	<link href='calendar/css/fullcalendar.css' rel='stylesheet' />

	<style type="text/css">
      .form-group input[type="checkbox"] {
          display: none;
      }

      .form-group input[type="checkbox"] + .btn-group > label span {
          width: 20px;
      }

      .form-group input[type="checkbox"] + .btn-group > label span:first-child {
          display: none;
      }
      .form-group input[type="checkbox"] + .btn-group > label span:last-child {
          display: inline-block;   
      }

      .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
          display: inline-block;
      }
      .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
          display: none;   
      }
      .custom-select {
          width: 50px !important;
          display: inline-flex !important; 
      }
      .custom-select-2 {
          width: 150px !important;
          display: inline-flex !important; 
      }
      .container-fluid{
        padding-top: 50px;
      }
      .special-menu{
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
        font-size: 15px;
        background-color: rgba(52,58,64,0.8)
      }
      .color-menu:hover{
        background-color: #303234!important;
          border-color: #303234!important;
      }
      .color-menu,
      .color-menu:active,
      .color-menu:visited,
      .color-menu:focus {
          background-color: #50575D!important;
          border-color: #50575D!important;
      }
      li{
        margin-left: 20px;
      }
      
      .sidebar{
        margin-top:100px;
      }
      .navbar{
        padding-top: 0px;
        padding-bottom: 0px;
        padding-left: 0px;
      }

      .padding-top-0{
        padding-top: 0px;
      }
      .list-group{
        padding-top: 10px;
      }
      .ul-special{
        margin-left: 290px;
      }
      .btn-lg{      
        /*width: 250px;*/
      }
      
      .bg-clair{
        background-color: #f8f9fa85;
      }
      .list-group-item.active {
          z-index: 2;
          color: #fff;
          background-color: #1d2124;
          border-color: #1d2124;
      }
      .bg-card-rouge{
        background-color: rgb(123 42 50 / 75%)!important;
      }
      .bg-card-vert{
        background-color: rgb(56 128 72 / 75%)!important;
      }
      .bg-card-jaune{
        background-color: rgb(193 150 25 / 75%)!important;
      }
      .bg-card-bleu{
        background-color: rgb(23 84 150 / 75%)!important;
      }
      .bg-card-mauve{
        background-color: rgb(47 27 113 / 75%)!important;
      }
      .bg-card-bleuvert{
        background-color: rgb(26 123 117 / 75%)!important;
      }
      .session{
        margin-right: 2%;
        margin-top: 0.22%;
      }
      /*.navbar-nav>li>a{
        padding-top: 2%; 
        padding-bottom: auto; 
      }*/


    </style>

    <!-- Custom CSS -->
    <style>
    /*body {
        padding-top: 70px;
    }*/
	/*#calendar {
		max-width: 800px;
	}*/
	.col-centered{
		float: none;
		margin: 0 auto;
	}
  .navbar-nav>li>a{
    padding-top: 7%;
    padding-bottom: 7%;
  }
  /* .navbar{
	  padding-top: ;
	  padding-left: ;
	  padding-bottom: ;
  } */
	</style>
	<link href="my_css.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<header>
  <?php include("header.php"); ?> 
</header>

    <!-- Page Content -->
    <div class="container padding-top-0">

        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="card shadow-sm "><div id="calendar" class="col-centered"></div></div>
            </div>
			
        </div>
        <!-- /.row -->
		
		<!-- Modal -->
		<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="addEvent.php">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Event</h4>
			  </div>
			  <div class="modal-body">
				
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option value="">Choose</option>
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						  
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="start" class="col-sm-2 control-label">Start date</label>
					<div class="col-sm-10">
					  <input type="text" name="start" class="form-control" id="start" readonly>
					</div>
				  </div>
				  <div class="form-group">
					<label for="end" class="col-sm-2 control-label">End date</label>
					<div class="col-sm-10">
					  <input type="text" name="end" class="form-control" id="end" readonly>
					</div>
				  </div>
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>
		
		
		
		<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="editEventTitle.php">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Event</h4>
			  </div>
			  <div class="modal-body">
				
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option value="">Choose</option>
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						  
						</select>
					</div>
				  </div>
				    <div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10">
						  <div class="checkbox">
							<label class="text-danger"><input type="checkbox"  name="delete"> Delete event</label>
						  </div>
						</div>
					</div>
				  
				  <input type="hidden" name="id" class="form-control" id="id">
				
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="calendar/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="calendar/js/bootstrap.min.js"></script>
	
	<!-- FullCalendar -->
	<script src='calendar/js/moment.min.js'></script>
	<script src='calendar/js/fullcalendar.min.js'></script>
	
	<script>

	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			defaultDate: '2016-01-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				
				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #title').val(event.title);
					$('#ModalEdit #color').val(event.color);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php foreach($events as $event): 
			
				$start = explode(" ", $event['start']);
				$end = explode(" ", $event['end']);
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $event['start'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $event['end'];
				}
			?>
				{
					id: '<?php echo $event['id']; ?>',
					title: '<?php echo $event['title']; ?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
					color: '<?php echo $event['color']; ?>',
				},
			<?php endforeach; ?>
			]
		});
		
		function edit(event){
			start = event.start.format('YYYY-MM-DD HH:mm:ss');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm:ss');
			}else{
				end = start;
			}
			
			id =  event.id;
			
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			
			$.ajax({
			 url: 'calendar/editEventDate.php',
			 type: "POST",
			 data: {Event:Event},
			 success: function(rep) {
					if(rep == 'OK'){
						alert('Saved');
					}else{
						alert('Could not be saved. try again.'); 
					}
				}
			});
		}
		
	});

</script>

</body>

</html>
