<!DOCTYPE html>
<html>
<head>
	<title>Reciept For <?php echo $patient_name; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">


	
	<?php if($facility_name == "Everight Diagnostic & Laboratory Services LTD"){ ?>
	<style>
		body{
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			background: #E8E8E8;
		}
		.container{
			padding: 30px;
			padding-top: 50px;
			background: #fff;
			border-radius: 5px;
			width: 800px;
			margin: 10px auto;
			
		}
		#facility-logo-img{
			height: 50px;
			width: 10%;
			display: inline-block;
			margin-bottom: 40px;
		}
		
		#company-name-div{
			width: 60%;
			display: inline-block;
			float: right
		}

		#company-name-div #company-logo{
			width: 40px;
			/*float: right;*/
		}

		#company-name-div #company-name{
			font-weight: bolder;
		    display: block;
		    text-align: right;
		    font-size: 13px;
		    /* width: 50%; */
		    /* float: right; */
		    margin: 0;

		    /* margin-right: 10px; */
		}
		#top-row-cont{
			width: 100%;
		}
		.facility-info-div{
			margin-top: -22px;
		}
		.facility-name{
			font-weight: bolder;
		    font-size: 75px;
		    margin-bottom: 10px;
		    text-transform: capitalize;
		}
		.state-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline;
		}
		.state-name{
			font-weight: bold;
			font-size: 54px;
			display: inline;
			font-style: unset;
			
		}
	
		.date-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline;
		}

		.date-value{
			font-size: 54px;
			display: inline;
			font-style: unset;
			font-weight: bold;
		}

		.address-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline;
		}

		.address-value{
			font-size: 54px;
			display: inline;
			font-style: unset;
			font-weight: bold;
		}

		#receipt-title{
			font-weight: bolder;
			text-align: center;
			font-size: 50px;
			margin-bottom: 10px;
			margin-top: 25px;
		}

		#receipt-subtitle{
			font-style: unset;
			/*text-align: center;*/
			font-size: 54px;
			font-weight: bold;
			margin-top: 0;
		}

		#patient-name{
			font-size: 18px;
			font-weight: bolder;
		}

		/*table, th, td {
		  border: 1px solid black;
		}*/

		#tests-table {
			margin-top: 30px;
		  margin-bottom: 30px;
		  border-collapse: collapse;
		  width: 100%;
		  font-size: 13px;
		}

		#tests-table td, #tests-table th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}

		#tests-table tr:nth-child(even){background-color: #f2f2f2;}

		#tests-table tr:hover {background-color: #ddd;}

		#tests-table th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  /*background-color: #4CAF50;
		  color: white;*/
		}

		.pathology-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline; 
		}

		.pathology-number{
			font-size: 54px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.sub-total-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline; 
		}

		.sub-total-name{
			font-size: 54px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.initiation-code-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline; 
		}

		.initiation-code-name{
			font-size: 54px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.mode-of-payment-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline; 
		}

		.mode-of-payment-name{
			font-size: 54px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		
		.receipt-number-placeholder{
			font-weight: bolder;
			font-size: 60px;
			display: inline; 
		}

		.receipt-number-name{
			font-size: 54px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.credit-us{
			font-weight: bolder;
			font-size: 18px;
		}
		
		.credit{
			font-weight: bolder;
			font-size: 20px;
		}


		.our-email{
			font-size: 10px;
			color: #0277BD;
		}

		#test-image-result-heading{
			text-align: center;
			margin-top: 70px;
			margin-bottom: 40px;
			text-decoration: underline;
		}

		.test-image{
			max-width: 400px;
			height: 300px;
			margin-bottom: 40px;
		}

		#contributors-heading{
			text-align: center;
			font-size: 54px;
		}

		
		.receptionist-placeholder{
			font-weight: bolder;
			font-size: 54px;
			display: inline; 
		}

		.receptionist-name{
			font-size: 16px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.teller-placeholder{
			font-weight: bolder;
			font-size: 54px;
			display: inline; 
		}

		.teller-name{
			font-size: 16px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.clerk-placeholder{
			font-weight: bolder;
			font-size: 54px;
			display: inline; 
		}

		.clerk-name{
			font-size: 16px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.laboratory-officer-3-placeholder{
			font-weight: bolder;
			font-size: 16px;
			display: inline; 
		}

		.laboratory-officer-3-name{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		
		.laboratory-officer-2-placeholder{
			font-weight: bolder;
			font-size: 16px;
			display: inline; 
		}

		.laboratory-officer-2-name{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.consultation-pathologist-placeholder{
			font-weight: bolder;
			font-size: 16px;
			display: inline; 
		}

		.consultation-pathologist-name{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.pathologists-comment-cont p{
			/*width: 50%;*/
			font-size: 13px;
		}

		#pathologists-comment-heading{
			text-align: left;
			font-size: 54px;
		}

		#patient-name-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-name{
			font-size: 54px;
			font-weight: bolder;
			font-style: unset;
			display: inline;
		}

		#patient-age-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-age{
			font-size: 13px;
			font-weight: bolder;
			font-style: unset;
			display: inline;
		}
		#patient-sex-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-sex{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		#patient-address-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-address{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		#patient-referring-dr-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-referring-dr{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		table th{
			font-weight: 800;
    		font-size: 54px;
		}

		table td{
			font-weight: 600;
    		font-size: 48px;
		}

		.bold{
			font-weight: bolder;
		}
	</style>

	<?php }else{ ?>
	<style>
		body{
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			background: #E8E8E8;
		}
		.container{
			padding: 30px;
			padding-top: 50px;
			background: #fff;
			border-radius: 5px;
			width: 800px;
			margin: 10px auto;
			
		}
		#facility-logo-img{
			height: 50px;
			width: 10%;
			display: inline-block;
			margin-bottom: 40px;
		}
		
		#company-name-div{
			width: 60%;
			display: inline-block;
			float: right
		}

		#company-name-div #company-logo{
			width: 40px;
			/*float: right;*/
		}

		#company-name-div #company-name{
			font-weight: bolder;
		    display: block;
		    text-align: right;
		    font-size: 13px;
		    /* width: 50%; */
		    /* float: right; */
		    margin: 0;
		    /* margin-right: 10px; */
		}
		#top-row-cont{
			width: 100%;
		}
		.facility-info-div{
			margin-top: -22px;
		}
		.facility-name{
			font-weight: bolder;
		    font-size: 25px;
		    margin-bottom: 10px;
		    text-transform: capitalize;
		}
		.state-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline;
		}
		.state-name{
			font-weight: bold;
			font-size: 18px;
			display: inline;
			font-style: unset;
			
		}
	
		.date-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline;
		}

		.date-value{
			font-size: 18px;
			display: inline;
			font-style: unset;
			font-weight: bold;
		}

		.address-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline;
		}

		.address-value{
			font-size: 18px;
			display: inline;
			font-style: unset;
			font-weight: bold;
		}

		#receipt-title{
			font-weight: bolder;
			text-align: center;
			font-size: 25px;
			margin-bottom: 10px;
			margin-top: 25px;
		}

		#receipt-subtitle{
			font-style: unset;
			/*text-align: center;*/
			font-size: 20px;
			font-weight: bold;
			margin-top: 0;
		}

		#patient-name{
			font-size: 18px;
			font-weight: bolder;
		}

		/*table, th, td {
		  border: 1px solid black;
		}*/

		#tests-table {
			margin-top: 30px;
		  margin-bottom: 30px;
		  border-collapse: collapse;
		  width: 100%;
		  font-size: 13px;
		}

		#tests-table td, #tests-table th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}

		#tests-table tr:nth-child(even){background-color: #f2f2f2;}

		#tests-table tr:hover {background-color: #ddd;}

		#tests-table th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  /*background-color: #4CAF50;
		  color: white;*/
		}

		.pathology-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline; 
		}

		.pathology-number{
			font-size: 18px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.sub-total-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline; 
		}

		.sub-total-name{
			font-size: 18px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.initiation-code-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline; 
		}

		.initiation-code-name{
			font-size: 18px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.mode-of-payment-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline; 
		}

		.mode-of-payment-name{
			font-size: 18px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		
		.receipt-number-placeholder{
			font-weight: bolder;
			font-size: 20px;
			display: inline; 
		}

		.receipt-number-name{
			font-size: 18px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.credit-us{
			font-weight: bolder;
			font-size: 18px;
		}
		
		.credit{
			font-weight: bolder;
			font-size: 20px;
		}


		.our-email{
			font-size: 10px;
			color: #0277BD;
		}

		#test-image-result-heading{
			text-align: center;
			margin-top: 70px;
			margin-bottom: 40px;
			text-decoration: underline;
		}

		.test-image{
			max-width: 400px;
			height: 300px;
			margin-bottom: 40px;
		}

		#contributors-heading{
			text-align: center;
			font-size: 54px;
		}

		
		.receptionist-placeholder{
			font-weight: bolder;
			font-size: 54px;
			display: inline; 
		}

		.receptionist-name{
			font-size: 16px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.teller-placeholder{
			font-weight: bolder;
			font-size: 54px;
			display: inline; 
		}

		.teller-name{
			font-size: 16px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.clerk-placeholder{
			font-weight: bolder;
			font-size: 54px;
			display: inline; 
		}

		.clerk-name{
			font-size: 16px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.laboratory-officer-3-placeholder{
			font-weight: bolder;
			font-size: 16px;
			display: inline; 
		}

		.laboratory-officer-3-name{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		
		.laboratory-officer-2-placeholder{
			font-weight: bolder;
			font-size: 16px;
			display: inline; 
		}

		.laboratory-officer-2-name{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.consultation-pathologist-placeholder{
			font-weight: bolder;
			font-size: 16px;
			display: inline; 
		}

		.consultation-pathologist-name{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		.pathologists-comment-cont p{
			/*width: 50%;*/
			font-size: 13px;
		}

		#pathologists-comment-heading{
			text-align: left;
			font-size: 54px;
		}

		#patient-name-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-name{
			font-size: 18px;
			font-weight: bolder;
			font-style: unset;
			display: inline;
		}

		#patient-age-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-age{
			font-size: 13px;
			font-weight: bolder;
			font-style: unset;
			display: inline;
		}
		#patient-sex-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-sex{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		#patient-address-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-address{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}
		#patient-referring-dr-placeholder{
			font-size: 15px;
			display: inline;
		}
		#patient-referring-dr{
			font-size: 13px;
			font-weight: bold;
			font-style: unset;
			display: inline;
		}

		table th{
			font-weight: 800;
    		font-size: 20px;
		}

		table td{
			font-weight: 600;
    		font-size: 18px;
		}

		.bold{
			font-weight: bolder;
		}
	</style>
	<?php } ?>

	
</head>
<body>
	<div class="container">
		<div class="top-row-cont">
			<?php
		
			 	// if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($facility_id)){
			?>
			<?php if($logo != ""){ ?>
			<img src="<?php echo $logo['src'];  ?>" id="facility-logo-img">
			<?php }else{ ?>
			<img avatar="<?php echo $facility_name; ?>" col="<?php echo $color; ?>" id="facility-logo-img">
			<?php } ?>
			<?php  ?>	
			<!-- <div id="company-name-div" align="right">
				<img src="<?php echo base_url('assets/images/logo_small.jpg'); ?>" id="company-logo" alt="">
				<h2 id="company-name">OneHealth Issues Global Limited</h2>
			</div> -->
		</div>
		<?php
		
		 	// if($this->onehealth_model->checkIfFacilityHasLetterHeadingEnabled($facility_id)){
		?>
		<div class="facility-info-div">
			<h3 class="facility-name"><?php echo $facility_name;?></h3>
			<h3 class="state-placeholder">State : </h3>
			<h3 class="state-name"><?php echo $facility_state . ", " .$facility_country; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="date-placeholder">Date :</h3>
			<h3 class="date-value"> <?php echo $date; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="address-placeholder">Address :</h3>
			<h3 class="address-value"><?php echo $facility_address; ?></h3>

		</div>
		<?php
			// }
		?>


		<h3 id="receipt-title">INVOICE FOR MEDICAL SERVICES</h3>
		<h3 id="receipt-subtitle">We Confirm Receipt Of Payment For: </h3>

		<h3 id="patient-name"><?php  echo $patient_name; ?></h3>
		<?php if(!isset($clinic)){ ?>
		<table id="tests-table">
			<thead>
				<tr>
					<th>#</th>
					<th>TEST ID</th>
					<th>TEST NAME</th>
					<!-- <th>TEST COST</th> -->
				</tr>
			</thead>
			<tbody>
				<?php
					$response = "";
					for($i = 0; $i < count($tests); $i++){
						$index = $tests[$i]['i'];
						$test_id = $tests[$i]['testid'];
						$testname = $tests[$i]['testname'];
						$testcost = $tests[$i]['testcost'];
						$response .= "<tr>";
						$response .= "<td>";
						$response .= $index;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $test_id;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $testname;
						$response .= "</td>";
						// $response .= "<td>";
						// $response .= $testcost;
						// $response .= "</td>";
						$response .= "<tr>";
					}
					echo $response;
				?>
			</tbody>

		</table>
		<?php } ?>

		<?php if(isset($drugs)){ ?>
		<table id="tests-table">
			<thead>
				<tr>
					<th>#</th>
					<th>GENERIC NAME</th>
					<th>BRAND NAME</th>
					<th>FORMULATION</th>
					<th>QUANTITY</th>
					<th>DRUG COST</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$response = "";
					$j = 0;
					for($i = 0; $i < count($drugs); $i++){
						$j++;
						$index = $j;
						$generic_name = $drugs[$i]['generic_name'];
						$brand_name = $drugs[$i]['brand_name'];
						$formulation = $drugs[$i]['formulation'];
						$drug_quantity = $drugs[$i]['drug_quantity'];
						$drug_cost = $drugs[$i]['drug_cost'];
						$response .= "<tr>";
						$response .= "<td>";
						$response .= $index;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $generic_name;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $brand_name;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $formulation;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $drug_quantity;
						$response .= "</td>";
						$response .= "<td>";
						$response .= $drug_cost;
						$response .= "</td>";
						$response .= "<tr>";
					}
					echo $response;
				?>
			</tbody>

		</table>
		<?php } ?>
		
		<?php if(!isset($clinic)){ ?>
		<div class="footer-right-div" align="left">
			<h3 class="pathology-placeholder">Pathology Number: </h3>
			<h3 class="pathology-number"><?php echo $lab_id; ?></h3>
			<div class="" style="height: 5px"></div>
			<?php if(!isset($is_teller)){ ?>
			<h3 class="sub-total-placeholder">Sub Total: </h3>
			<h3 class="sub-total-name"><?php echo $sum; ?></h3>
			<div class="" style="height: 5px"></div>
			<?php }else{ ?>
			<h3 class="sub-total-placeholder">Amount Paid: </h3>
			<h3 class="sub-total-name"><?php echo $sum; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="sub-total-placeholder">Total Amount Paid: </h3>
			<h3 class="sub-total-name"><?php echo $total_amount_paid; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="sub-total-placeholder">Total Cost Of Test(s): </h3>
			<h3 class="sub-total-name"><?php echo $total_price; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="sub-total-placeholder">Balance: </h3>
			<h3 class="sub-total-name"><?php echo $balance; ?></h3>
			<div class="" style="height: 5px"></div>
			<?php } ?>
			<h3 class="initiation-code-placeholder">Initiation Code: </h3>
			<h3 class="initiation-code-name"> <?php echo $initiation_code; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="mode-of-payment-placeholder">Mode Of Payment: </h3>
			<h3 class="mode-of-payment-name" style="text-transform: capitalize;"><?php echo $mod; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="receipt-number-placeholder">Receipt Number: </h3>
			<h3 class="receipt-number-name"><?php echo $receipt_number; ?></h3>
		</div>
		<?php } else{
			if(!isset($drugs)){
		?>
		<div class="footer-right-div" align="left">
			
			<h3 class="sub-total-placeholder">Amount Paid: </h3>
			<h3 class="sub-total-name"><?php echo $sum; ?></h3>
			<div class="" style="height: 5px"></div>
			<?php if(isset($balance)){ ?>
			<h3 class="sub-total-placeholder">Balance: </h3>
			<h3 class="sub-total-name"><?php echo $balance; ?></h3>
			<?php } ?>
			<div class="" style="height: 5px"></div>
			<?php if(isset($mortuary)){ ?>
			<h3 class="initiation-code-placeholder">Mortuary Number: </h3>
			<?php } ?>
			<h3 class="initiation-code-name"> <?php echo $hospital_number; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="mode-of-payment-placeholder">Mode Of Payment: </h3>
			<h3 class="mode-of-payment-name"><?php echo $mod; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="receipt-number-placeholder">Receipt Number: </h3>
			<h3 class="receipt-number-name"><?php echo $receipt_number; ?></h3>
		</div>
		<?php
		}else{
		?>
		<div class="footer-right-div" align="left">
			
			<h3 class="sub-total-placeholder">Amount Paid: </h3>
			<h3 class="sub-total-name"><?php echo number_format($sum,2); ?></h3>
			<div class="" style="height: 5px"></div>
			<?php if(isset($balance)){ ?>
			<h3 class="sub-total-placeholder">Balance: </h3>
			<h3 class="sub-total-name"><?php echo $balance; ?></h3>
			<?php } ?>
			<div class="" style="height: 5px"></div>
			<h3 class="initiation-code-placeholder">Discount: </h3>
			<h3 class="initiation-code-name"> <?php echo $discount; ?>%</h3>
			<div class="" style="height: 5px"></div>
			<h3 class="mode-of-payment-placeholder">Mode Of Payment: </h3>
			<h3 class="mode-of-payment-name"><?php echo $mod; ?></h3>
			<div class="" style="height: 5px"></div>
			<h3 class="receipt-number-placeholder">Receipt Number: </h3>
			<h3 class="receipt-number-name"><?php echo $receipt_number; ?></h3>
		</div>
		<?php
		}
		 } ?>

		<?php if($facility_name != "Everight Diagnostic & Laboratory Services LTD"){ ?>
		<div class="footer-center" align="center" style="margin-top: 30px">
			<h3 class="credit">For <?php echo $facility_name; ?></h3>
			<h3 class="credit-us">OneHealth Issues Global Limited</h3>
			<p class="our-email">support@onehealthissues.com</p>
		</div>

		<?php } ?>



	</div>
</body>
<script src="<?php echo base_url('assets/js/letter_avatar.js') ?>"></script>
</html>