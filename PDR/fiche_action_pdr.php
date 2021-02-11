<form method="post">
<div style="margin-left:50px;" class="modal fade" id="newAction" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-xl">
  
    
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <h5>Fiche d'Action</h5>
        <button type="button" class="close" id="closeModalAction">&times;</button>
      </div>

      <div class="modal-body"> 
          <div class="top-group">
            <div class="carre1">
              <!-- <div class="base" style="display:block;width:50%;"><p>Avancement</p></div> -->
              <div style="position: relative;white-space: nowrap;display: inline-block;width:100%;">
                <div class="btn-group btn-group-toggle" style="width:90%;" data-toggle="buttons" >
                  <label class="btn base">0%
                    <input type="radio" class="btn-check sr-only" name="avancement" id="0" value="0" required>
                  </label>
                  <label class="btn base">30%
                    <input type="radio" class="btn-check sr-only" name="avancement" id="30" value="30" required>
                  </label>
                  <label class="btn base">70%
                    <input type="radio" class="btn-check sr-only" name="avancement" id="70" value="70" required>
                  </label>
                  <label class="btn base">100%
                    <input type="radio" class="btn-check sr-only" name="avancement" id="100" value="100" required>
                  </label>
                </div>
              </div>
            </div>

            <div class="carre2">
              <!-- <div class="btn-group btn-group-toggle" style="display:block; " data-toggle="buttons"> -->
              <div class="btn-group btn-group-toggle" data-toggle="buttons" >
                  <label id="test" class="btn base">I et U
                    <input type="radio" class="btn-check sr-only" name="importance" id="importance0" value="0" required>
                  </label>
                  <label id="test2" class="btn base">I et Non U
                    <input type="radio" class="btn-check sr-only" name="importance" id="importance1" value="1" required>
                  </label><br>
                  <label id="test3" class="btn base">U et Non I
                    <input type="radio" class="btn-check sr-only" name="importance" id="importance2" value="2" required>
                  </label>
                  <label id="test4" class="btn base">Non U et Non I
                    <input type="radio" class="btn-check sr-only" name="importance" id="importance3" value="3" required>
                  </label>
              </div>
            </div>

            <div class="carre3">
              <button type="submit" name="SaveAction" class="btn btn-success">Enregistrer</button>
            </div>
            
          </div>

          <div class="group">
            <div class="forms">
              <label>Titre:</label>
              <input id="titre" name="titre" style="display: inline-block;" required></input>
            </div>
            <div class="forms">
              <label>Partenaire:</label>
              <input id="partenaire" name="partenaire" style="display: inline-block;" required></input>
            </div>
            <!-- <div class="forms">
              <label>Projet:</label>
              <select class="custom-select d-block w-100" name="projet" id="projet" required>
                <option selected value>...</option>
                <?php 
                for ($i=0; $i < count($projets); $i++) { 
                  echo "<option value =\"".$projets[$i]['code']."\" >".$projets[$i]['nom']."</option>";
                }
                ?>
              </select>
            </div> -->
          </div>

          <div class="group">
            <div class="forms">
              <label>Date de création: </label>
              <label id="date_creation"><B><?php echo date("d/m/Y");?></B></label>
            </div>
            <div class="forms">
              <label style="display: inline-block;">Date Programmée:</label>
              <input style="display: inline-block;" id="date_prog" class="form-control"  name="date_prog" type="date" required/>
            </div>
          </div>

          <input style="display: none;" id="id_pdr" class="form-control"  name="id_pdr" type="number"/>

          <div class="group">
            <label>Détails de l'Action:</label>
            <textarea class="form-control" id="detail" name="detail" name="projetText" rows="3"></textarea>
          </div>
    </div>
  </div>
  
</div>
</div>
</form>

<script>
$('#closeModalAction').click(function() {
    $('#newAction').modal('hide');
});
</script>