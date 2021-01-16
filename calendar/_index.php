<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: ../index.php');
  } 

   $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';
  
    $data=array();
    $db = include '../db_mysql.php';
    try { 
      if(isset($_POST['modifmdp'])){
              $db = include '../db_mysql.php';
                $mail = $_SESSION['email'];
                $stmt = $db->prepare($sql_data);
                $stmt->execute(array($mail));
                $data= $stmt->fetchAll(PDO::FETCH_ASSOC);
                unset($db);
                if(count($data)==1 and password_verify($_POST['mdpactu'], $data[0]['mdp']) and $_POST['newmdp']==$_POST['confirm']){
                  $dbco = include '../db_mysql.php';
                  $sth = $dbco->prepare('UPDATE comptes SET mdp=? WHERE email=?');
                  $sth->execute(array(password_hash($_POST['newmdp'], PASSWORD_DEFAULT),$_SESSION['email']));
                  unset($dbco);
                }
                else{
                  echo "mdp incorect";
                }
            } 
      } catch (Exception $e) {
             print "Erreur ! " . $e->getMessage() . "<br/>";
          }
  ?>



<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Younes Benreguieg">
    <title>Integral Sales App</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="apple-touch-icon" href="logos/logo.JPG" sizes="180x180">
    <link rel="icon" href="../logos/logo.JPG" sizes="32x32" type="image/png">
    <link rel="icon" href="../logos/logo.JPG" sizes="16x16" type="image/png">
    <link rel="manifest" href="../favicons/manifest.json">
    <link rel="mask-icon" href="../favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="../logos/logo.JPG">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <meta name="msapplication-config" content="../favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">
    <link href="../css/style_menu.css" rel="stylesheet">
    <link href="../css/dashboard.css" rel="stylesheet">
    <link href="../css/carousel.css" rel="stylesheet">
    <link href='css/fullcalendar.css' rel='stylesheet' />
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
        font-size: 15;
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
        margin-top: 0.5%;
      }


    </style>
    <link rel="stylesheet" href="fullcalendar/lib/main.css">
    
        <style>

      #calendar {
        max-width: 800px;
      }
      .col-centered{
        float: none;
        margin: 0 auto;
      }
        </style>
  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT --------------------------------------------------------------------->
   <header>
          <nav class="navbar navbar-expand-lg navbar-dark">

                  <ul class="navbar-nav mr-auto ul-special session">
                    <li class="nav-item">

                      <a class="btn btn-dark btn-lg special-menu" href="../marketing.php?categ=Postes%20Premium">
                        <svg width="1.5em" height="1.5em" viewBox="0.5 1.5 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg> PRODUITS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu " href="../projets.php?pr=0">
                      <svg width="1.5em" height="1.5em" viewBox="0 1 16 16" class="bi bi-folder-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                      </svg> PROJETS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu active" href="calendrier.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-calendar-date" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        <path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z"/>
                      </svg> AGENDA <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu" href="../newClient.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 1 16 16" class="bi bi-people-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                      </svg> CONTACTS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu" href="../marques.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-bag-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 1a2.5 2.5 0 0 0-2.5 2.5V4h5v-.5A2.5 2.5 0 0 0 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                      </svg> MARQUES </a>
                    </li>

                  </ul>
                <!-- </div> -->
                <!-- <div class="col-lg-4"> -->
                  <ul class="navbar-nav session">
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- <span class="navbar-toggler-icon"></span> -->
                              <?php echo $_SESSION['prenom'] ." ".$_SESSION['nom']?><br/>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <button type="button" data-backdrop="false" data-toggle="modal" data-target="#modal_modif_mdp" class="dropdown-item">Modifier mot de passe</button>
                        <a type="button" data-backdrop="false" href="../deconnection.php" class="dropdown-item">Déconnection</a>
                      </div>
                    </div>
                  <!-- /////////////////MODAL/////////////////////// -->
                    <form method="post">
                          <div class="modal fade" id="modal_modif_mdp" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">
                            
                              <!-- Modal content-->
                              <div class="modal-content">

                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                  <p>Mot de passe actuel : <input type="password" id="mdpactu" name="mdpactu" type="text"/></p>
                                  <p>Nouveau mot de passe : <input type="password" id="newmdp" name="newmdp" type="text"/></p>
                                  <p>confirmer mot de passe : <input type="password" id="confirm" name="confirm" type="text"/></p>
                                </div>

                                <div class="modal-footer">
                                  <button name = "modifmdp" type="submit" class="btn btn-default">OK</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </ul>
          </nav>
</header>



<div class="container">

        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="calendar" class="col-centered">
                </div>
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
          <label for="color" class="col-sm-2 control-label">Couleur</label>
          <div class="col-sm-10">
            <select name="color" class="form-control" id="color">
              <option value="">Choisir</option>
              <option style="color:#0071c5;" value="#0071c5">&#9724; Bleu</option>
              <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
              <option style="color:#008000;" value="#008000">&#9724; Vert</option>              
              <option style="color:#FFD700;" value="#FFD700">&#9724; Jaune</option>
              <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
              <option style="color:#FF0000;" value="#FF0000">&#9724; Rouge</option>
              <option style="color:#000;" value="#000">&#9724; Noir</option>
              
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
              <option value="">Choisir</option>
              <option style="color:#0071c5;" value="#0071c5">&#9724; Bleu</option>
              <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
              <option style="color:#008000;" value="#008000">&#9724; Vert</option>              
              <option style="color:#FFD700;" value="#FFD700">&#9724; Jaune</option>
              <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
              <option style="color:#FF0000;" value="#FF0000">&#9724; Rouge</option>
              <option style="color:#000;" value="#000">&#9724; Noir</option>
              
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


    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
  
  <!-- FullCalendar -->
  <script src='js/moment.min.js'></script>
  <script src='js/fullcalendar.min.js'></script>
  <script src='locales/locales-all.js'></script>
  
  <script>

  $(document).ready(function() {
    
    $('#calendar').fullCalendar({
      locale: 'fr',
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
       url: 'editEventDate.php',
       type: "POST",
       data: {Event:Event},
       success: function(rep) {
          if(rep == 'OK'){
            //alert('Saved');
          }else{
            //alert('Could not be saved. try again.'); 
          }
        }
      });
    }
    
  });

</script>



<script src="fullcalendar/lib/main.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script>
</body>
      <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</html>