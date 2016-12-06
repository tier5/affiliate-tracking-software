jQuery(function($){
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    function getParameterByNamew(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^?#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    
    if($('#dyntable_commission_list').length > 0) {
        $('#dyntable_commission_list').dataTable({
            "bProcessing"	: true,
            "bServerSide"	: true,
            "sAjaxSource"	: "templates/internals/core_ajax_report.php?report=" + getParameterByName('report'), 
            "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 3 ]}], 
	   		"aaSorting"     : [[ 0, "desc" ]],
            "bFilter"           : true,
            "oLanguage": {
			  	"sEmptyTable":     langDataTable["sEmptyTable"],
				"sInfo":           langDataTable["sInfo"],
				"sInfoEmpty":      langDataTable["sInfoEmpty"],
				"sInfoFiltered":   langDataTable["sInfoFiltered"],
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     langDataTable["sLengthMenu"],
				"sLoadingRecords": langDataTable["sLoadingRecords"],
				"sProcessing":     langDataTable["sProcessing"],
				"sSearch":         langDataTable["sSearch"],
				"sZeroRecords":    langDataTable["sZeroRecords"],
				"oPaginate": {
					"sFirst":    langDataTable["sFirst"],
					"sLast":     langDataTable["sLast"],
					"sNext":     langDataTable["sNext"],
					"sPrevious": langDataTable["sPrevious"]
				},
				"oAria": {
					"sSortAscending":  langDataTable["sSortAscending"],
					"sSortDescending": langDataTable["sSortDescending"]
				}
			}
        });
    }
    if($('#dyntable_commission_list_subs').length > 0) {
        $('#dyntable_commission_list_subs').dataTable({
            "bProcessing"	: true,
            "bServerSide"	: true,
            "sAjaxSource"	: "templates/internals/core_ajax_report.php?report=" + getParameterByName('report'),  
            "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 4 ]}],
	   		"aaSorting"     : [[ 0, "desc" ]],
            "bFilter"           : true,
	   		"oLanguage": {
			  	"sEmptyTable":     langDataTable["sEmptyTable"],
				"sInfo":           langDataTable["sInfo"],
				"sInfoEmpty":      langDataTable["sInfoEmpty"],
				"sInfoFiltered":   langDataTable["sInfoFiltered"],
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     langDataTable["sLengthMenu"],
				"sLoadingRecords": langDataTable["sLoadingRecords"],
				"sProcessing":     langDataTable["sProcessing"],
				"sSearch":         langDataTable["sSearch"],
				"sZeroRecords":    langDataTable["sZeroRecords"],
				"oPaginate": {
					"sFirst":    langDataTable["sFirst"],
					"sLast":     langDataTable["sLast"],
					"sNext":     langDataTable["sNext"],
					"sPrevious": langDataTable["sPrevious"]
				},
				"oAria": {
					"sSortAscending":  langDataTable["sSortAscending"],
					"sSortDescending": langDataTable["sSortDescending"]
				}
			}
            
        });
    }
	if($('#dyntable_payment_history').length > 0) {
        $('#dyntable_payment_history').dataTable({
            "bProcessing"	: true,
            "bServerSide"	: true,
            "sAjaxSource"	: "templates/internals/core_ajax_history.php?report=" + getParameterByNamew('page'),
            "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 3 ]}],
			"aaSorting"     : [[ 0, "desc" ]],
 			"bFilter"           : true,
 			"oLanguage": {
			  	"sEmptyTable":     langDataTable["sEmptyTable"],
				"sInfo":           langDataTable["sInfo"],
				"sInfoEmpty":      langDataTable["sInfoEmpty"],
				"sInfoFiltered":   langDataTable["sInfoFiltered"],
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     langDataTable["sLengthMenu"],
				"sLoadingRecords": langDataTable["sLoadingRecords"],
				"sProcessing":     langDataTable["sProcessing"],
				"sSearch":         langDataTable["sSearch"],
				"sZeroRecords":    langDataTable["sZeroRecords"],
				"oPaginate": {
					"sFirst":    langDataTable["sFirst"],
					"sLast":     langDataTable["sLast"],
					"sNext":     langDataTable["sNext"],
					"sPrevious": langDataTable["sPrevious"]
				},
				"oAria": {
					"sSortAscending":  langDataTable["sSortAscending"],
					"sSortDescending": langDataTable["sSortDescending"]
				}
			}
       
        });
    }
if($('#dyntable_payment_Traffic').length > 0) {
        $('#dyntable_payment_Traffic').dataTable({
            "bProcessing"	: true,
            "bServerSide"	: true,
            "sAjaxSource"	: "templates/internals/core_ajax_traffic.php?report=" + getParameterByNamew('page'),
			"aaSorting"     : [[ 0, "desc" ]],
 	   		"bFilter"           : true,
 	   		"oLanguage": {
			  	"sEmptyTable":     langDataTable["sEmptyTable"],
				"sInfo":           langDataTable["sInfo"],
				"sInfoEmpty":      langDataTable["sInfoEmpty"],
				"sInfoFiltered":   langDataTable["sInfoFiltered"],
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     langDataTable["sLengthMenu"],
				"sLoadingRecords": langDataTable["sLoadingRecords"],
				"sProcessing":     langDataTable["sProcessing"],
				"sSearch":         langDataTable["sSearch"],
				"sZeroRecords":    langDataTable["sZeroRecords"],
				"oPaginate": {
					"sFirst":    langDataTable["sFirst"],
					"sLast":     langDataTable["sLast"],
					"sNext":     langDataTable["sNext"],
					"sPrevious": langDataTable["sPrevious"]
				},
				"oAria": {
					"sSortAscending":  langDataTable["sSortAscending"],
					"sSortDescending": langDataTable["sSortDescending"]
				}
			}
	
        });
    }
    
	if($('#dyntable_payment_Tier').length > 0) {
        $('#dyntable_payment_Tier').dataTable({
            "bProcessing"	: true,
            "bServerSide"	: true,
            "sAjaxSource"	: "templates/internals/core_ajax_tier.php?report=" + getParameterByNamew('page'),
			"aaSorting"     : [[ 0, "desc" ]],
			"bFilter"           : false,
			"oLanguage": {
			  	"sEmptyTable":     langDataTable["sEmptyTable"],
				"sInfo":           langDataTable["sInfo"],
				"sInfoEmpty":      langDataTable["sInfoEmpty"],
				"sInfoFiltered":   langDataTable["sInfoFiltered"],
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     langDataTable["sLengthMenu"],
				"sLoadingRecords": langDataTable["sLoadingRecords"],
				"sProcessing":     langDataTable["sProcessing"],
				"sSearch":         langDataTable["sSearch"],
				"sZeroRecords":    langDataTable["sZeroRecords"],
				"oPaginate": {
					"sFirst":    langDataTable["sFirst"],
					"sLast":     langDataTable["sLast"],
					"sNext":     langDataTable["sNext"],
					"sPrevious": langDataTable["sPrevious"]
				},
				"oAria": {
					"sSortAscending":  langDataTable["sSortAscending"],
					"sSortDescending": langDataTable["sSortDescending"]
				}
			}
       
        });
    }
if($('#dyntable_Pending_Debits').length > 0) {
        $('#dyntable_Pending_Debits').dataTable({
            "bProcessing"	: true,
            "bServerSide"	: true,
            "sAjaxSource"	: "templates/internals/core_ajax_Pending_Debit.php?report=" + getParameterByNamew('page'),
			"aaSorting"     : [[ 0, "desc" ]],
 			"bFilter"           : true,
 			"oLanguage": {
			  	"sEmptyTable":     langDataTable["sEmptyTable"],
				"sInfo":           langDataTable["sInfo"],
				"sInfoEmpty":      langDataTable["sInfoEmpty"],
				"sInfoFiltered":   langDataTable["sInfoFiltered"],
				"sInfoPostFix":    "",
				"sInfoThousands":  ",",
				"sLengthMenu":     langDataTable["sLengthMenu"],
				"sLoadingRecords": langDataTable["sLoadingRecords"],
				"sProcessing":     langDataTable["sProcessing"],
				"sSearch":         langDataTable["sSearch"],
				"sZeroRecords":    langDataTable["sZeroRecords"],
				"oPaginate": {
					"sFirst":    langDataTable["sFirst"],
					"sLast":     langDataTable["sLast"],
					"sNext":     langDataTable["sNext"],
					"sPrevious": langDataTable["sPrevious"]
				},
				"oAria": {
					"sSortAscending":  langDataTable["sSortAscending"],
					"sSortDescending": langDataTable["sSortDescending"]
				}
			}
       
        });
    }
});   
