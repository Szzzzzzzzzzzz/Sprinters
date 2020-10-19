<?php 
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die(1); ?>
<style type="text/css">
	.content-item-image.quiz{
			background-image: url(<?php echo plugins_url('', dirname(__FILE__) ) . '/images/form-not-found.png' ?>);
		    background-repeat: no-repeat;
    		background-size: cover;
		}
</style>
	<div id="opinionstage-content">
		<div class="opinionstage-header-wrapper">	
				<?php if ( $os_client_logged_in ) { ?>
				<div class="opinionstage-logo-wrapper">
				<div class="opinionstage-logo"></div>
				<div class="opinionstage-connectivity-status"><?php echo($os_options["email"]); ?>
					<form method="POST" action="<?php echo get_admin_url(null, 'admin.php?page='.OPINIONSTAGE_DISCONNECT_PAGE)?>" class="opinionstage-connect-form">
						<button class="opinionstage-disconnect" type="submit">Disconnect</button>
					</form>
				</div>
				</div>	
				<?php } ?>
		</div>
	<div id="container" class="opinionstage-dashboard">
		<div class="opinionstage-item-view-dashboard">
		<div id="opinionstage-section-create" class="opinionstage-dashboard-section">
		<div class="opinionstage-section-header">
			<div class="opinionstage-section-title">My Items</div>
			<div class="opinionstage-header-inner-container">
			<div class="opinionstage-header-inner-section">
			<a href="https://help.opinionstage.com/wordpress-plugin/how-to-add-items-to-your-wordpress-site" target="_blank" class="">Need help adding items to your site?</a>	
			<div style="padding: 0px 9px; width: 150px; display: inline-block;">
				<select id="itemList">
					<option value="all">ALL ITEMS</option>
					<option value="poll">POLL</option>
					<option value="multiple">MULTIPLE POLL SET</option>
					<option value="survey">SURVEY</option>
					<option value="slideshow">SLIDESHOW</option>
					<option value="trivia">TRIVIA QUIZ</option>
					<option value="personality">PERSONALITY QUIZ</option>
					<option value="list">LIST</option>
					<option value="form">FORM</option>
					<option value="story">STORY ARTICLE</option>
				</select>
			</div>
			<div class="search search-container">
				<input id="searchItem" class="std-input" name="search" placeholder="Search" type="text">
			</div>	
			</div>	
			<a href="<?php echo admin_url( 'admin.php?page=opinionstage-settings' ); ?>" class="opinionstage-connect-btn opinionstage-blue-btn opinionstage-item-create" style="margin-left: 20px; font-weight: 600; padding-left: 0; padding-right: 0;">CREATE</a>
			</div>
		</div>	
		</div>
		<p class="result_progress" style="display: block; font-size: 16px; text-align: center;">Loading...</p>
		<table id="check"></table>
		<p class="no_item" style="display: none; font-size: 15px; text-align: center;">No items found</p>
		<div id="loadMore" class="btn btn_aqua btn_full-width" style="display: none;">Click for more</div>
		<div id="showLess" style="display: none;">Show less</div>
		</div>
	</div>
	</div>
<script type="text/javascript">
	jQuery(document).ready(function($){		
    	$.ajax({
			url: '<?php echo OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API; ?>?type=all&page=1&per_page=99',
			headers: {
			'Accept':'application/vnd.api+json',
			'Content-Type':'application/vnd.api+json',
			'OSWP-Plugin-Version':'<?php echo OPINIONSTAGE_WIDGET_VERSION ?>',
			'OSWP-Client-Token': '<?php echo opinionstage_user_access_token() ?>'
			    },
			method: 'GET', 
			dataType: 'json',
			success: function(data){
				dropdownOptions = data;
				if(dropdownOptions.data.length == 0){
					var adminUrlCreateLink = "<?php echo admin_url( 'admin.php?page=opinionstage-settings'); ?>";
					var viewtext = '<tbody><tr><td><p><span style="font-weight: 600; font-size: 15px; color:#212120;">No items yet..., </span><a href="'+adminUrlCreateLink+'" style="font-weight: 600; font-size: 15px; color:#3499c2;">Add your first item</a></p></td></tr></tbody>';
					$('.result_progress').css('display', 'none');
					$(viewtext).appendTo('#container table#check');
				}else{
					for (var i = 0; i < dropdownOptions.data.length; i++) {
			            var previewBlockOsTitle = dropdownOptions.data[i].attributes['title'];
			            var previewBlockOsType = dropdownOptions.data[i].attributes['type'];
			            var previewBlockOsDate = dropdownOptions.data[i].attributes['updated-at'];
			            	previewBlockOsDate = new Date(previewBlockOsDate);
							previewBlockOsDate = previewBlockOsDate.toDateString();
						var resDateOs = previewBlockOsDate.split(" ");
  							previewBlockOsDate = resDateOs[2]+' '+resDateOs[1]+' '+resDateOs[3];
			            var previewBlockOsImageUrl = dropdownOptions.data[i].attributes['image-url'];
			            var previewBlockOsView = dropdownOptions.data[i].attributes['landing-page-url'];
			            var previewBlockOsEdit = dropdownOptions.data[i].attributes['edit-url'];
			            var previewBlockOsStatistics = dropdownOptions.data[i].attributes['stats-url'];
			            var viewtext = '<tbody id="count"><tr class="settingBorderOs"><td class="image"><a href="'+previewBlockOsView+'" target="_blank"><div class="content-item-image quiz"><img height="90" src="'+previewBlockOsImageUrl+'" width="120"><div class="content-item-label">'+previewBlockOsType+'</div></div></a></td><td class="long"><div style="position: relative;height: 85px;"><a href="'+previewBlockOsEdit+'" class="opinionstage-item-title" target="_blank">'+previewBlockOsTitle+'</a><table><tbody><tr><td><span class="os-icon-plugin icon-os-common-date"></span><div class="label">'+previewBlockOsDate+'</div></td></tr></tbody></table></div></td><td class="action"><div class="opinionstage-item-action-container"><a href="'+previewBlockOsView+'" class="opinionstage-blue-bordered-btn opinionstage-edit-content " target="_blank"> View </a><a href="'+previewBlockOsEdit+'" class="opinionstage-blue-bordered-btn opinionstage-edit-content " target="_blank"> Edit </a><a href="'+previewBlockOsStatistics+'" class="opinionstage-blue-bordered-btn opinionstage-edit-content " target="_blank"> Results </div></a></td></tr></tbody>';
			            	$('.result_progress').css('display', 'none');
							$(viewtext).appendTo('#container table#check');
		        	}		        		        	
				}				
			},
			complete: function(data) {
				size_li = $("table#check tbody#count").size();
				dropdownDataLength = dropdownOptions.data.length;

				loadMore(size_li, dropdownDataLength, "all");
				     var data = {
						'action': 'opinionstage_ajax_item_count',
						'oswp_item_count' : dropdownOptions.data.length
					};	

					jQuery.post(ajaxurl, data, function(response) {
						if(response){

						}
					});	

				    jQuery('#itemList').on('change', function() {
					var selectedValue = this.value;
					var contentLabel = jQuery(".content-item-label");
					var item_count = 0;

						jQuery('.no_item').css('display', 'none');
					  	contentLabel.each(function() {
					  		getContainer = jQuery( this ).parent().parent().parent().parent();
							if(selectedValue != 'all' && selectedValue != jQuery( this ).text().toLowerCase()){
							  	getContainer.parent().css('display', 'none');
							  	getContainer.parent().removeClass('countItem');
							}
							else {
								jQuery("#searchItem").val('');
								getContainer.parent().css('display', 'table-row-group');
								getContainer.parent().addClass('countItem');
								item_count = item_count + 1;
							}
						});

					  	if (item_count == 0) {
					  		jQuery('.no_item').css('display', 'block');
					  	}

					  	size = $("table#check tbody.countItem").size();
					  	$("#loadMore").fadeOut(500);
					  	loadMore(size, item_count, "filter");
					});

					$("#searchItem").on("keyup",function search(e) {
					    if(e.keyCode == 13) {
					        var searchItem = $(this).val();
					        var listTitle = jQuery('td.long a');
					        var dropdownValue = jQuery('#itemList').val();
					        var contentList = jQuery(".content-item-label");

							    listTitle.each(function() {
						        	var title = jQuery( this ).text().toLowerCase();
						        	outerContainer = jQuery( this ).parent().parent().parent().parent();

						        	if ( dropdownValue == 'all' ) {
							        	if(!title.includes(searchItem)) {
							        		outerContainer.css('display', 'none');
							        	}
							        	else {
										  	outerContainer.css('display', 'table-row-group');
										}
									}
									else {
										contentList.each(function() {
											if(dropdownValue == jQuery( this ).text().toLowerCase()){
												if (outerContainer.hasClass('countItem')) {
													if(!title.includes(searchItem)) {
														outerContainer.css('display', 'none');
													}
													else {
													  	outerContainer.css('display', 'table-row-group');
													}
												}
											}
										});
									}
						        });
					    }
					});		    
			},
			error: function(){
			    console.log(data.statusText);
			}
		});


	function loadMore(size, dataLength, item) {
		if(dataLength == size && dataLength > 10){
        	setTimeout(function(){$('#showLess').trigger('click');},500);            			
        }
        // Show the div in 5s
		var countItemOS = 10;
		if(dataLength > countItemOS){
			$("#loadMore").delay(2000).fadeIn(500);
		}

		x=10;
		$('table#check ttbody#count:lt('+x+')').show();
		$('#loadMore').click(function () {
		    x= (x+10 <= size) ? x+10 : size;

		    if (item == 'all') {
			    $('table#check tbody#count:lt('+x+')').show(500);
			}
			else {
				$('table#check tbody#countItem:lt('+x+')').show(500);
			}

		    if(size == x){
				$("#loadMore").hide(500);
			}
		});
		$('#showLess').on('click', null, function () {
		    x=(x-0<0) ? 10 : x-0;
		    if (item == 'all') {
		    	$('table#check tbody#count').not(':lt('+x+')').hide();
		    }
			else {
				$('table#check tbody#countItem').not(':lt('+x+')').hide();
			}
		});	
	}
	});
</script>
