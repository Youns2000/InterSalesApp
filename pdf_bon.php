<?php
/*session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } */
  

function getBon($code){
// require('fonctions_panier.php');


$sql_options='SELECT Engin, Nom, Prix, prix_transport
          FROM options
          ORDER BY id;';
$sql_pays ='SELECT code , alpha2 , alpha3, nom_en_gb, nom_fr_fr
          FROM pays
          ORDER BY id;';  

$options=array();
$pays=array();
$db = include 'db_mysql.php';

try {
  $stmt4 = $db->prepare($sql_options);
  $stmt4->execute(array());
  $options = $stmt4->fetchAll();

  $stmt5 = $db->prepare($sql_pays);
  $stmt5->execute(array());
  $pays = $stmt5->fetchAll();
  unset($db);
} catch (Exception $e) {
    print "Erreur ! " . $e->getMessage() . "<br/>";
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);
$pdf->Image('logos/logo integral_origin.jpg',5,4,-300);
$pdf->SetFillColor(220 , 230, 242);
$pdf->SetDrawColor(0 , 0, 0);
$pdf->SetAutoPageBreak(true);

$pdf->Text(100, 15, "Sarl INTEGRAL TRADING");
$pdf->Text(100, 20, "3 CHEMIN DES REMISES 60410 VERBERIE, FRANCE");
$pdf->Text(100, 25, "Tel: 00 333 444 111 33      Email: info@integral.sarl");

//Premier rectangle
$pdf->Rect(0,37,90,30,"DF");
$pdf->Line(0, 47, 90, 47);
$pdf->Line(50, 37, 50, 47);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Text(4.5, 43.5, "BON DE COMMANDE");
$pdf->SetFont('Arial', '', 11);
$pdf->Text(54.5, 43.5, "Numero Bon");
$pdf->SetFont('Arial', '', 10);
$pdf->Text(2, 50.5, "Date Proforma:   ".date("d/m/Y"));
$pdf->Text(2, 55.5, "Date de validite:");
$pdf->Text(2, 60.5, "Delai de livraison:");
$pdf->Text(2, 65.5, "Emis et valide par:   ".$_SESSION['prenom']." ".$_SESSION['nom']);

$pdf->Text(200, 35.5, $pdf->PageNo().'/{nb}');

//Deuxieme Rectangle
$pdf->Rect(120,37,90,30,"DF");
$pdf->Text(122, 40.5, $_SESSION['NomClient']);
$pdf->Text(122, 45.5, $_SESSION['AdresseClient']);
$pdf->Text(122, 50.5, "Numero NIF: ".$_SESSION['NIF']);
$pdf->Text(122, 55.5, $_SESSION['CodePostalClient']);
$pdf->Text(155, 55.5, $_SESSION['VilleClient']);
$pdf->Text(190, 55.5, $_SESSION['WilayaClient']);
$pdf->Text(122, 60.5, iconv("UTF-8", "CP1252", $_SESSION['PaysClient']));
$pdf->Text(122, 65.5, $_SESSION['CodeClient']);
//$pdf->Text(122, 65.5, MontantGlobal());

//Bande Transport
/*$pdf->Rect(0,72,210,15,"DF");
$pdf->Line(52.5, 72, 52.5, 87);
$pdf->Line(105, 72, 105, 87);
$pdf->Line(157.5, 72, 157.5, 87);
$pdf->SetFont('Arial', 'U', 10);
$pdf->Text(4, 77, "Port d'embarquement");
$pdf->Text(56.5, 77, "Port de Destination");
$pdf->Text(109, 77, "Pays d'origine");
$pdf->Text(161.5, 77, "Monnaie de facturation");*/

$pdf->Rect(0,72,210,15,"DF");
$pdf->Line(70, 72, 70, 87);
$pdf->Line(135, 72, 135, 87);
//$pdf->Line(157.5, 72, 157.5, 87);
$pdf->SetFont('Arial', 'U', 10);
$pdf->Text(22, 77, "Port de Destination");
$pdf->SetFont('Arial', '', 10);
if(isset($_SESSION['port_dest'])) $pdf->Text(27, 83, $_SESSION['port_dest']);
$pdf->SetFont('Arial', 'U', 10);
$pdf->Text(90, 77, "Pays d'origine");
$pdf->SetFont('Arial', 'U', 10);
$pdf->Text(150, 77, "Monnaie de facturation");
$pdf->SetFont('Arial', '', 10);
if(isset($_SESSION['Monnaie'])) $pdf->Text(161, 84, $_SESSION['Monnaie']);

$DEVISE_SIGNE = '';
if($_SESSION['Monnaie']=="EURO") $DEVISE_SIGNE = '€';
else if($_SESSION['Monnaie']=="GBP") $DEVISE_SIGNE = '£';
else if($_SESSION['Monnaie']=="USD") $DEVISE_SIGNE = '$';
else if($_SESSION['Monnaie']=="DA") $DEVISE_SIGNE = "DA";

//tableau central
$pdf->SetFont('Arial', '', 10);
$pdf->Rect(0,92,210,5,"DF");
$pdf->Text(4, 95.5, "Ref");
$pdf->Text(40, 95.5, "Designation");
$pdf->Text(109, 95.5, "Origine");
$pdf->Text(140, 95.5, "Qte");
$pdf->Text(155.5, 95.5, "Prix Unitaire");
$pdf->Text(185, 95.5, "Prix Total");

$i=0;
while($i<15){
  $pdf->SetFillColor(255 , 255, 255);
  $pdf->Rect(0,97.2+(10*$i),210,5,"F");
  $pdf->SetFillColor(220 , 230, 242);
  $pdf->Rect(0,102.2+(10*$i),210,5,"F");
  $i++;
}
//101.5+(10*$j)
$TOTALPRICE=0.0;
$TOTALTRANSPORT=0.0;
$ORIGINES = "";
if(creationPanier()){

  $pdf->SetXY(2,97);
for($j = 0; $j < count($_SESSION['panier']['libelleProduit']); $j++)
   {
      $ref="";
      $i=0;
      for (; utf8_decode($_SESSION['panier']['libelleProduit'][$j][$i])!="/" ; $i++) { 
        $ref=$ref.$_SESSION['panier']['libelleProduit'][$j][$i];
      }
      $categorie="";
      $i++;
      for (; utf8_decode($_SESSION['panier']['libelleProduit'][$j][$i])!="/" ; $i++) { 
        $categorie=$categorie.$_SESSION['panier']['libelleProduit'][$j][$i];
      }
      $marque="";
      $i++;
      for (; utf8_decode($_SESSION['panier']['libelleProduit'][$j][$i])!="/" ; $i++) { 
        $marque=$marque.$_SESSION['panier']['libelleProduit'][$j][$i];
      }
      $type="";
      $i++;
      for (; utf8_decode($_SESSION['panier']['libelleProduit'][$j][$i])!="/" ; $i++) {
        $type=$type.$_SESSION['panier']['libelleProduit'][$j][$i];
      }
      $origine="";
      $i++;
      for (; utf8_decode($_SESSION['panier']['libelleProduit'][$j][$i])!="/" ; $i++) {
        $origine=$origine.$_SESSION['panier']['libelleProduit'][$j][$i];
      }
      $contenu="";
      $i++;
      for (; $i<strlen($_SESSION['panier']['libelleProduit'][$j]) ; $i++) {
        $contenu=$contenu.$_SESSION['panier']['libelleProduit'][$j][$i];
      }

      $pdf->Write(5,utf8_decode($ref));
      $pdf->SetX(110);
      $pdf->Write(5,$pays[intval($origine)]['alpha2']);
      $ORIGINES .= $pays[intval($origine)]['alpha2']."/";
      $pdf->SetX(140);
      $pdf->Write(5,$_SESSION['panier']['qteProduit'][$j]);
      $pdf->SetX(160);
      $pdf->Write(5,$_SESSION['panier']['prixProduit'][$j].iconv("UTF-8", "CP1252", $DEVISE_SIGNE));
      $pdf->SetX(180);
      $pdf->Write(5,floatval($_SESSION['panier']['prixProduit'][$j]*$_SESSION['panier']['qteProduit'][$j]).iconv("UTF-8", "CP1252", $DEVISE_SIGNE));
      $TOTALPRICE+=floatval($_SESSION['panier']['prixProduit'][$j]*$_SESSION['panier']['qteProduit'][$j]);
      $TOTALTRANSPORT+=floatval($_SESSION['panier']['prixTransport'][$j]*$_SESSION['panier']['qteProduit'][$j]);

      $pdf->SetFont('Arial', 'B', 10);
      $pdf->SetX(21);
      $pdf->Write(5,utf8_decode($categorie."\n"));
      $pdf->SetX(21);
      $pdf->Write(5,utf8_decode("Marque : ".$marque."  Type: ".$type."\n"));
      $pdf->SetFont('Arial', '', 9);
      $pdf->SetX(21);
      $pdf->MultiCell(77,5,utf8_decode($contenu));

      $pdf->SetX(21);
      $pdf->SetFont('Arial', 'B', 10);
      $pdf->Write(5,"Options:\n");
      //$pdf->SetFont('Arial', '', 9);
      for ($option=0; $option < nbOptions($_SESSION['panier']['libelleProduit'][$j]); $option++) { 
        if(qteOption($_SESSION['panier']['libelleProduit'][$j],$option+1)>0){
          $pdf->SetFont('Arial', '', 9);
          $pdf->SetX(21);
          $pdf->MultiCell(77,5,utf8_decode($options[$option]['Nom']));
          $pdf->SetX(140);
          $pdf->Write(-5,qteOption($_SESSION['panier']['libelleProduit'][$j],$option+1));
          $pdf->SetFont('Arial', '', 10);
          $pdf->SetX(160);
          $pdf->Write(-5,utf8_decode($options[$option]['Prix']).iconv("UTF-8", "CP1252", $DEVISE_SIGNE));
          $pdf->SetX(185);
          $pdf->Write(-5,floatval(utf8_decode($options[$option]['Prix'])*qteOption($_SESSION['panier']['libelleProduit'][$j],$option+1)).iconv("UTF-8", "CP1252", $DEVISE_SIGNE));
          $TOTALPRICE+=floatval(utf8_decode($options[$option]['Prix'])*qteOption($_SESSION['panier']['libelleProduit'][$j],$option+1));
          $TOTALTRANSPORT+=floatval(utf8_decode($options[$option]['prix_transport'])*qteOption($_SESSION['panier']['libelleProduit'][$j],$option+1));
        }
      }
      $j++;
}
$ORIGINES = substr($ORIGINES,0,-1);
$pdf->Text(80, 83,  $ORIGINES);

$pdf->Line(135, 92, 135, 247);
$pdf->Line(150, 92, 150, 247);
$pdf->Line(180, 92, 180, 247);
$pdf->Line(0, 247, 210, 247);

//Paiement
$pdf->Rect(0,252,90,22,"DF");
$pdf->SetFont('Arial', '', 10);
$pdf->Text(2, 256, "Paiement : 100% par Lettre de Credit Irrevocable et");
$pdf->Text(2, 261, "Confirmee par notre banque CIC :  14 av de l'Europe ");
$pdf->Text(2, 266, "77144 Montevrain France          BIC : CMCIFRPP");
$pdf->Text(2, 271, "IBAN : FR76 3008 7338 3100 0336 6011 434  ");

//Total
$pdf->Rect(120,252,90,22,"DF");
$pdf->Text(122, 256, "Total Marchandise H.T.");
$pdf->Text(180, 256, ":      ".$TOTALPRICE.iconv("UTF-8", "CP1252", $DEVISE_SIGNE));
$pdf->Text(122, 261, "Total Transport");
$pdf->Text(180, 261, ":      ".$TOTALTRANSPORT.iconv("UTF-8", "CP1252", $DEVISE_SIGNE));
$pdf->Text(122, 266, "Total CFR Port de");
$pdf->Text(180, 266, ":      ".($TOTALPRICE+$TOTALTRANSPORT).iconv("UTF-8", "CP1252", $DEVISE_SIGNE));

$pdf->Rect(0,280,210,15,"DF");
$pdf->Text(2, 284, "Sarl Integral Trading  : Capital 10 000".iconv("UTF-8", "CP1252", $DEVISE_SIGNE)."       Numero Intra CEE FR21 418 515 136        RCS Compi".utf8_decode("è")."gne N".utf8_decode("°")."");
$pdf->Text(2, 289, "Code APE :");
}
if($code == true) return $pdf->Output('S',"bon de commande");
else if($code == false) $pdf->Output('I',"bon de commande");
}
?>