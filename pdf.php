<?php
session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 

require('fonctions_panier.php');
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);
$pdf->Image('logos/logo integral_origin.jpg',5,4,-300);
$pdf->SetFillColor(220 , 230, 242);
$pdf->SetDrawColor(0 , 0, 0);

$pdf->Text(100, 15, "Sarl INTEGRAL TRADING");
$pdf->Text(100, 20, "3 CHEMIN DES REMISES 60410 VERBERIE, FRANCE");
$pdf->Text(100, 25, "Tel: 00 333 444 111 33      Email:info@integral.sarl");

//Premier rectangle
$pdf->Rect(0,37,90,30,"DF");
$pdf->Line(0, 47, 90, 47);
$pdf->Line(50, 37, 50, 47);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Text(4.5, 43.5, "FACTURE PROFORMA");
$pdf->SetFont('Arial', '', 11);
$pdf->Text(54.5, 43.5, "Numero Proforma");
$pdf->SetFont('Arial', '', 10);
$pdf->Text(2, 50.5, "Date Proforma:   ".date("d/m/Y"));
$pdf->Text(2, 55.5, "Date de validite:");
$pdf->Text(2, 60.5, "Delai de livraison:");
$pdf->Text(2, 65.5, "Emis et valide par:   ".$_SESSION['prenom']." ".$_SESSION['nom']);

$pdf->Text(195, 36, $_SESSION['CodeClient']);
//Deuxieme Rectangle
$pdf->Rect(120,37,90,30,"DF");
$pdf->Text(122, 40.5, $_SESSION['NomClient']);
$pdf->Text(122, 45.5, $_SESSION['AdresseClient']);
$pdf->Text(122, 50.5, "Numero NIF: ".$_SESSION['NIF']);
$pdf->Text(122, 55.5, $_SESSION['CodePostalClient']);
$pdf->Text(155, 55.5, $_SESSION['VilleClient']);
$pdf->Text(190, 55.5, $_SESSION['WilayaClient']);
$pdf->Text(122, 60.5, "ALGERIE");
//$pdf->Text(122, 65.5, MontantGlobal());

//Bande Transport
$pdf->Rect(0,72,210,15,"DF");
$pdf->Line(52.5, 72, 52.5, 87);
$pdf->Line(105, 72, 105, 87);
$pdf->Line(157.5, 72, 157.5, 87);
$pdf->SetFont('Arial', 'U', 10);
$pdf->Text(4, 77, "Port d'embarquement");
$pdf->Text(56.5, 77, "Port de Destination");
$pdf->Text(109, 77, "Pays d'origine");
$pdf->Text(161.5, 77, "Monnaie de facturation");

//tableau central
$pdf->SetFont('Arial', '', 10);
$pdf->Rect(0,92,210,5,"DF");
$pdf->Text(4, 95.5, "Reference");
$pdf->Text(40, 95.5, "Designation");
$pdf->Text(109, 95.5, "Origine");
$pdf->Text(140, 95.5, "Qte");
$pdf->Text(155.5, 95.5, "Prix Unitaire");
$pdf->Text(185, 95.5, "Prix Total");

$i=0;
while($i<13){
	$pdf->SetFillColor(255 , 255, 255);
	$pdf->Rect(0,97.2+(10*$i),210,5,"F");
	$pdf->SetFillColor(220 , 230, 242);
	$pdf->Rect(0,102.2+(10*$i),210,5,"F");
	$i++;
}
//101.5+(10*$j)
$pdf->SetXY(2,97);
for($j = 0; $j < count($_SESSION['panier']['libelleProduit']); $j++)
   {
   //	$pdf->Write(5,utf8_decode($_SESSION['panier']['libelleProduit'][$j]));
	$pdf->Write(5,utf8_decode($_SESSION['panier']['libelleProduit'][$j]));
	$j++;
}

$pdf->Line(135, 92, 135, 227);
$pdf->Line(150, 92, 150, 227);
$pdf->Line(180, 92, 180, 227);
$pdf->Line(0, 227, 210, 227);

//Paiement
$pdf->Rect(0,232,90,22,"DF");
$pdf->SetFont('Arial', '', 10);
$pdf->Text(2, 236, "Paiement : 100% par Lettre de Credit Irrevocable et");
$pdf->Text(2, 241, "Confirmee par notre banque CIC :  14 av de l'Europe ");
$pdf->Text(2, 246, "77144 Montevrain France          BIC : CMCIFRPP");
$pdf->Text(2, 251, "IBAN : FR76 3008 7338 3100 0336 6011 434  ");

//Total
$pdf->Rect(120,232,90,22,"DF");
$pdf->Text(122, 236, "Total Marchandise H.T.");
$pdf->Text(180, 236, ":      ".MontantGlobal().iconv("UTF-8", "CP1252", "€"));
$pdf->Text(122, 241, "Total Transport");
$pdf->Text(180, 241, ":58");
$pdf->Text(122, 246, "Total CFR Port de");
$pdf->Text(180, 246, ":58");

$pdf->Rect(0,260,210,15,"DF");
$pdf->Text(2, 264, "Sarl Integral Trading  : Capital 10 000".iconv("UTF-8", "CP1252", "€")."       Numero Intra CEE FR21 418 515 136        RCS Compi".utf8_decode("è")."gne N".utf8_decode("°")."");
$pdf->Text(2, 269, "Code APE :");

$pdf->Output();
?>