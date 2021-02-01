<form method="post">
<div class="modal fade" id="newAction" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <h5>Fiche d'Action</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body"> 
          <div class="top-group">
            <div class="carre1">
              <!-- <div class="base" style="display:block;width:50%;"><p>Avancement</p></div> -->
              <div style="position: relative;white-space: nowrap;display: inline-block;width:100%;">
                <div class="btn-group btn-group-toggle" style="width:90%;" data-toggle="buttons">
                  <label class="btn base">0%
                    <input type="radio" class="btn-check" name="avancement" id="0" value="0" autocomplete="off" >
                  </label>
                  <label class="btn base">30%
                    <input type="radio" class="btn-check" name="avancement" id="30" value="30" autocomplete="off" >
                  </label>
                  <label class="btn base">70%
                    <input type="radio" class="btn-check" name="avancement" id="70" value="70" autocomplete="off" >
                  </label>
                  <label class="btn base">100%
                    <input type="radio" class="btn-check" name="avancement" id="100" value="100" autocomplete="off" >
                  </label>
                </div>
              </div>
            </div>

            <div class="carre2">
              <!-- <div class="btn-group btn-group-toggle" style="display:block; " data-toggle="buttons"> -->
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <label class="btn base">I et U
                    <input type="radio" class="btn-check" name="avancement" id="0" value="0" autocomplete="off" >
                  </label>
                  <label class="btn base">I et Non U
                    <input type="radio" class="btn-check" name="avancement" id="30" value="30" autocomplete="off" >
                  </label><br>
                  <label class="btn base">U et Non I
                    <input type="radio" class="btn-check" name="avancement" id="70" value="70" autocomplete="off" >
                  </label>
                  <label class="btn base">Non U et Non I
                    <input type="radio" class="btn-check" name="avancement" id="100" value="100" autocomplete="off" >
                  </label>
              </div>
            </div>

            <div class="carre3">
              <button type="submit" class="btn btn-success">Enregistrer</button>
            </div>
            
          </div>

          
          
    </div>
  </div>
</div>
</div>
</form>