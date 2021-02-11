<form method="post">
<div class="modal fade" id="modifPDR" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-xl"><!--  modal-dialog-scrollable -->
    
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <h5>Dossier PDR</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
          <div class="btn-group btn-group-toggle" id="categList" data-toggle="buttons" style="margin: 0 auto;width: 100%; ">

            <label class="btn base" >Demande
              <input type="radio" class="btn-check sr-only" name="categ" id="demandes" value="demandes" autocomplete="off" required>
            </label>
            <label class="btn base" >Sourcing
              <input type="radio" class="btn-check sr-only" name="categ" id="sourcing" value="sourcing" autocomplete="off" required>
            </label>
            <label class="btn base" >Offre
              <input type="radio" class="btn-check sr-only" name="categ" id="envoyer" value="envoyer" autocomplete="off" required>
            </label>
            <label class="btn base" >Commande
              <input type="radio" class="btn-check sr-only" name="categ" id="commandesClient" value="commandesClient" autocomplete="off" required>
            </label>
            <label class="btn base" >Achat
              <input type="radio" class="btn-check sr-only" name="categ" id="commandesFournisseur" value="commandesFournisseur" autocomplete="off" required>
            </label>
            <label class="btn base" >Livraison
              <input type="radio" class="btn-check sr-only" name="categ" id="livraisons" value="livraisons" autocomplete="off" required>
            </label>

          </div>

          <div class="row" style="margin-top:15px;">

              <div class="col-lg-8">
                  <div class="row" >
                    
                  </div>   

                  <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col">Référence</th>
                        <th scope="col">Marque</th>
                        <th scope="col">Désignation pièce</th>
                        <th scope="col">Qté</th>
                      </tr>
                    </thead>
                    <tbody id="piecesList">
                      <tr>
                        <th scope="row">
                          <input style="width:100%;" type="text" class="form-control-sm" name="ref0" id="ref0" value="">
                        </th>
                        <td>
                          <input style="width:100%;" type="text" class=" form-control-sm" name="marque0" id="marque0" value="">
                        </td>
                        <td>
                          <input style="width:100%;" type="text" class="form-control-sm" name="piece0" id="piece0" value="">
                        </td>
                        <td>
                          <input style="width:100%;" type="text" class="form-control-sm" name="qte0" id="qte0" value="">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <button id="addPiece" type="button" style="background-color:#e9ecef;" class="btn btn-sm btn-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                  </button>
                  <script>
                    $(document).ready(function(){
                      $("#modifPDR #addPiece").click(function(){
                        var lastID = parseInt($('#modifPDR #piecesList').children().last().children().children().last().attr('id').slice(3));
                        var piece = "<tr>";
                        piece += "<th scope=\"row\">";
                        piece += "<input style=\"width:100%;\" type=\"text\" class=\"form-control-sm\" name=\"ref"+(lastID+1)+"\" id=\"ref"+(lastID+1)+"\" value=\"\">";
                        piece += "</th>";
                        piece += "<td scope=\"row\">";
                        piece += "<input style=\"width:100%;\" type=\"text\" class=\"form-control-sm\" name=\"marque"+(lastID+1)+"\" id=\"marque"+(lastID+1)+"\" value=\"\">";
                        piece += "</td>";
                        piece += "<td scope=\"row\">";
                        piece += "<input style=\"width:100%;\" type=\"text\" class=\"form-control-sm\" name=\"piece"+(lastID+1)+"\" id=\"piece"+(lastID+1)+"\" value=\"\">";
                        piece += "</td>";
                        piece += "<td scope=\"row\">";
                        piece += "<input style=\"width:100%;\" type=\"text\" class=\"form-control-sm\" name=\"qte"+(lastID+1)+"\" id=\"qte"+(lastID+1)+"\" value=\"\">";
                        piece += "</td>";
                        piece += "</tr>";
                        $("#modifPDR #piecesList").append(piece);
                      });
                    });
                  </script>


                  <br/>
                  <table class="table">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col" style="text-align:center;">Actions</th>
                      </tr>
                    </thead> 
                    <tbody id="Actions">
                    </tbody>
                  </table>
                  <button type="button" style="background-color:#e9ecef;" data-toggle="modal" data-target="#newAction" class="btn btn-sm btn-block open-EditFicheActionInPDR">Ajouter une Action</button>
                  

              </div>       

              <div class="col-lg-4">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Client</span>
                    </div>
                    <input type="text" name="client" id="client" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                  </div>

                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Titre</span>
                    </div>
                    <input type="text" name="titre" id="titre" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                  </div>

                  <div class="input-group mb-3" style="width:100%;">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Réf. Dossier</span>
                    </div>
                    <input type="text" name="ref" id="ref" class="form-control" aria-label="ref" aria-describedby="inputGroup-sizing-default">
                  </div>
                  <div class="input-group mb-3" style="width:100%;">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Numéro de série</span>
                    </div>
                    <input type="text" name="numero_serie" id="numero_serie" class="form-control" aria-label="numero_serie" aria-describedby="inputGroup-sizing-default">
                  </div>
                  <div class="input-group mb-3" style="width:100%;">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-default">Prix Total</span>
                    </div>
                    <input type="text" name="prix" id="prix" class="form-control" aria-label="Prix" aria-describedby="inputGroup-sizing-default">
                    <div class="input-group-append">
                      <span class="input-group-text">€</span>
                    </div>
                  </div>
                  <button type="submit" name="ModifPDR" id="ModifPDR" class="btn btn-success btn-sm btn-block">Enregistrer</button>
              </div>
          </div>
          <input style="display: none;" id="id_PDR" class="form-control"  name="id_PDR" type="number"/>            
    </div>
  </div>
</div>
</div>
</form>

<script>
$(document).on("click", ".open-EditFicheActionInPDR", function () {

  console.log($("#id_PDR").val());
  $("#newAction #id_pdr").val( $("#id_PDR").val() );

});

$(document).on("click", ".open-EditFichePDRActionModif", function () {
  var ActionId = $(this).data('id');
  console.log(ActionId);
  let i = 0
  for (; i < todolist.length; i++){
    if(todolist[i]['id']==ActionId)break;
  }
  
  var action = todolist[i];
  $("#modifAction #titre").val( action['titre'] );
  $("#modifAction #partenaire").val( action['partenaire'] );
  $("#modifAction #projet").val( action['projet'] );

  let date_creation = new Date(action['date_creation']);
  let date_prog = new Date(action['date_prog']);
  // let date_diff = (date_prog - date_creation)/86400000;
  $("#modifAction #date_creation").text(action['date_creation']);
  $("#modifAction #date_prog").val( action['date_prog']);
  // $("#modifAction #depuis").text( date_diff+" jours" );

  $("#modifAction #id_action").val( ActionId );

  $("#modifAction #detail").val( action['detail'] );

  $("#modifAction .carre1 ").children().children().children('.btn').each(function(){
    if($(this).hasClass("active")) $(this).removeClass('active');
  });
  $("#modifAction .carre2 ").children().children('.btn').each(function(){
    if($(this).hasClass("active")) $(this).removeClass('active');
  });
  $("#modifAction #avancement"+action['avancement']).addClass('active');
  $("#modifAction #avancement"+action['avancement']).children().prop("checked", true);

  $("#modifAction #importance"+action['importance']).addClass('active');
  $("#modifAction #importance"+action['importance']).children().prop("checked", true);
  
});

</script>

<?php include("fiche_action_pdr.php");?>
<?php include("fiche_action_modif_pdr.php");?>