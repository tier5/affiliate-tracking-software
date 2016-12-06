{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$char_set}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>{$sitename} - {$header_title}</title>
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="templates/source/common/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="templates/themes/{$active_theme}/css/fonts.css">
	<link rel="stylesheet" type="text/css" href="templates/source/common/font-awesome/font-awesome.css">
	<link rel="stylesheet" href="templates/themes/{$active_theme}/css/footable.min.css">
	<link rel="stylesheet" type="text/css" href="templates/themes/{$active_theme}/css/style.css">	

	<!--[if lte IE 8]>
	<link rel="stylesheet" href="templates/source/common/css/ie-fix.css" />
	<![endif]-->
	
	<!--[if lt IE 9]>
	{literal}
    <script type="text/javascript" src="templates/source/common/js/html5shiv.js"></script>
    <script type="text/javascript" src="templates/source/common/js/respond.min.js"></script>
	{/literal}
    <![endif]-->
	
</head>

<body style="margin-top:50px;">
	<div id="wrapper">
		<div id="main-container">		

				<!-- BEGIN MAIN PAGE CONTENT -->
				<div id="page-wrapper" class="collapsed">
					
						<div class="row">
							<div class="col-lg-12">
							
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<div class="well white padding-25">
											<div class="pull-left">
{if isset($main_logo)}<a href="index.php" class="brand"><img style="border:none; height:25px; width:150px;" src="{$main_logo}" alt="{$sitename} - {$header_title}"></a>{/if}
											</div>
											<div class="pull-right"><a href="#" onClick="window.print();return false;"><i class="fa fa-print fa-lg"></i></a></div>
											
											<div class="clearfix"></div>
											
											<div class="hr hr-double hr-dotted hr-12"></div>
											
											<div class="row">
												<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
													<p class="bigger-110">
														{$comdetails_date}: {$invoice_date}<br />
														{$invoice_comm_amt}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$invoice_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}
													
													{if isset($revised_numbers)}	
													<br />{$debit_invoice_amount}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$pexacttotaldebs}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}
													<br /><font color="#CC0000">{$debit_revised_amount}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$revised_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</font>
													{/if}
													
													{if isset($vat_amount)}
														<br />{$vat_amount_heading}: {if $cur_sym_location == 1}{$cur_sym}{/if}{$vat_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}
													{/if}
													
													</p>
												</div>																						
												<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
													<div class="text-right">
														<span class="label label-xlg arrowed-in-right arrowed-in label-danger">{$invoice_header}</span>
													</div>
												</div>
											</div>
										
											<div class="space-16"></div>
										
											<div class="row">
												<div class="col-sm-8">
													<h4 class="text-primary">{$sitename} {$invoice_co_info}</h4>
													<div class="hr hr-4 hr-dotted"></div>
													<div class="row">
														<div class="col-lg-6">
															<address>
																<strong>{$invoice_our_company}</strong>
																{$invoice_our_address1}{$invoice_our_address2}{$invoice_our_city}{$invoice_our_state}{$invoice_our_zip}{$signup_personal_country}: {$invoice_our_country}

															</address>
														</div>
														<div class="col-lg-6">
															<p>
																<strong>{$invoice_aff_id}:</strong> {$invoice_affiliate_id}<br />
																<strong>{$invoice_aff_user}:</strong> {$invoice_username}<br />
																<strong>{$edit_personal_phone}:</strong> {$invoice_phone}
															</p>
															<div class="space-4"></div>
															<p>
																<strong>{$edit_standard_taxinfo}:</strong> {$invoice_taxinfo}
															</p>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<h4 class="text-primary">{$invoice_aff_info}</h4>
													<div class="hr hr-4 hr-dotted"></div>
													<address>
{$invoice_affiliate_payto}{$invoice_affiliate_fname}{$invoice_affiliate_lname}{$invoice_affiliate_address1}{$invoice_affiliate_address2}{$invoice_affiliate_city}{$invoice_affiliate_state}{$invoice_affiliate_zip}{$signup_personal_country}: {$invoice_affiliate_country}
													</address>												
												</div>
											</div>
{if isset($revised_numbers)}											
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th width="20%">{$debit_date_label}</th>
														<th width="60%">{$debit_reason_label}</th>
														<th width="20%" style="text-align:right;">{$debit_amount_label}</th>

													</tr>
												</thead>
												<tbody>
												
{section name=nr loop=$debit_results}
<tr>
<td width="20%">{$debit_results[nr].debit_date_table}</td>
<td width="60%">{$debit_results[nr].debit_reason_table}</td>
<td width="20%" style="text-align:right;">{if $cur_sym_location == 1}{$cur_sym}{/if}{$debit_results[nr].debit_amount_table}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
</tr>
{/section}

											</table>
{/if}										
										
										
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th width="20%">{$comdetails_date}</th>
														<th width="60%">{$invoice_comm_type}</th>
														<th width="20%" style="text-align:right;">{$invoice_comm_amt}</th>
													</tr>
												</thead>
												<tbody>
												
{section name=nr loop=$payment_results}
<tr>
<td width="20%">{$payment_results[nr].payment_individual_date}</td>
<td width="60%">{$payment_results[nr].payment_individual_type}</td>
<td width="20%" style="text-align:right;">{if $cur_sym_location == 1}{$cur_sym}{/if}{$payment_results[nr].payment_individual_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</td>
</tr>
{/section}

											</table>
										
											<div class="row">

											
												<div class="col-lg-12 pull-right">

													<p class="text-right bigger-150">{$invoice_comm_amt}:<span class="text-danger"> {if $cur_sym_location == 1}{$cur_sym}{/if}{$revised_amount}{if $cur_sym_location == 2} {$cur_sym}{/if} {$currency}</span></p>
												</div>
												<div class="clearfix"></div>
											</div>
										
											<hr class="separator" />
										
											<div class="well light text-center">
												{$invoice_note}
											</div>
										</div>									
									</div>
								</div>

					
							</div>							
						</div>					

				</div><!-- /#page-wrapper -->	  
			<!-- END MAIN PAGE CONTENT -->
		</div>  
	</div> 
	 
  	{literal}
	<script type="text/javascript" src="templates/source/lightbox/js/jquery-1.11.1.min.js"></script>
    <script src="templates/source/common/bootstrap/js/bootstrap.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery.slimscroll.min.js"></script>
	<script src="templates/source/common/pace/js/pace.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/footable.min.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/jquery.slimscroll.init.js"></script>
	<script src="templates/themes/{/literal}{$active_theme}{literal}/js/footable.init.js"></script>
	{/literal}
	
  </body>
</html>