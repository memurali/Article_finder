$(function() {
	
	var delay = (function() {
		var timer = 0;
		return function(callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();
	
	/** search page  ****/
	$('#btn_search').click(function() {
		var title = $('#user_title').val();
		$.ajax({
			method: "POST",
			url:"",
			data:{action:'article_search',title:title},
			headers:{

				'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
			},
			success: function(result) {
				$('#cat_div').css('display','block');
				$('#category').val('');
				$('#result_div').html(result);
			}
		});
		
	}); 
	
	/*** client report page  ***/
	$("#content_qlty,#action_filter").on("change", function () {
        var content = $('#content_qlty').find("option:selected").val();
		var action = $('#action_filter').find("option:selected").val();
		SearchData(content, action)
    });
	
});	

function SearchData(content, action) 
{
        if (content.toUpperCase() == 'ALL' && action.toUpperCase() == 'ALL') 
		{
            $('#tbdy_clreport_page tr').show();
        } 
		else 
		{
            $('#tbdy_clreport_page tr:has(td)').each(function () 
			{
                var rowcontent = $.trim($(this).find('td:eq(1)').text());
                var rowaction = $.trim($(this).find('td:eq(3)').text());
                if (content.toUpperCase() != 'ALL' && action.toUpperCase() != 'ALL') 
				{
                    if (rowcontent.toUpperCase() == content.toUpperCase() && rowaction == action) 
					{
                        $(this).show();
                    } 
					else 
					{
                        $(this).hide();
                    }
                } 
				else if ($(this).find('td:eq(1)').text() != '' || $(this).find('td:eq(1)').text() != '') 
				{
                    if (content != 'all') 
					{
                        if (rowcontent.toUpperCase() == content.toUpperCase()) 
						{
                            $(this).show();
                        } 
						else 
						{
                            $(this).hide();
                        }
                    }
                    if (action != 'all') 
					{
                        if (rowaction == action) 
						{
                            $(this).show();
                        }
                        else 
						{
                            $(this).hide();
                        }
                    }
                }
 
            });
        }
}



function category_select(category)
{
	var tit_count = $('#tit_count').val();
	$.ajax
	({
		method: "POST",
		url:"",
		data:{action:'category_filter',category:category,tit_count:tit_count},
		headers:{

			'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
		},
		success: function(result) {
			$('#result_div').html(result);
		}
	});
	
}

function tagcheck(tag,id)
{
	$('#result_div').css('display','none');
	var tit_count = $('#tit_count').val();
	var catid = $('#category').val();
	if($('#'+id).is(":checked"))
	{
		var check = 'check';
	}
	else
	{
		var check = 'uncheck';
	}
	
	$.ajax
	({
		method: "POST",
		url:"",
		data:{action:'tag_filter',category:catid,tag:tag,tit_count:tit_count,check:check},
		headers:{

			'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
		},
		success: function(result) {
			$('#result_div').css('display','block');
			$('#result_div').html(result);
		}
	});
}

function select_all_tag()
{
	$('#result_div').css('display','none');
	var tit_count = $('#tit_count').val();
	if($('#select_all').is(":checked"))
	{
		$('.checkbox_tag').prop('checked', true);
		var select_val = 'all_tag_select';
	}
	else
	{
		$('.checkbox_tag').prop('checked', false);
		var select_val = 'all_tag_unselect';
	}
	$.ajax
	({
		method: "POST",
		url:"",
		data:{action:'tag_filter_select',tit_count:tit_count,select_val:select_val},
		headers:{

			'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
		},
		success: function(result) {
			$('#result_div').css('display','block');
			$('#result_div').html(result);
		}
	});
}



/*** keyword page ***/
/*function kwgrp_click()
{
	if($('#frmkwgrp input:checked').length > 0)
		$('#frmkwgrp').submit();
		
	
}*/


function kwgrp_click_href(kwgrp)
{
	$('#kwgrp_val').val(kwgrp);
	$('#frmkwgrp').submit();
}

function keyword_click_href(keyword)
{
	$('#keyword').val(keyword);
	$('#frmkeyword').submit();
}
function check_all_kwgrp()
{
	if($('#select_all_kwgrp').is(":checked"))
	{
		$('.checkbox_kwgrp').prop('checked', true);
	}
	else
	{
		$('.checkbox_kwgrp').prop('checked', false);
	}
	$('#frmkwgrp').submit();
}
function show_kw(hideid,spanid,kwgrp)
{
	var check_symbol = $('#'+spanid).text();
	if(check_symbol=='-')
	{
		$('#'+spanid).text('+');
		//$('#'+hideid).toggle();
		$('#'+hideid +' tr').not(':first').hide();
		
	}
	else
	{
		$('#'+spanid).closest('td').css('visibility','hidden');
		$.ajax
		({
			method:"POST",
			url:"master_report",
			data:{action:'show_allkw',kw:kwgrp},
			headers:{

				'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
			},
			success: function(result) {
				$('#'+spanid).closest('td').css('visibility','visible');
				$('#'+hideid +' tr:last').after(result);
				if(check_symbol=='+')
					$('#'+spanid).text('-');
				if(check_symbol=='-')
					$('#'+spanid).text('+');
				//$('#'+hideid).toggle();
			}
			
		});
	}
	
	
}

function sortTable(tableid,n,data_type=null) 
{
	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	table = document.getElementById(tableid);
	switching = true;
	//Set the sorting direction to ascending:
	dir = "asc"; 
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching) {
	//start by saying: no switching is done:
	switching = false;
	rows = table.rows;
	/*Loop through all table rows (except the
	first, which contains table headers):*/
	/*console.log('td1  '+rows[1].getElementsByTagName("TD")[1].textContent);
	console.log('td2  '+rows[1].getElementsByTagName("TD")[2].textContent);
	console.log('td3  '+rows[1].getElementsByTagName("TD")[3].textContent);*/
		
	for (i = 0; i < (rows.length - 1); i++)
	{
		//start by saying there should be no switching:
		shouldSwitch = false;
		/*Get the two elements you want to compare,
		one from current row and one from the next:*/
		x = rows[i].getElementsByTagName("TD")[n];
		y = rows[i + 1].getElementsByTagName("TD")[n];
		/*check if the two rows should switch place,
		based on the direction, asc or desc:*/
		
		value1 = x.textContent;
		value2 = y.textContent;
		
		if (dir == "asc")
		{
			if(data_type==null)
			{
				if(value1.toLowerCase() > value2.toLowerCase())
				{
					//if so, mark as a switch and break the loop:
					shouldSwitch= true;
					break;
				}
			}
			else if(data_type=='number')
			{
				if(value1.indexOf(',')>-1)
					value1 = value1.replace(/,/g,'');
				if(value2.indexOf(',')>-1)
					value2 = value2.replace(/,/g,'');
				if(Number(value1) > Number(value2))
				{
					shouldSwitch = true;
					break;
				}
			}
			else if(data_type=='date')
			{
				if(new Date(value1)>new Date(value2))
				{
					shouldSwitch= true;
					break;
				}
			}
			
		}
		else if (dir == "desc")
		{
			if(data_type==null)
			{
				if(value1.toLowerCase() < value2.toLowerCase())
				{
					//if so, mark as a switch and break the loop:
					shouldSwitch= true;
					break;
				}
			}
			else if(data_type=='number')
			{
				if(value1.indexOf(',')>-1)
					value1 = value1.replace(/,/g,'');
				if(value2.indexOf(',')>-1)
					value2 = value2.replace(/,/g,'');
				
				if(Number(value1) < Number(value2))
				{
					shouldSwitch = true;
					break;
				}
			}
			else if(data_type=='date')
			{
				if(new Date(value1)<new Date(value2))
				{
					shouldSwitch= true;
					break;
				}
			}
		}
	}
	if (shouldSwitch) {
		/*If a switch has been marked, make the switch
		and mark that a switch has been done:*/
		rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
		switching = true;
		//Each time a switch is done, increase this count by 1:
		switchcount ++;      
	} else {
		/*If no switching has been done AND the direction is "asc",
		set the direction to "desc" and run the while loop again.*/
		if (switchcount == 0 && dir == "asc") {
		dir = "desc";
		switching = true;
		}
	}
	}
}

function select_category(catid)
{
	if(catid!='')
		$('#master_report').submit();
}
function show_more(element)
{
	var parenttbl_id = $(element).closest('table').attr('id');
	$(element).closest('tr').remove();
	$('#'+parenttbl_id+'  tr').attr('class','show_tr');
}
function sel_cat_match(catid)
{
	if(catid!='')
	{
		$.ajax
		({
			method:'POST',
			url:'kwgrping',
			data:{action:'cat_selected',catid:catid},
			headers:{
				'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
			},
			success:function(result)
			{
				$('textarea#kwgrp_match').val(result);
			}
			
		});
	}
}

function select_site_report(siteid)
{
	if(siteid!='')
	{
		$.ajax
		({
			method:'POST',
			url:'master_report',
			data:{action:'site_selected',siteid:siteid},
			headers:{
				'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
			},
			success:function(result)
			{
				$('#client_div').html(result);
			}
		});
	}
}

function client_report(kwgrp)
{
	$('#kwgrp_report').val(kwgrp);
	$('#frmclreport').submit();
}

function accordion_clreport(spanid,tblid)
{
	var check_symbol = $('#'+spanid).text();
	if(check_symbol=='-')
	{
		$('#'+spanid).text('+');
		$('#'+tblid +' tr').slice(2).hide();
		
	}
	else
	{
		$('#'+spanid).text('-');
		$('#'+tblid +' tr').show();
	}
}

function check_keyword(keyword) 
{
	
	$('#tbdyclreport_master tr td').css("background-color","");
	$('#tbdyclreport_kw tr td').css("background-color","");
	$('#tbdy_clreport_page tr td').css("background-color","");
	var elems = $('#tbdyclreport_master tr td').filter(function(){
					return this.textContent.trim() === keyword
				}).css("background-color","#f3ca63");
			
	var elems = $('#tbdyclreport_kw tr td').filter(function(){
					return this.textContent.trim() === keyword
				}).css("background-color","#f3ca63");
				
	$.ajax
	({
		method:'POST',
		url:'client_report',
		data:{action:'change_color',keyword:keyword},
		headers:{
			'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
		},
		success:function(result)
		{
			var elems = $('#tbdy_clreport_page tr td').filter(function(){
				return this.textContent.trim() === result
			}).css("background-color","#f3ca63");
		}
	});
	
	
			
}

function kwgrp_match_click()
{
	var kwgrp_match = $('textarea#kwgrp_match').val();
	//kwgrp_match = kwgrp_match.replace(/\t/g,'');
	var category = $('#category').val();
	if(kwgrp_match!='' && category!='')
	{
		$.ajax
		({
			method : 'POST',
			url	   : 'kwgrping',
			data   : {action:'match_request',category:category,kwgrp_match:encodeURIComponent(kwgrp_match)},
			headers:{
					'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
					
			},
			success:function(result)
			{
				$('textarea#kwgrp_match').val(result);
				alert('Added successfully');
			}
		});
	}
	else
		alert('select category and enter phrase match');
}


