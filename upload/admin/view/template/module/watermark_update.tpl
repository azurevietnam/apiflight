<?php echo $header; ?>

<link rel="stylesheet" media="screen" type="text/css" href="view/javascript/watermark/colorpicker/colorpicker.css" />
<script type="text/javascript" src="view/javascript/watermark/colorpicker/colorpicker.js"></script>                

<script type="text/javascript" src="view/javascript/watermark/uploadify/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="view/javascript/watermark/uploadify/uploadify.css" />

<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
<div class="box">
	<div class="heading">
		<h1><img src="view/image/module.png" alt="" />Create New Watermark</h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="button"><span>Save</span></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span>Cancel</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<tr>
							<td>
								<span class="required">*</span>Watermark Name
								<br>
								<span class="help">
									A name given to watermark.
								</span>
							</td>
							<td>
								<input type="text" name="watermark_name" value="<?php echo $watermark['watermark_name']; ?>"/>
							</td>
						</tr>
						<td>
							Watermark Type
							<br>
							<span class="help">
								Choose "Image" for image watermark or Choose "Text" for text watermark.
							</span>
						</td>
						<td>
							<select name="watermark_type" id="new_watermark">
								<?php if($watermark['watermark_type']) { ?>
								<option value="1" selected="selected">Image</option>
								<option value="0">Text</option>							
								<?php } else { ?>
								<option value="1">Image</option>
								<option value="0" selected="selected">Text</option>							
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr class="new_watermark_image" style="display:none;">
						<td><span class="required">*</span>Image</td>
						<td>
							<div class="image">
								<img src="<?php echo $watermark['watermark_thumb']; ?>" alt="" id="watermark-image" />
								<input type="hidden" name="watermark_image" value="<?php echo $watermark['watermark_image']; ?>" id="watermark_image_value" />
								<br />
								<a onclick="image_upload('watermark_image_value', 'watermark-image');">Browse</a>&nbsp;&nbsp;|&nbsp;&nbsp;
								<a onclick="$('#watermark-image').attr('src', '<?php echo $no_image; ?>'); $('#watermark_image_value').attr('value', '');">Clear</a>
							</div>
						</td>
					</tr>
					
					
					<tr class="new_watermark_text">
						<td><span class="required">*</span>Watermark Text</td>
						<td><input type="text" name="watermark_text[text]" value="<?php echo $watermark['watermark_text']['text']; ?>" /></td>
					</tr>
					<tr class="new_watermark_text">
						<td>
							<span class="required">*</span>Text Font<br>
							<span class="help">You can upload your own custom fonts.</span>
						</td>
						<td>
							<select name = "watermark_text[font]">
								<option <?php if($watermark['watermark_text']['font'] == 'Arial.ttf') {?> selected="selected"  <?php } ?>><I>Default</I></option>
							<?php foreach($watermark_text_fonts as $font) { ?>
								<option <?php if($watermark['watermark_text']['font'] == $font) {?> selected="selected"  <?php } ?>><?php echo $font;?></option>
							<?php }?>
							</select>
							<div id="watermark_text_font_style"></div>
							<span id="watermark_text_font_show"> Sample Text </span>
						</td>
					</tr>
					<tr class="new_watermark_text">
						<td><span class="required">*</span>Text Color</td>
						<td>
							<div id="watermark_text_color_widget">
								<div id="watermark_text_colorSelector"><div style="background-color: #<?php echo $watermark['watermark_text']['color']; ?>"></div></div>
								<div id="watermark_text_colorpickerHolder">
								</div>
							</div>
							<input type="hidden" name="watermark_text[color]" value="<?php echo $watermark['watermark_text']['color']; ?>" id="watermark_color_picker_value"/>
							
						</td>
					</tr>
					
					<tr class="new_watermark_text">
						<td><span class="required">*</span>Font Size</td>
						<td>
							<div id="slider_watermark_text_font_size" class="watermark_slider"></div>
							<span id="watermark_text_font_size_val"></span>
							<input type="hidden" name="watermark_text[font_size]" value="<?php echo $watermark['watermark_text']['font_size']; ?>" id="watermark_text_font_size"/>							
						</td>
					</tr>				
					<tr class="new_watermark_text">
						<td>
							<span class="required">*</span>Watermark Opacity<br>
							<span class="help">Apply watermark opacity to image.</span>							
						</td>
						<td>
							<div id="slider_watermark_opacity" class="watermark_slider"></div>
							<span id="slider_watermark_opacity_val"></span>
							<input type="hidden" name="watermark_text[opacity]" value="<?php echo $watermark['watermark_text']['opacity']; ?>" />
						</td>
					</tr>
					
					<tr class="new_watermark_text">
						<td>
							Watermark Preview										
						</td>
						<td>
							<div class="image">
								<img src id="preview"/>
							</div>
						</td>
					</tr>
				</table>
		</form>
	</div>
</div>

<script type="text/javascript"><!--
//$(document).ready(function() {

function changeType(value)
{
	if(value == 1){
		$(".new_watermark_image").fadeIn();
		$(".new_watermark_text").fadeOut();
		//updatePreview();
	}
	else{
		$(".new_watermark_image").fadeOut();
		$(".new_watermark_text").fadeIn();
		updatePreview();
	}
}
$("#new_watermark").change(function(){
	changeType($(this).val());
});

changeType(<?php echo $watermark['watermark_type'] ; ?>);


$("#slider_watermark_opacity").slider({
	range: "min",
	value: <?php echo $watermark['watermark_text']['opacity']; ?>,
	min: 0,
	max: 100,
	slide: function( event, ui ) {
		$( "span#slider_watermark_opacity_val" ).text(ui.value + "%");
		
	},
	stop: function(event, ui) {
		$( "span#slider_watermark_opacity_val" ).text(ui.value + "%");
		$('input[name="watermark_text[opacity]"]' ).val(ui.value);
		updatePreview();
	}
});

$("span#slider_watermark_opacity_val" ).text($("#slider_watermark_opacity").slider( "value" ) + "%");



$("#slider_watermark_text_font_size").slider({
	range: "min",
	value: <?php echo $watermark['watermark_text']['font_size']; ?>,
	min: 8,
	max: 64,
	slide: function( event, ui ) {
		$( "span#watermark_text_font_size_val" ).text(ui.value + "px");
	},
	stop: function(event, ui) {
		$( "span#watermark_text_font_size_val" ).text(ui.value + "px");
		$('input[name="watermark_text[font_size]"]').val(ui.value);
		updatePreview();
	}
});

$( "span#watermark_text_font_size_val" ).text($("#slider_watermark_text_font_size").slider( "value" ) + "px");


$('#watermark_text_colorpickerHolder').ColorPicker({
	flat: true,
	color: '#<?php echo $watermark['watermark_text']['color']; ?>',
	onSubmit: function(hsb, hex, rgb) {
		$('#watermark_text_colorSelector div').css('backgroundColor', '#' + hex);
		updatePreview();
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#watermark_text_colorSelector div').css('backgroundColor', '#' + hex);
		$('input[name="watermark_text[color]"]').val(hex);
		updatePreview();
	}
});



var widt = false;

$('#watermark_text_colorSelector').bind('click', function() {
	$('#watermark_text_colorpickerHolder').stop().animate({height: widt ? 0 : 173}, 500);
	widt = !widt;
	
});

$('select[name="watermark_text[font]"]').change(function(){
	if($(this).val() != "Default"){
		$("#watermark_text_font_style").html('<style type="text/css">@font-face{font-family: watermarkfont;src: url("../fonts/'+$(this).val()+'")}</style>');	
		$("#watermark_text_font_show").css('font-family','watermarkfont');
	}else
	{
		$("#watermark_text_font_style").html('');	
		$("#watermark_text_font_show").css('font-family','Arial');
	}

	updatePreview();
});

function updatePreview()
{
	if($("#new_watermark").val() == 0){
		var font = $('select[name="watermark_text[font]"]').val();
		var param = '';
		param += "&token=<?php echo $token; ?>";
		param += "&text="+$('input[name="watermark_text[text]"]').val();
		param += "&font="+ ((font == 'Default') ? 'Arial.ttf' : font);
		param += "&color="+$('input[name="watermark_text[color]"]').val();
		param += "&alpha=" + $("#slider_watermark_opacity").slider( "value" );
		param += "&size=" + $("#slider_watermark_text_font_size").slider("value");
		
		$(".image img#preview").attr("src","index.php?route=module/watermark/generate"+param);
	}
}

updatePreview();
//});*/
//--></script> 

<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: 'Image Manager',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<?php echo $footer; ?>