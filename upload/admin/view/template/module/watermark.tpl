<?php echo $header; ?>
<link type="text/css" href="view/javascript/jquery/ui/themes/base/jquery.ui.all.css" rel="stylesheet" />
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
<div class="notificatoin"><b>Do you want activate disabled features of Samcholi Watermark? Purchase "Samcholi Watermark Premium Version" Here.</b></div>
<div class="box">
	<div class="heading">
		<h1><img src="view/image/module.png" alt="" />Store Watermark Settings</h1>
		<div class="buttons">
			<a onclick="$('#setting-form').submit();" class="button"><span>Save</span></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span>Cancel</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="setting-form">
			<table class="form">
				<tr>
					<td title="tooltip"> Enable Watermark ?</td>
					<td>
						<input type="hidden" name="watermark_status" value="0"/>
						<input type="checkbox" class="on_off" name="watermark_status" handler="wControlPanel" value="1" <?php echo ($watermark_status) ? 'checked="checked"' : ''; ?> />	
					</td>
				</tr>
			</table>
			<div id="wControlPanel">
				<div class="notificatoin">Changes on <b>Size Setting, Category Setting, Manufacturer Setting</b> will not work on front side. And <b>Some Positions ( Center Repeat, Repeat)</b> and <b>Angle</b> features also will be not working.</div>
				<?php foreach($watermark_sort as $sort_order) { ?>
					<?php if($sort_order == 'size_watermark') { ?>
						<div>
							<h3 title="Drag to change the priority" style="cursor:move !important"><a onclick="javascript:void(0);">Size Setting</a></h3>
							<div>
								<table class="form">
									<tr>
										<td> Enable Size Watermark ?</td>
										<td>
											<input type="hidden" name="size_watermark[status]" value="0"/>
											<input type="checkbox" class="on_off" name="size_watermark[status]" handler="size_watermark" value="1" <?php echo ($size_watermark['status'] == 1) ? 'checked="checked"' : ''; ?> />	
										</td>
									</tr>
								</table>
								<table id="size_watermark" class="list">
									<thead>
										<tr>
											<td class="center"> Image Size Type </td>
											<td class="center"> Watermark Name</td>
											<td class="center"> Position </td>
											<td class="left"> *Angle* </td>
											<td class="left"> Max Size </td>
											<td class="left"> Preview </td>
										</tr>
									</thead>
									<tbody id="size_setting">
									<?php foreach($size_watermark['setting'] as $size_id => $size) { ?>
										<tr>
											<td class="center">
												<?php echo $size['name']; ?>
												<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][name]" value="<?php echo $size['name']; ?>"/> 
												<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][value]" value="<?php echo $size['value']; ?>"/> 
											</td>
											<td class="center">
												<?php if($watermarks ) { ?>

													<?php if(isset($size_watermark['setting'][$size_id]['watermark'])){ ?>
														<select name="size_watermark[setting][<?php echo $size_id; ?>][watermark]">
															<option value="0" > None </option>
															<?php foreach($watermarks as $watermark){ ?>
																<?php $key= $watermark['details']['wid']; ?>
																<option value="<?php echo $key; ?>" <?php if($size_watermark['setting'][$size_id]['watermark'] == $key){echo 'selected="selected"'; } ?>> <?php echo $watermark['details']['watermark_name']; ?></option>
															<?php } ?>								
														</select>
													<?php } else { ?>
														<select name="size_watermark[setting][<?php echo $size_id; ?>][watermark]">
															<option value="0" > None </option>
															<?php foreach($watermarks as $watermark){ ?>
																<?php $key= $watermark['details']['wid']; ?>
																<option value="<?php echo $key; ?>"> <?php echo $watermark['details']['watermark_name']; ?></option>
															<?php } ?>								
														</select>
													<?php } ?>
												<?php } else {?>
														No Watermark exists.	
														<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][watermark]" value="0" />
												<?php } ?>											
											</td>
											<td class="center">
												<select name="size_watermark[setting][<?php echo $size_id; ?>][position]">
													<?php foreach ($position as $position_id=>$position_name) { ?>
														<?php if( $position_id == 6 || $position_id == 7 ) { ?>
															<option disabled="disabled"><?php echo $position_name; ?></option>
														<?php } else { ?>
															<option value="<?php echo $position_id; ?>" <?php if($size_watermark['setting'][$size_id]['position'] == $position_id) {echo 'selected="selected"';} ?>><?php echo $position_name; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
											</td>
											<td class="left">
												<div id="size_watermark_angle_<?php echo $size_id; ?>" class="watermark_slider" valueholder = "size_watermark[setting][<?php echo $size_id; ?>][angle]"></div>
												<span id="size_watermark_angle_<?php echo $size_id; ?>_val"></span>&deg;
												<?php if(isset($size_watermark['setting'][$size_id]['angle'])) { ?>
													<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][angle]" value="<?php echo $size_watermark['setting'][$size_id]['angle']; ?>" />
												<?php } else { ?>
													<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][angle]" value="0" />
												<?php } ?>
											</td>
											<td class="left">
												<div id="size_watermark_maxsize_<?php echo $size_id; ?>" class="watermark_slider_percent" valueholder = "size_watermark[setting][<?php echo $size_id; ?>][maxsize]"></div>
												<span id="size_watermark_maxsize_<?php echo $size_id; ?>_val"></span>%;
												<?php if(isset($size_watermark['setting'][$size_id]['maxsize'])) { ?>
													<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][maxsize]" value="<?php echo $size_watermark['setting'][$size_id]['maxsize']; ?>" />
												<?php } else { ?>
													<input type="hidden" name="size_watermark[setting][<?php echo $size_id; ?>][maxsize]" value="30" />
												<?php } ?>
											</td>											
											<td class="left">
												<a class="button colorbox" watermark="size_watermark" iden="<?php echo $size_id; ?>">Set With Preview</a>
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>						
					<?php } elseif ($sort_order == 'manufacturer_watermark') { ?>
						<div>
							<h3><a onclick="javascript:void(0);">Manufacturer Setting</a></h3>
							<div>
								<table class="form">
									<tr>
										<td> Enable Manufacturer Watermark ?</td>
										<td>
											<input type="hidden" name="manufacturer_watermark[status]" value="0"/>
											<input type="checkbox" class="on_off" name="manufacturer_watermark[status]" handler="manufacturer_watermark" value="1" <?php echo (isset($manufacturer_watermark['status']) && $manufacturer_watermark['status'] == 1) ? 'checked="checked"' : ''; ?> />
										</td>
									</tr>
								</table>
								<table id="manufacturer_watermark" class="list">
									<thead>
										<tr>
											<td class="center"> Manufacturer Name </td>
											<td class="center"> Watermark Name</td>
											<td class="center"> Position </td>							
											<td class="left"> *Angle* </td>
											<td class="left"> Max Size </td>
											<td class="left"> Preview </td>
										</tr>
									</thead>
									<?php foreach($manufacturer_list as $manufacturer){ ?>						
										<tbody id="manufacturer_setting_0">
											<tr>
												<td class="center">
													<?php echo $manufacturer['name']; ?>
												</td>
												<td class="center">
													<?php if($watermarks){ ?>
															<select name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id'] ?>][watermark]">
																<option value="0" > None </option>
																<?php foreach($watermarks as $watermark){ ?>
																	<?php $key= $watermark['details']['wid']; ?>
																	<option value="<?php echo $key; ?>" <?php if(isset($manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['watermark']) && ($manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['watermark'] == $key)) {echo 'selected="selected"' ;} ?>> <?php echo $watermark['details']['watermark_name']; ?></option>
																<?php } ?>
									
															</select>
													<?php } else {?>
															No Watermark exists.	
															<input type="hidden" name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id'] ?>][watermark]" value="0" />
													<?php } ?>											
												</td>
												<td class="center">
													<select name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][position]">				
														<?php foreach ($position as $position_id=>$position_name) { ?>
															<?php if( $position_id == 6 || $position_id == 7 ) { ?>
																<option disabled="disabled"><?php echo $position_name; ?></option>
															<?php } else { ?>
																<option value="<?php echo $position_id; ?>" <?php if(isset($manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['position']) && ($manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['position'] == $position_id)) {echo 'selected="selected"';} ?>><?php echo $position_name; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>												
												<td class="left">
													<div id="manufacturer_watermark_angle_<?php echo $manufacturer['manufacturer_id']; ?>" class="watermark_slider" valueholder = "manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][angle]"></div>
													<span id="manufacturer_watermark_angle_<?php echo $manufacturer['manufacturer_id']; ?>_val"></span>&deg;
													<?php if(isset($manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['angle'])) { ?>
														<input type="hidden" name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][angle]" value="<?php echo $manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['angle']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][angle]" value="0" />
													<?php } ?>
												</td>
												<td class="left">
													<div id="manufacturer_watermark_maxsize_<?php echo $manufacturer['manufacturer_id']; ?>" class="watermark_slider_percent" valueholder = "manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][maxsize]"></div>
													<span id="manufacturer_watermark_maxsize_<?php echo $manufacturer['manufacturer_id']; ?>_val"></span>%
													<?php if(isset($manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['maxsize'])) { ?>
														<input type="hidden" name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][maxsize]" value="<?php echo $manufacturer_watermark['setting'][$manufacturer['manufacturer_id']]['maxsize']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="manufacturer_watermark[setting][<?php echo $manufacturer['manufacturer_id']; ?>][maxsize]" value="30" />
													<?php } ?>
												</td>
												<td class="left">
													<a class="button colorbox" watermark="manufacturer_watermark" iden="<?php echo $manufacturer['manufacturer_id']; ?>">Set With Preview</a>
												</td>
											</tr>
										</tbody>				
									<?php } ?>
								</table>
							</div>
						</div>
					<?php } elseif ($sort_order == 'category_watermark') { ?>
						<div>
							<h3><a onclick="javascript:void(0);">Category Setting</a></h3>
							<div>
								<table class="form">
									<tr>
										<td> Enable Category Watermark ?</td>
										<td>
											<input type="hidden" name="category_watermark[status]" value="0"/>
											<input type="checkbox" class="on_off" name="category_watermark[status]" handler="category_watermark" value="1" <?php echo ($category_watermark['status'] == 1) ? 'checked="checked"' : ''; ?> />
										</td>
									</tr>
								</table>
								<table id="category_watermark" class="list">
									<thead>
										<tr>
											<td class="center"> Category </td>
											<td class="center"> Watermark Name</td>
											<td class="center"> Position </td>
											<td class="left"> *Angle* </td>
											<td class="left"> Max Size </td>
											<td class="left"> Preview </td>
											<td class="left"> </td>								
										</tr>
									</thead>
									<?php $category_row = 0; if(isset($category_watermark['setting'])){ 
										foreach($category_watermark['setting'] as $category_id=>$category) { ?>
										<tbody id="category_setting_<?php echo $category_row; ?>">
											<tr>
												<td class="center">
													<input type="text" name="category_watermark[setting][<?php echo $category_row; ?>][name]" value="<?php echo $category['name']; ?>"/>
													<input type="hidden" name="category_watermark[setting][<?php echo $category_row; ?>][category_id]" value="<?php echo $category['category_id']; ?>" />
												</td>
												<td class="center">
													<?php if($watermarks){ ?>
														<select name="category_watermark[setting][<?php echo $category_row; ?>][watermark]">
															<option value="0" > None </option>
															<?php foreach($watermarks as $watermark){ ?>
																<?php $key= $watermark['details']['wid']; ?>
																<option value="<?php echo $key; ?>" <?php if($category['watermark'] == $key) {echo 'selected="selected"' ;} ?>> <?php echo $watermark['details']['watermark_name']; ?></option>
															<?php } ?>
														</select>
													<?php } else {?>
															No Watermark exists.
															<input type="hidden" name="category_watermark[setting][<?php echo $category_row; ?>][watermark]" value="0" />
													<?php } ?>	
												</td>
												<td class="center">
													<select name="category_watermark[setting][<?php echo $category_row; ?>][position]">
														<?php foreach ($position as $position_id=>$position_name) { ?>
															<?php if( $position_id == 6 || $position_id == 7 ) { ?>
																<option disabled="disabled"><?php echo $position_name; ?></option>
															<?php } else { ?>
																<option value="<?php echo $position_id; ?>" <?php if($category['position'] == $position_id) {echo 'selected="selected"';} ?>><?php echo $position_name; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>												
												<td class="left">
													<div id="category_watermark_angle_<?php echo $category_row; ?>" class="watermark_slider" valueholder = "category_watermark[setting][<?php echo $category_row; ?>][angle]"></div>
													<span id="category_watermark_angle_<?php echo $category_row; ?>_val"></span>&deg;
													<?php if(isset($category_watermark['setting'][$category_row]['angle'])) { ?>
														<input type="hidden" name="category_watermark[setting][<?php echo $category_row; ?>][angle]" value="<?php echo $category_watermark['setting'][$category_row]['angle']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="category_watermark[setting][<?php echo $category_row; ?>][angle]" value="0" />
													<?php } ?>
												</td>
												<td class="left">
													<div id="category_watermark_maxsize_<?php echo $category_row; ?>" class="watermark_slider_percent" valueholder = "category_watermark[setting][<?php echo $category_row; ?>][maxsize]"></div>
													<span id="category_watermark_maxsize_<?php echo $category_row; ?>_val"></span>%
													<?php if(isset($category_watermark['setting'][$category_row]['maxsize'])) { ?>
														<input type="hidden" name="category_watermark[setting][<?php echo $category_row; ?>][maxsize]" value="<?php echo $category_watermark['setting'][$category_row]['maxsize']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="category_watermark[setting][<?php echo $category_row; ?>][maxsize]" value="30" />
													<?php } ?>
												</td>
												<td class="left">
													<a class="button colorbox" watermark="category_watermark" iden="<?php echo $category_row; ?>">Set With Preview</a>
												</td>
												<td class="left">
													<a onclick="$('#category_setting_<?php echo $category_row; ?>').remove();" class="button">Remove</a>
												</td>
											</tr>
										</tbody>
									<?php $category_row++; }} ?>
									<tfoot>
										<tr>
											<td colspan="6"></td>
											<td class="left"><a onclick="addCategory();" class="button">Add Category</a></td>
										</tr>
									</tfoot>									
								</table>
							</div>
						</div>
					<?php } elseif ($sort_order == 'product_watermark') { ?>
						<div>
							<h3><a onclick="javascript:void(0);">Product Setting</a></h3>
							<div>
								<table class="form">
									<tr>
										<td> Enable Product Watermark ?</td>
										<td>
											<input type="hidden" name="product_watermark[status]" value="0"/>
											<input type="checkbox" class="on_off" name="product_watermark[status]" handler="product_watermark" value="1" <?php echo ($product_watermark['status'] == 1) ? 'checked="checked"' : ''; ?> />
										</td>
									</tr>
								</table>
								<table id="product_watermark" class="list">
									<thead>
										<tr>
											<td class="center"> Product </td>
											<td class="center"> Watermark Name</td>
											<td class="center"> Position </td>
											<td class="left"> *Angle* </td>
											<td class="left"> Max Size </td>
											<td class="left"> Preview </td>
											<td class="left"> </td>								
										</tr>
									</thead>
									<?php $product_row = 0; if(isset($product_watermark['setting'])){ 
										foreach($product_watermark['setting'] as $product_id=>$product) { ?>
										<tbody id="product_setting_<?php echo $product_row; ?>">
											<tr>
												<td class="center">
													<input type="text" name="product_watermark[setting][<?php echo $product_row; ?>][name]" value="<?php echo $product['name']; ?>"/>
													<input type="hidden" name="product_watermark[setting][<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>" />
												</td>
												<td class="center">
													<?php if($watermarks){ ?>
														<select name="product_watermark[setting][<?php echo $product_row; ?>][watermark]">
															<option value="0" > None </option>
															<?php foreach($watermarks as $watermark){ ?>
																<?php $key= $watermark['details']['wid']; ?>
																<option value="<?php echo $key; ?>" <?php if($product['watermark'] == $key) {echo 'selected="selected"' ;} ?>> <?php echo $watermark['details']['watermark_name']; ?></option>
															<?php } ?>
														</select>
													<?php } else {?>
															No Watermark exists.
															<input type="hidden" name="product_watermark[setting][<?php echo $product_row; ?>][watermark]" value="0" />
													<?php } ?>	
												</td>
												<td class="center">
													<select name="product_watermark[setting][<?php echo $product_row; ?>][position]">
														<?php foreach ($position as $position_id=>$position_name) { ?>
															<?php if( $position_id == 6 || $position_id == 7 ) { ?>
																<option disabled="disabled"><?php echo $position_name; ?></option>
															<?php } else { ?>
																<option value="<?php echo $position_id; ?>" <?php if($product['position'] == $position_id) {echo 'selected="selected"';} ?>><?php echo $position_name; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>												
												<td class="left">
													<div id="product_watermark_angle_<?php echo $product_row; ?>" class="watermark_slider" valueholder = "product_watermark[setting][<?php echo $product_row; ?>][angle]"></div>
													<span id="product_watermark_angle_<?php echo $product_row; ?>_val"></span>&deg;
													<?php if(isset($product_watermark['setting'][$product_row]['angle'])) { ?>
														<input type="hidden" name="product_watermark[setting][<?php echo $product_row; ?>][angle]" value="<?php echo $product_watermark['setting'][$product_row]['angle']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="product_watermark[setting][<?php echo $product_row; ?>][angle]" value="0" />
													<?php } ?>
												</td>
												<td class="left">
													<div id="product_watermark_maxsize_<?php echo $product_row; ?>" class="watermark_slider_percent" valueholder = "product_watermark[setting][<?php echo $product_row; ?>][maxsize]"></div>
													<span id="product_watermark_maxsize_<?php echo $product_row; ?>_val"></span>%
													<?php if(isset($product_watermark['setting'][$product_row]['maxsize'])) { ?>
														<input type="hidden" name="product_watermark[setting][<?php echo $product_row; ?>][maxsize]" value="<?php echo $product_watermark['setting'][$product_row]['maxsize']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="product_watermark[setting][<?php echo $product_row; ?>][maxsize]" value="30" />
													<?php } ?>
												</td>
												<td class="left">
													<a class="button colorbox" watermark="product_watermark" iden="<?php echo $product_row; ?>">Set With Preview</a>
												</td>
												<td class="left">
													<a onclick="$('#product_setting_<?php echo $product_row; ?>').remove();" class="button">Remove</a>
												</td>
											</tr>
										</tbody>
									<?php $product_row++; }} ?>
									<tfoot>
										<tr>
											<td colspan="6"></td>
											<td class="left"><a onclick="addProduct();" class="button">Add Product</a></td>
										</tr>
									</tfoot>									
								</table>
							</div>
						</div>
					<?php } elseif ($sort_order == 'store_watermark') { ?>
						<div>				
							<h3><a onclick="javascript:void(0);">Store Setting</a></h3>
							<div>
								<table class="form">
									<tr>
										<td> Enable Store Watermark ?</td>
										<td>
											<input type="hidden" name="store_watermark[status]" value="0"/>
											<input type="checkbox" class="on_off" name="store_watermark[status]" handler="store_watermark" value="1" <?php echo ($store_watermark['status'] == 1) ? 'checked="checked"' : ''; ?> />
										</td>
									</tr>
								</table>
								<table class="list" id="store_watermark">
									<thead>
										<tr>
											<td class="center">Store Name</td>
											<td class="center">Watermark Name</td>
											<td class="center">Position</td>											
											<td class="left"> *Angle* </td>
											<td class="left"> Max Size </td>
											<td class="left"> Preview </td>											
										</tr>
									</thead>
									<tbody>
										<?php foreach ($stores as $store) { ?>
											<tr>
												<td class="center"><?php echo $store['name'] ; ?> </td>
												<td class="center">
													<?php if($watermarks){ ?>
														<select name="store_watermark[setting][<?php echo $store['store_id']; ?>][watermark]">												
															<option value="0" > None </option>
															<?php foreach($watermarks as $watermark){ ?>
																<?php $key= $watermark['details']['wid']; ?>
																<option value="<?php echo $key; ?>"  <?php if(isset($store_watermark['setting'][$store['store_id']]['watermark']) && ($store_watermark['setting'][$store['store_id']]['watermark'] == $key)) {echo 'selected="selected"' ;} ?>> <?php echo $watermark['details']['watermark_name']; ?></option>
															<?php } ?>
														</select>
													<?php } else {?>
														No Watermark exists.
														<input type="hidden" name="store_watermark[setting][<?php echo $store['store_id']; ?>][watermark]" value="0" />
													<?php } ?>
												</td>
												<td class="center">
													<select name="store_watermark[setting][<?php echo $store['store_id']; ?>][position]">
														<?php foreach ($position as $position_id=>$position_name) { ?>
															<?php if( $position_id == 6 || $position_id == 7 ) { ?>
																<option disabled="disabled"><?php echo $position_name; ?></option>
															<?php } else { ?>
																<option value="<?php echo $position_id; ?>" <?php if(isset($store_watermark['setting'][$store['store_id']]['position']) && ($store_watermark['setting'][$store['store_id']]['position'] == $position_id)) {echo 'selected="selected"';} ?>><?php echo $position_name; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												</td>
												<td class="left">
													<div id="store_watermark_angle_<?php echo $store['store_id']; ?>" class="watermark_slider" valueholder = "store_watermark[setting][<?php echo $store['store_id']; ?>][angle]"></div>
													<span id="store_watermark_angle_<?php echo $store['store_id']; ?>_val"></span>&deg;
													<?php if(isset($store_watermark['setting'][$store['store_id']]['angle'])) { ?>
														<input type="hidden" name="store_watermark[setting][<?php echo $store['store_id']; ?>][angle]" value="<?php echo $store_watermark['setting'][$store['store_id']]['angle']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="store_watermark[setting][<?php echo $store['store_id']; ?>][angle]" value="0" />
													<?php } ?>
												</td>
												<td class="left">
													<div id="store_watermark_maxsize_<?php echo $store['store_id']; ?>" class="watermark_slider_percent" valueholder = "store_watermark[setting][<?php echo $store['store_id']; ?>][maxsize]"></div>
													<span id="store_watermark_maxsize_<?php echo $store['store_id']; ?>_val"></span>%
													<?php if(isset($store_watermark['setting'][$store['store_id']]['maxsize'])) { ?>
														<input type="hidden" name="store_watermark[setting][<?php echo $store['store_id']; ?>][maxsize]" value="<?php echo $store_watermark['setting'][$store['store_id']]['maxsize']; ?>" />
													<?php } else { ?>
														<input type="hidden" name="store_watermark[setting][<?php echo $store['store_id']; ?>][maxsize]" value="30" />
													<?php } ?>
												</td>
												<td class="left">
													<a class="button colorbox" watermark="store_watermark" iden="<?php echo $store['store_id']; ?>">Set With Preview</a>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
								<input type="hidden" name="action" value="store" />
							</div>
						</div>
					<?php } ?>
				<?php } ?>
				<div class="discription">* MaxSize is the maximum percentage, which watermark can take, in the product images.</div>
			</div>			
		</form>
	</div>
</div>
		
<div class="box">
	<div class="heading">
		<h1><img src="view/image/module.png" alt="" />Watermark Management</h1>
		<div class="buttons">
			<a href="<?php echo $create;?>" class="button"><span>New Watermark</span></a>
			<a onclick="$('#watermark-form').submit();" class="button"><span>Delete</span></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span>Cancel</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="watermark-form">
			<table class="list">
				<thead>
					<tr>
						<td width="1">
							<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
						</td>
						<td class="center">Image</td>
						<td class="center">Product Name</td>
						<td class="center">Model</td>
						<td class="right">Action</td>					
					</tr>
				</thead>
				<tbody>					
					<?php if($watermarks){ ?>
						<?php foreach($watermarks as $watermark){ ?>
						<?php $key= $watermark['details']['wid']; ?>
						<tr>
							<td style="text-align:center;">
								<input type="checkbox" name="watermark[<?php echo $key; ?>][selected]" value="1"/>
							</td>
							<td class="center" width="100px">
								<div class="image">
									<img src="<?php echo $watermark['src'];?>" id="preview_<?php echo $key; ?>" width="100px" />
								</div>
							</td>
							<td class="center"><?php echo $watermark['details']['watermark_name']; ?></td>
							<td class="center"><?php echo $watermark['type'];?></td>
							<td class="right">
								<?php foreach ($watermark['details'] as $property => $detail) { ?>
									<?php if( !is_array($detail) ) { ?>
										<input type="hidden" name="watermark[<?php echo $key; ?>][<?php echo $property; ?>]" value="<?php echo $detail; ?>"/>
									<?php } else { ?>
										<?php foreach ( $detail as $sub_property => $sub_detail) { ?>
											<input type="hidden" name="watermark[<?php echo $key; ?>][<?php echo $property; ?>][<?php echo $sub_property; ?>]" value="<?php echo $sub_detail; ?>" />
										<?php } ?>
									<?php } ?>
								<?php } ?>
								<a class="button watermark_edit" href="<?php echo $watermark['edit_url']; ?> ">Edit</a>
							</td>
						</tr>
						<?php } ?>
					<?php } else { ?>					
						<td colspan="5" class="center">No Watermarks Exist</td>
					<?php } ?>
				</tbody>
			</table>
			<input type="hidden" name="action" value="watermark" />
		</form>
		<div style="display:none">
			<div id="preview_set">
				<div id="preview_body">
					<div id="preview_image">
						<img src=""/>
					</div>
					<table class="list">
						<thead>
							<tr>
								<td class="left">Watermark Name</td>
								<td class="center">Position</td>
								<td class="center">Max Size</td>
								<td class="right" width="200px">*Angle*</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left">
									<?php if($watermarks){ ?>
										<select id="preview_watermark">												
											<option value="0" > None </option>
											<?php foreach($watermarks as $watermark){ ?>
												<?php $key= $watermark['details']['wid']; ?>
												<option value="<?php echo $key; ?>"> <?php echo $watermark['details']['watermark_name']; ?></option>
											<?php } ?>
										</select>
									<?php } else {?>
										No Watermark exists.
										<input type="hidden" id="preview_watermark" value="0" />
									<?php } ?>
									<input type="hidden" id="watermark_holder" value=""/>
								</td>
								<td class="center">
									<select id="preview_position">
										<?php foreach ($position as $position_id=>$position_name) { ?>
											<?php if( $position_id == 6 || $position_id == 7 ) { ?>
												<option disabled="disabled"><?php echo $position_name; ?></option>
											<?php } else { ?>
												<option value="<?php echo $position_id; ?>"><?php echo $position_name; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" id="position_holder" value=""/>
								</td>
								<td class="right">
									<div id="preview_angle"></div>
									<span id="preview_angle_val"></span>&deg;
									<input type="hidden" name="preview_angle" value="0" />
									<input type="hidden" id="angle_holder" value=""/>
									<input type="hidden" id="angle_slider_holder" value=""/>
								</td>
								<td class="right">
									<div id="preview_maxsize"></div>
									<span id="preview_maxsize_val"></span>&deg;
									<input type="hidden" name="preview_maxsize" value="30" />
									<input type="hidden" id="maxsize_holder" value=""/>
									<input type="hidden" id="maxsize_slider_holder" value=""/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="preview_button">
					<a class="button" id="preview_save">Save &amp; Exit</a>
					<a class="button" id="preview_cancel">Cancel</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="box">
	<div class="heading">
		<h1><img src="view/image/mail.png" alt="" />Need Help?</h1>		
	</div>
	<div class="content" style="min-height: 0 !important">
		<p> Did you follow the instructions? Click <a href="http://www.watermark.samcholi.com/index.php?route=product/document">Here</a> to see instruction guide</p>
		<p> If you have any problems, click <a href="http://www.watermark.samcholi.com/index.php?route=information/contact">Here</a> to <b>Submit Ticket</b> to developer</p>		
	</div>
</div>
<script type="text/javascript"><!--
$(function() {
	$("#wControlPanel")
		.accordion({
			header : '>div>h3',
			autoHeight: false,
			active : 4
		})
		.sortable({
			axis: "y",
			handle: "h3",
			stop: function(event, ui) {
				 ui.item.children( "h3" ).triggerHandler( "focusout" );
			}
		});

	$('input.on_off').switchButton({
	  on_label: 'Enable',
	  off_label: 'Disable',
	  width: 50,
	  height: 20,
	  button_width: 25
	});
	
	function init_slider( classname) {
		min_value = -179;
		max_value = 180;
		
		if (classname == 'watermark_slider_percent') {
			min_value = 1;
			max_value = 100;
		}
		$("." + classname ).each(function(){
			var sliderid = $(this).attr('id');
			var spanid = sliderid + '_val';
			var valueholder= $(this).attr('valueholder');
			
			$(this).slider({
				range: "min",
				value: $('input[name="' + valueholder + '"]' ).val(),
				min: min_value,
				max: max_value,
				slide: function( event, ui ) {
					$( "span#" + spanid ).text(ui.value);
					
				},
				stop: function(event, ui) {
					$( "span#" + spanid ).text(ui.value);
					$('input[name="' + valueholder + '"]' ).val(ui.value);			
				}
			});
			$( "span#" + spanid  ).text($(this).slider( "value" ));
			
		});
		
	}
	
	init_slider("watermark_slider");
	init_slider("watermark_slider_percent");
	
	$('#preview_angle').slider({
		range: "min",
		value: 0,
		min: -179,
		max: 180,
		slide: function( event, ui ) {
			$( "span#preview_angle_val").text(ui.value);
			update_preview();
		},
		stop: function(event, ui) {
			$( "span#preview_angle_val" ).text(ui.value);
			$('input[name="preview_angle"]' ).val(ui.value);
			update_preview();
		}}
	);
	
	$('#preview_maxsize').slider({
		range: "min",
		value: 30,
		min: 1,
		max: 100,
		slide: function( event, ui ) {
			$( "span#preview_maxsize_val").text(ui.value);
			update_preview();
		},
		stop: function(event, ui) {
			$( "span#preview_maxsize_val" ).text(ui.value);
			$('input[name="preview_maxsize"]' ).val(ui.value);
			update_preview();
		}}
	);
	
	
	
	$('#preview_watermark').bind('change',function(){
		update_preview();
	});
	$('#preview_position').bind('change',function(){
		update_preview();
	});
	
	
	$(".colorbox").each(function(){
		var watermark_type = $(this).attr('watermark');
		var watermark_iden = $(this).attr('iden');
		$(this).colorbox({
			inline:			true,
			href:			"#preview_set",
			innerWidth :	650,
			innerHeight:    535,
			closeButton: 	false,
			overlayClose:   false,
			onLoad: function() {
				$('#cboxClose').remove();
				
				var watermark_name = $('select[name=\''+watermark_type+'[setting]['+watermark_iden+'][watermark]\']').val();
				var watermark_position = $('select[name=\''+watermark_type+'[setting]['+watermark_iden+'][position]\']').val();
				var watermark_angle = $('input[name=\''+watermark_type+'[setting]['+watermark_iden+'][angle]\']').val();
				var watermark_maxsize = $('input[name=\''+watermark_type+'[setting]['+watermark_iden+'][maxsize]\']').val();
				
				$('#preview_watermark').val(watermark_name);
				$('#preview_position').val(watermark_position);
				$('#preview_angle').slider('value',watermark_angle);
				$( "span#preview_angle_val").text(watermark_angle);
				$('#preview_maxsize').slider('value',watermark_maxsize);
				$( "span#preview_maxsize_val").text(watermark_maxsize);
				
				$('#watermark_holder').val('select[name=\''+watermark_type+'\[setting]['+watermark_iden+'][watermark]\']');
				$('#position_holder').val('select[name=\''+watermark_type+'\[setting]['+watermark_iden+'][position]\']');
				$('#angle_holder').val('input[name=\''+watermark_type+'\[setting]['+watermark_iden+'][angle]\']');
				$('#angle_slider_holder').val(watermark_type+'_angle_'+watermark_iden);
				$('#maxsize_holder').val('input[name=\''+watermark_type+'\[setting]['+watermark_iden+'][maxsize]\']');
				$('#maxsize_slider_holder').val(watermark_type+'_maxsize_'+watermark_iden);
				
				update_preview();				
			}
		});
	});
	
	$("#preview_save").click(function(){
		$($('#watermark_holder').val()).val($('#preview_watermark').val());
		$($('#position_holder').val()).val($('#preview_position').val());
		$($('#angle_holder').val()).val($('#preview_angle').slider('value'));
		$('#'+$('#angle_slider_holder').val()).slider( 'value',	$('#preview_angle').slider('value'));
		$('#'+$('#angle_slider_holder').val() + '_val').text($('#preview_angle').slider('value'));
		$.colorbox.close();
	});
	
	$("#preview_cancel").click(function(){
		$.colorbox.close();
	});
	
	
	
});


function update_preview(){
	$('#preview_image img').attr('src','index.php?route=module/watermark/preview&token=' + '<?php echo $token; ?>' + '&name='+$('#preview_watermark').val()+'&pos='+$('#preview_position').val()+'&angle='+$('#preview_angle').slider('value') + '&maxsize='+$('#preview_maxsize').slider('value'));
}

/* Category Watermark Setting Javascript Code */
var category_row = <?php echo $category_row; ?>;

function addCategory() {
	html  = '<tbody id="category_setting_' + category_row + '">';
    html += '<tr>';									
	html += '<td class="center"><input type="text" name="category_watermark[setting][' + category_row + '][name]"/><input type="hidden" name="category_watermark[setting][' + category_row + '][category_id]" /> </td>';
	html += '    <td class="center">';
		html += '<select name="category_watermark[setting][' + category_row + '][watermark]"><option value="0" > None </option>';		
					<?php foreach($watermarks as $watermark){ ?>
						<?php $key= $watermark['details']['wid']; ?>
		html += '	<option value="<?php echo $key; ?>"> <?php echo $watermark['details']['watermark_name']; ?></option>';
					<?php } ?>
		html += '</select>';
    html += '    </td>';
	html += '    <td class="center">';
		html += '<select name="category_watermark[setting][' + category_row + '][position]">';					
		html += '	<option value="1">Top Left</option>';
		html += '	<option value="2">Top Right</option>';
		html += '	<option value="3" >Center</option>';
		html += '	<option value="4">Bottom Left</option>';
		html += '	<option value="5">Bottom Right</option>';
		html += '	<option value="6">Center Repeat</option>';
		html += '	<option value="7">Repeat</option>';
		html += '</select>';
    html += '    </td>';
	html += '	 <td class="left">';
		html += '	 <div id="category_watermark_angle_'+ category_row +'" class="watermark_slider" valueholder="cateogry_watermark[setting][' + category_row + '][angle]">';
		html += '	 </div>';
		html += '	 <span id="category_watermark_angle_' + category_row + '_val">0</span>&deg';
		html += '	 <input type="hidden" name="category_watermark[setting][' + category_row + '][angle]" value="0" />';
		
	html += '	 </td>';
	html += '	 <td class="left">';
		html += '	 <div id="category_watermark_maxsize_'+ category_row +'" class="watermark_slider" valueholder="cateogry_watermark[setting][' + category_row + '][maxsize]">';
		html += '	 </div>';
		html += '	 <span id="category_watermark_maxsize_' + category_row + '_val">30</span>&deg';
		html += '	 <input type="hidden" name="category_watermark[setting][' + category_row + '][maxsize]" value="30" />';
		
	html += '	 </td>';	
	html += '	 <td class="left">';
		html += '	 <a class="button colorbox" watermark="category_watermark"  iden = "' + category_row +'" id="category_watermark_preview_'+category_row+'">Set With Preview</a>';
	html += '	 </td>';
	html += '    <td class="left"><a onclick="$(\'#category_setting_' + category_row + '\').remove();" class="button">Remove</a></td>';
    html += '  </tr>';	
    html += '</tbody>';
	
	$('#category_watermark tfoot').before(html);
	
	$('#category_watermark_angle_'+ category_row).slider({
		range: "min",
		value: 0,
		min: -179,
		max: 180,
		slide: function( event, ui ) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);			
		},
		stop: function(event, ui) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);
			$('input[name="$' + $(this).attr("valueholder") + '"]' ).val(ui.value);		
		}
	});	
	
	$('#category_watermark_maxsize_'+ category_row).slider({
		range: "min",
		value: 30,
		min: 1,
		max: 100,
		slide: function( event, ui ) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);			
		},
		stop: function(event, ui) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);
			$('input[name="$' + $(this).attr("valueholder") + '"]' ).val(ui.value);		
		}
	});	
	
	$('#category_watermark_preview_'+category_row).colorbox({
		inline:			true,
		href:			"#preview_set",
		innerWidth :	650,
		innerHeight:    535,
		closeButton: 	false,
		overlayClose:   false,
		onLoad: function() {
			$('#cboxClose').remove();
			
			var watermark_name = $('select[name=\'category_watermark[setting]['+$(this).attr('iden')+'][watermark]\']').val();
			var watermark_position = $('select[name=\'category_watermark[setting]['+$(this).attr('iden')+'][position]\']').val();
			var watermark_angle = $('input[name=\'category_watermark[setting]['+$(this).attr('iden')+'][angle]\']').val();
			var watermark_maxsize = $('input[name=\'category_watermark[setting]['+$(this).attr('iden')+'][maxsize]\']').val();
			
			$('#preview_watermark').val(watermark_name);
			$('#preview_position').val(watermark_position);
			$('#preview_angle').slider('value',watermark_angle);
			$("span#preview_angle_val").text(watermark_angle);
			$('#preview_maxsize').slider('value',watermark_maxsize);
			$("span#preview_maxsize_val").text(watermark_maxsize);
			
			
			$('#watermark_holder').val('select[name=\'category_watermark[setting]['+$(this).attr('iden')+'][watermark]\']');
			$('#position_holder').val('select[name=\'category_watermark[setting]['+$(this).attr('iden')+'][position]\']');
			$('#angle_holder').val('input[name=\'category_watermark[setting]['+$(this).attr('iden')+'][angle]\']');
			$('#maxsize_holder').val('input[name=\'category_watermark[setting]['+$(this).attr('iden')+'][maxsize]\']');
			
			$('#angle_slider_holder').val('category_watermark_angle_'+$(this).attr('iden'));
			$('#maxsize_slider_holder').val('category_watermark_maxsize_'+$(this).attr('iden'));
			update_preview();				
		}
	});
	
	
	
	categoryautocomplete(category_row);
	
	category_row++;
}

function categoryautocomplete(category_row) {
	$('input[name=\'category_watermark[setting][' + category_row + '][name]\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {							
							label: item.name,
							value: item.category_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'category_watermark[setting][' + category_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'category_watermark[setting][' + category_row + '][category_id]\']').attr('value', ui.item.value);
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#category_watermark tbody').each(function(index, element) {
	categoryautocomplete(index);
});

/*----------------------------------------------------------------- Product Watermark Setting Javascript Code (START) -----------------------------------------------------------------*/

var product_row = <?php echo $product_row; ?>;

function addProduct() {
	html  = '<tbody id="product_setting_' + product_row + '">';
    html += '<tr>';									
	html += '<td class="center"><input type="text" name="product_watermark[setting][' + product_row + '][name]"/><input type="hidden" name="product_watermark[setting][' + product_row + '][product_id]" /> </td>';
	html += '    <td class="center">';
		html += '<select name="product_watermark[setting][' + product_row + '][watermark]"><option value="0" > None </option>';		
					<?php foreach($watermarks as $watermark){ ?>
						<?php $key= $watermark['details']['wid']; ?>
		html += '	<option value="<?php echo $key; ?>"> <?php echo $watermark['details']['watermark_name']; ?></option>';
					<?php } ?>
		html += '</select>';
    html += '    </td>';
	html += '    <td class="center">';
		html += '<select name="product_watermark[setting][' + product_row + '][position]">';					
		html += '	<option value="1">Top Left</option>';
		html += '	<option value="2">Top Right</option>';
		html += '	<option value="3" >Center</option>';
		html += '	<option value="4">Bottom Left</option>';
		html += '	<option value="5">Bottom Right</option>';
		html += '	<option value="6">Center Repeat</option>';
		html += '	<option value="7">Repeat</option>';
		html += '</select>';
    html += '    </td>';
	html += '	 <td class="left">';
		html += '	 <div id="product_watermark_angle_'+ product_row +'" class="watermark_slider" valueholder="cateogry_watermark[setting][' + product_row + '][angle]">';
		html += '	 </div>';
		html += '	 <span id="product_watermark_angle_' + product_row + '_val">0</span>&deg';
		html += '	 <input type="hidden" name="product_watermark[setting][' + product_row + '][angle]" value="0" />';
		
	html += '	 </td>';
	html += '	 <td class="left">';
		html += '	 <div id="product_watermark_maxsize_'+ product_row +'" class="watermark_slider" valueholder="cateogry_watermark[setting][' + product_row + '][maxsize]">';
		html += '	 </div>';
		html += '	 <span id="product_watermark_maxsize_' + product_row + '_val">30</span>&deg';
		html += '	 <input type="hidden" name="product_watermark[setting][' + product_row + '][maxsize]" value="30" />';
		
	html += '	 </td>';
	html += '	 <td class="left">';
		html += '	 <a class="button colorbox" watermark="product_watermark"  iden = "' + product_row +'" id="product_watermark_preview_'+product_row+'">Set With Preview</a>';
	html += '	 </td>';
	html += '    <td class="left"><a onclick="$(\'#product_setting_' + product_row + '\').remove();" class="button">Remove</a></td>';
    html += '  </tr>';	
    html += '</tbody>';
	
	$('#product_watermark tfoot').before(html);
	
	$('#product_watermark_angle_'+ product_row).slider({
		range: "min",
		value: 0,
		min: -179,
		max: 180,
		slide: function( event, ui ) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);			
		},
		stop: function(event, ui) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);
			$('input[name="$' + $(this).attr("valueholder") + '"]' ).val(ui.value);		
		}
	});
	
	$('#product_watermark_maxsize_'+ product_row).slider({
		range: "min",
		value: 30,
		min: 1,
		max: 100,
		slide: function( event, ui ) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);			
		},
		stop: function(event, ui) {
			$('span#'+$(this).attr("id")+'_val').text(ui.value);
			$('input[name="$' + $(this).attr("valueholder") + '"]' ).val(ui.value);		
		}
	});	
	
	$('#product_watermark_preview_'+product_row).colorbox({
		inline:			true,
		href:			"#preview_set",
		innerWidth :	650,
		innerHeight:    535,
		closeButton: 	false,
		overlayClose:   false,
		onLoad: function() {
			$('#cboxClose').remove();
			
			var watermark_name = $('select[name=\'product_watermark[setting]['+$(this).attr('iden')+'][watermark]\']').val();
			var watermark_position = $('select[name=\'product_watermark[setting]['+$(this).attr('iden')+'][position]\']').val();
			var watermark_angle = $('input[name=\'product_watermark[setting]['+$(this).attr('iden')+'][angle]\']').val();
			var watermark_maxsize = $('input[name=\'product_watermark[setting]['+$(this).attr('iden')+'][maxsize]\']').val();
			
			$('#preview_watermark').val(watermark_name);
			$('#preview_position').val(watermark_position);
			$('#preview_angle').slider('value',watermark_angle);
			$( "span#preview_angle_val").text(watermark_angle);
			$('#preview_maxsize').slider('value',watermark_maxsize);
			$( "span#preview_maxsize_val").text(watermark_maxsize);
			
			
			$('#watermark_holder').val('select[name=\'product_watermark[setting]['+$(this).attr('iden')+'][watermark]\']');
			$('#position_holder').val('select[name=\'product_watermark[setting]['+$(this).attr('iden')+'][position]\']');
			$('#angle_holder').val('input[name=\'product_watermark[setting]['+$(this).attr('iden')+'][angle]\']');
			$('#angle_slider_holder').val('product_watermark_angle_'+$(this).attr('iden'));			
			$('#maxsize_holder').val('input[name=\'product_watermark[setting]['+$(this).attr('iden')+'][maxsize]\']');
			$('#maxsize_slider_holder').val('product_watermark_maxsize_'+$(this).attr('iden'));
			
			update_preview();				
		}
	});
	
	
	
	productautocomplete(product_row);
	
	product_row++;
}

function productautocomplete(product_row) {
	$('input[name=\'product_watermark[setting][' + product_row + '][name]\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {							
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'product_watermark[setting][' + product_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_watermark[setting][' + product_row + '][product_id]\']').attr('value', ui.item.value);
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#product_watermark tbody').each(function(index, element) {
	productautocomplete(index);
});

/*----------------------------------------------------------------- Product Watermark Setting Javascript Code  (END)  -----------------------------------------------------------------*/

//--></script>
<?php echo $footer; ?>