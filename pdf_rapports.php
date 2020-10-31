<?php
require('fonctions_panier.php');
require('fpdf/fpdf.php');
function getRapportPDF(){
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial', '', 11);
	$pdf->Image('logos/logo integral_origin.jpg',5,4,-300);
	$pdf->SetFillColor(220 , 230, 242);
	$pdf->SetDrawColor(0 , 0, 0);

	$pdf->Text(100, 15, "Sarl INTEGRAL TRADING");
	$pdf->Text(100, 20, "3 CHEMIN DES REMISES 60410 VERBERIE, FRANCE");
	$pdf->Text(100, 25, "Tel: 00 333 444 111 33      Email: info@integral.sarl");

	//Premier rectangle
	$pdf->Rect(0,37,210,18,"DF");
	$pdf->Line(0, 43, 210, 43);
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Text(90, 41.5, "RAPPORT");
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetFont('Arial', '', 10);
	$pdf->Text(2, 47.5, "Date Rapport:   ".date("d/m/Y"));
	$pdf->Text(2, 52.5, "Emis par:   ".$_SESSION['prenom']." ".$_SESSION['nom']);


	$pdf->SetXY(2,50);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Commandes: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['commandes']);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Visites Clients: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['visites']);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Offres: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['offres']);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Remarques: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['remarques']);
	$pdf->Write(10,"\n");

	if(intval($_SESSION['id_compte'])<10) $pdf->Output('F',"rapports/".$_SESSION['currentRapport'].".pdf");
	else $pdf->Output('F',"proformas/".$_SESSION['currentRapport'].".pdf"); 
	//return $pdf->Output('I',"rapport");
}

function getRapportCode(){
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial', '', 11);
	$pdf->Image('logos/logo integral_origin.jpg',5,4,-300);
	$pdf->SetFillColor(220 , 230, 242);
	$pdf->SetDrawColor(0 , 0, 0);

	$pdf->Text(100, 15, "Sarl INTEGRAL TRADING");
	$pdf->Text(100, 20, "3 CHEMIN DES REMISES 60410 VERBERIE, FRANCE");
	$pdf->Text(100, 25, "Tel: 00 333 444 111 33      Email: info@integral.sarl");

	//Premier rectangle
	$pdf->Rect(0,37,210,18,"DF");
	$pdf->Line(0, 43, 210, 43);
	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Text(90, 41.5, "RAPPORT");
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetFont('Arial', '', 10);
	$pdf->Text(2, 47.5, "Date Rapport:   ".date("d/m/Y"));
	$pdf->Text(2, 52.5, "Emis par:   ".$_SESSION['prenom']." ".$_SESSION['nom']);


	$pdf->SetXY(2,50);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Commandes: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['commandes']);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Visites Clients: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['visites']);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Offres: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['offres']);
	$pdf->Write(10,"\n");

	$pdf->SetFont('Arial', 'B', 11);
	$pdf->Write(5,"Remarques: ");
	$pdf->SetFont('Arial', '', 11);
	$pdf->Write(5,$_SESSION['remarques']);
	$pdf->Write(10,"\n");

	return $pdf->Output('S');
}
?>