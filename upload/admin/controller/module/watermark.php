<?php
class ControllerModuleWatermark extends Controller {
	private $error = array(); 
	
	public function index() {   
		
		$this->language->load('module/watermark');
		
		$this->load->model('module/watermark');
		$this->load->model('catalog/manufacturer');
		
		$this->document->addScript('view/javascript/watermark/switchButton/jquery.switchButton.js');
		$this->document->addScript('view/javascript/watermark/colorbox/jquery.colorbox-min.js');
		
		
		$this->document->addStyle('view/javascript/watermark/switchButton/jquery.switchButton.css');
		$this->document->addStyle('view/javascript/watermark/colorbox/colorbox.css');
		$this->document->addStyle('view/javascript/watermark/watermark.css');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$success = false;
			
			if(isset($this->request->post['action']))
			{
				
				if($this->request->post['action']== 'store')
				{
					$success = $this->model_module_watermark->updateApplySetting($this->request->post);
					
				} else if ($this->request->post['action']== 'watermark' && isset($this->request->post['watermark']))
				{
					$success = $this->model_module_watermark->updateWatermark($this->request->post['watermark']);
				} 
			}
			
			if($success)
			{
				$this->model_module_watermark->clearCache(DIR_IMAGE . 'cache');	
						
				$this->session->data['success'] = 'Success: You have modified Watermark Settings!';
							
				//$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Modules',
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Watermark Modification',
			'href'      => $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['create'] = $this->url->link('module/watermark/add', 'token=' . $this->session->data['token'], 'SSL');		
		
		$this->data['apply'] = $this->url->link('module/watermark/apply', 'token=' . $this->session->data['token'], 'SSL');		
		
		$this->data['preview_link'] = $this->url->link('module/watermark/preview', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		/*--- Set Init Values --*/
		
		if (isset($this->request->post['watermark_status'])) {
			$this->data['watermark_status'] = $this->request->post['watermark_status'];
		} else {
			$this->data['watermark_status'] = $this->config->get('watermark_status');
		}	
		
		$init_setting = $this->model_module_watermark->getApplySetting();
		
		$this->data['watermark_sort'] = array_keys($init_setting);
		
		if(isset($this->request->post['size_watermark'])){	
			$this->data['size_watermark'] = $this->request->post['size_watermark'];
		} else {
			$this->data['size_watermark'] = $init_setting['size_watermark'];
		}	
		if(isset($this->request->post['manufacturer_watermark'])){	
			$this->data['manufacturer_watermark'] = $this->request->post['manufacturer_watermark'];
		} else {
			$this->data['manufacturer_watermark'] = $init_setting['manufacturer_watermark'];
		}
		
		if(isset($this->request->post['category_watermark'])){	
			$this->data['category_watermark'] = $this->request->post['category_watermark'];
		} else {
			$this->data['category_watermark'] = $init_setting['category_watermark'];
		}
		
		if(isset($this->request->post['product_watermark'])){	
			$this->data['product_watermark'] = $this->request->post['product_watermark'];
		} else {
			$this->data['product_watermark'] = $init_setting['product_watermark'];
		}
		
		if(isset($this->request->post['store_watermark'])){	
			$this->data['store_watermark'] = $this->request->post['store_watermark'];
		} else {
			$this->data['store_watermark'] = $init_setting['store_watermark'];
		}
		
		
		
		/*--- Get Font Lists 
		
		$this->data['watermark_text_fonts'] = array();
		
		$watermark_text_fonts = glob("../fonts/*.ttf");
		
		foreach($watermark_text_fonts as $file)
		{
			$this->data['watermark_text_fonts'][] = substr( $file, ( strrpos( $file, "/" ) +1 ) );
		}		
		---*/
		
		/*--- Get Position Lists ---*/
		
		$this->data['position'] = array(
			1=> "Top Left",
			2=> "Top Right",
			3=> "Center",
			4=> "Bottom Left",
			5=> "Bottom Right",
			6=> "Center Repeat",
			7=> "Repeat"
		);
		
		
		/*--- Get Watermark Lists ---*/
		$results = $this->model_module_watermark->getWatermarks();
		
		$this->data['watermarks'] = array();
		foreach($results as $result)
		{
			if($result['watermark_type'])
			{
				$watermark_type = 'Image';
				$watermark_src = '../image/'.$result['watermark_image'];			
			}
			else
			{
				$watermark_type = 'Text';
				
				$watermark_text = $result['watermark_text'];
				
				$watermark_src = 'index.php?route=module/watermark/generate&token='. $this->session->data['token'] . "&font=" . $watermark_text['font']."&text=" . $watermark_text['text']."&color=" . $watermark_text['color']."&size=" . $watermark_text['font_size']."&alpha=" . $watermark_text['opacity'];
			}
			$this->data['watermarks'][] = array(
				"type"		=> $watermark_type,
				"src" 		=> $watermark_src,
				"details"	=> $result,
				"edit_url"  => $this->url->link('module/watermark/update', 'token=' . $this->session->data['token'] . '&wid='.$result['wid'], 'SSL')
			);
		}
		/*--- Get Manufacturer Lists ---*/
		$this->data['manufacturer_list'] = array();
		
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers(array());
		
		foreach ($manufacturers as $manufacturer) {
			$this->data['manufacturer_list'][] = array(
				'manufacturer_id' => $manufacturer['manufacturer_id'],
				'name'            => $manufacturer['name']			
			);
		}	
		
		/*--- Get Store Lists ---*/
		$this->data['stores'] = array();
		
		$this->data['stores'][] = array(
			'store_id'			=> 0,
			'name'     			=> $this->config->get('config_name') . $this->language->get('text_default')
			);
			
		$this->load->model('setting/store');
		
		$results = $this->model_setting_store->getStores();
		foreach($results as $result)
			$this->data['stores'][] = array(
				'store_id' 			=> $result['store_id'],
				'name'				=> $result['name']
			);	
		
		/*--- Loading Template file ---*/
		$this->template = 'module/watermark.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function preview(){
		$this->load->model('module/watermark');
		
		$image = new Image(DIR_IMAGE . 'data/sample.jpg');		
		if($this->request->get['name'] != '' && $this->request->get['name'] != '0'){
			$image->custom_watermark($this->model_module_watermark->getWatermarkById($this->request->get['name']),$this->request->get['pos'],$this->request->get['maxsize']);
		}
		$image->resize(400, 400);
		$this->response->addHeader("Content-Type: image/png");
		$this->response->setOutput(imagejpeg($image->getImage()));
	}
	
	public function generate()
	{
		
		$text = urlencode($this->request->get['text']);
		
		$font = "../fonts/".$this->request->get['font'];
		
		$size = $this->request->get['size'];
		
		$t_color=$this->request->get['color'];
		
		$angle=0;
		
		$alpha=$this->request->get['alpha'];
		
		$ary=imagettfbbox($size, $angle, $font, $text);
		$width=abs($ary[4]-$ary[0])+5;
		$height=abs($ary[5]-$ary[1])+5;
		
		
		$dst=imagecreatetruecolor($width,$height);
			
		$trans=imagecolorallocate($dst,255,255,255);
		
		imagecolortransparent($dst,$trans);
		
		imagefill($dst,0,0,$trans);
		
		$color=imagecolorallocatealpha($dst,base_convert(substr($t_color,0,2),16,10),base_convert(substr($t_color,2,2),16,10),base_convert(substr($t_color,4,2),16,10),(100-$alpha)*127/100);
		
		imagettftext($dst,$size,$angle,0,$size,$color,$font,$text);
		

		
		$this->response->addHeader("Content-Type: image/png");
		$this->response->setOutput(imagepng($dst));
	}
	
	public function add()
	{
		$this->language->load('module/watermark');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/watermark');
		$this->load->model('tool/image');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->model_module_watermark->addWatermark($this->request->post)) {
			$this->model_module_watermark->clearCache(DIR_IMAGE . 'cache');	
			$this->session->data['success'] = 'Success: You have created new watermark!';
			$this->redirect($this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		
		if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Modules',
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Watermark Modification',
			'href'      => $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Create New Watermark',
			'href'      => $this->url->link('module/watermark/add', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/watermark/add', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['watermark_text_fonts'] = array();
		
		$watermark_text_fonts = glob("../fonts/*.ttf");
		
		foreach($watermark_text_fonts as $file)
		{
			$this->data['watermark_text_fonts'][] = substr( $file, ( strrpos( $file, "/" ) +1 ) );
		}		
		
		$this->data['no_image'] = $this->model_tool_image->resize('data/watermark.png', 100, 100);
		
		$this->template = 'module/watermark_add.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function update()
	{
		$this->language->load('module/watermark');
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/watermark');
		$this->load->model('tool/image');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() && $this->model_module_watermark->editWatermark($this->request->post, $this->request->get['wid'])) {
			$this->model_module_watermark->clearCache(DIR_IMAGE . 'cache');	
			$this->session->data['success'] = 'Success: You have updated new watermark!';
			$this->redirect($this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		
		if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Modules',
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Watermark Modification',
			'href'      => $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Create New Watermark',
			'href'      => $this->url->link('module/watermark/update', 'token=' . $this->session->data['token'] . '&wid=' . $this->request->get['wid'], 'SSL'),
      		'separator' => ' :: '
   		);
		
				
		$this->data['action'] = $this->url->link('module/watermark/update', 'token=' . $this->session->data['token']. '&wid=' . $this->request->get['wid'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('module/watermark', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['token'] = $this->session->data['token'];
		
		$results = $this->model_module_watermark->getWatermarks();

		foreach ($results as $result){
			if($result['wid'] == $this->request->get['wid']){
				
				$result['watermark_thumb'] = $this->model_tool_image->resize($result['watermark_image'], 100, 100);
				$this->data['watermark'] = $result;			
			}
		}
		
			
		$this->data['watermark_text_fonts'] = array();
		
		$watermark_text_fonts = glob("../fonts/*.ttf");
		
		foreach($watermark_text_fonts as $file)
		{
			$this->data['watermark_text_fonts'][] = substr( $file, ( strrpos( $file, "/" ) +1 ) );
		}		
		
		$this->data['no_image'] = $this->model_tool_image->resize('data/watermark.png', 100, 100);
		
		$this->template = 'module/watermark_update.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/watermark')) {
			$this->error['warning'] = "Warning: You do not have permission to modify this module!";
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	private function getManufacturersDetailByStoreid($store_id)
	{
		$this->load->model('module/watermark');
		$this->load->model('catalog/manufacturer');
		
		$data = array();
		
		$manufacturer_ids = $this->model_module_watermark->getManufacturersByStoreid($store_id);
		
		foreach($manufacturer_ids as $id)
			$data[] = $this->model_catalog_manufacturer->getManufacturer($id);
			
		return $data;
	}
	
	public function install(){
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'watermark', `key` = 'watermark_settings', `value` = '" . $this->db->escape(serialize(array())) . "', serialized = '1'");
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'watermark', `key` = 'watermark_status', `value` = '0', serialized = '0'");
		
		$init_setting = array();
		
		$init_setting['size_watermark'] = array();
		$init_setting['product_watermark'] = array();
		$init_setting['category_watermark'] = array();
		$init_setting['manufacturer_watermark'] = array();
		$init_setting['store_watermark'] = array();
		
		$init_setting['size_watermark']['status'] = 0;
		$init_setting['size_watermark']['setting'] = array();
		
		$init_setting['category_watermark']['status'] = 0;
		$init_setting['category_watermark']['setting'] = array();
		
		$init_setting['product_watermark']['status'] = 0;
		$init_setting['product_watermark']['setting'] = array();
		
		$init_setting['manufacturer_watermark']['status'] = 0;
		$init_setting['manufacturer_watermark']['setting'] = array();
		
		$init_setting['store_watermark']['status'] = 0;
		$init_setting['store_watermark']['setting'] = array();
		
		$init_setting['size_watermark']['setting'][0]['name'] = "Cateogry Image";
		$init_setting['size_watermark']['setting'][0]['value'] = "config_image_category";
		$init_setting['size_watermark']['setting'][0]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][0]['position'] = 1;
		$init_setting['size_watermark']['setting'][0]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][1]['name'] = "Product Thumb Image";
		$init_setting['size_watermark']['setting'][1]['value'] = "config_image_thumb";
		$init_setting['size_watermark']['setting'][1]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][1]['position'] = 1;
		$init_setting['size_watermark']['setting'][1]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][2]['name'] = "Product Popup Image";
		$init_setting['size_watermark']['setting'][2]['value'] = "config_image_popup";
		$init_setting['size_watermark']['setting'][2]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][2]['position'] = 1;
		$init_setting['size_watermark']['setting'][2]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][3]['name'] = "Product List Image";
		$init_setting['size_watermark']['setting'][3]['value'] = "config_image_product";
		$init_setting['size_watermark']['setting'][3]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][3]['position'] = 1;
		$init_setting['size_watermark']['setting'][3]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][4]['name'] = "Additional Product Image";
		$init_setting['size_watermark']['setting'][4]['value'] = "config_image_additional";
		$init_setting['size_watermark']['setting'][4]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][4]['position'] = 1;
		$init_setting['size_watermark']['setting'][4]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][5]['name'] = "Related Product Image";
		$init_setting['size_watermark']['setting'][5]['value'] = "config_image_related";
		$init_setting['size_watermark']['setting'][5]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][5]['position'] = 1;
		$init_setting['size_watermark']['setting'][5]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][6]['name'] = "Compare Image";
		$init_setting['size_watermark']['setting'][6]['value'] = "config_image_compare";
		$init_setting['size_watermark']['setting'][6]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][6]['position'] = 1;
		$init_setting['size_watermark']['setting'][6]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][7]['name'] = "Wishlist Image";
		$init_setting['size_watermark']['setting'][7]['value'] = "config_image_wishlist";
		$init_setting['size_watermark']['setting'][7]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][7]['position'] = 1;
		$init_setting['size_watermark']['setting'][7]['maxsize'] = 30;
		
		$init_setting['size_watermark']['setting'][8]['name'] = "Cart Image";
		$init_setting['size_watermark']['setting'][8]['value'] = "config_image_cart";
		$init_setting['size_watermark']['setting'][8]['watermark'] = 0;
		$init_setting['size_watermark']['setting'][8]['position'] = 1;
		$init_setting['size_watermark']['setting'][8]['maxsize'] = 30;	
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'watermark', `key` = 'watermark_apply', `value` = '" . $this->db->escape(serialize($init_setting)) . "', serialized = '1'");
	}
	
	public function uninstall(){
	}

	
}
?>