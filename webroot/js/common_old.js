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
	
});	

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

function kwgrp_click(kwgrp,id)
{
	if($('#'+id).is(":checked"))
	{
		var check = 'checked';
	}
	else
	{
		var check = 'unchecked';
	}
	$.ajax
	({
		method: "POST",
		url:"kwgrp",
		data:{action:'kwgrp_select',kwgrp:kwgrp,check:check},
		headers:{

			'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
		},
		success: function(result) {
			$('#tab_div').html(result);
		}
	});
}

/** for html tab click ***/
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}


function select_all_kwgrp()
{
	if($('#check_all_kwgrp').is(":checked"))
	{
		$('.checkbox_kwgrp').prop('checked', true);
		var select_val = 'all_kwgrp_select';
	}
	else
	{
		$('.checkbox_kwgrp').prop('checked', false);
		var select_val = 'all_kwgrp_unselect';
	}
	var formdata = $('#frmkwgrp').serialize();
	$.ajax
	({
		method: "POST",
		url:"kwgrp",
		data:{action:'select_all_kwgrp',formdata:formdata,select_val:select_val},
		headers:{

			'X-CSRF-Token':$('meta[name="csrfToken"]').attr('content')
		},
		success: function(result) {
			$('#tab_div').html(result);
		}
	});
}



