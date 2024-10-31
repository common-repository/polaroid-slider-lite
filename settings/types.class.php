<?php
if (!class_exists("wpdreamsType")) {
  class wpdreamsType {
  	protected static $_instancenumber = 0;
  	protected static $_errors = 0;
  	protected static $_globalerrormsg = "Only integer values are accepted!";
  	function __construct($name, $label, $data, $constraints = null, $errormsg = "") {
  		$this->name        = $name;
  		$this->label       = $label;
  		$this->constraints = $constraints;
  		$this->errormsg    = $errormsg;
  		$this->data        = $data;
  		$this->isError     = false;
  		self::$_instancenumber++;
  		$this->getType();
  	}
  	function getData() {
  		return $this->data;
  	}
  	final function getName() {
  		return $this->name;
  	}
  	final function getError() {
  		return $this->isError;
  	}
  	final function getErrorMsg() {
  		return $this->errormsg;
  	}
  	final function setError($error, $errormsg = "") {
  		if ($errormsg != "")
  			$this->errormsg = $errormsg;
  		if ($error) {
  			self::$_errors++;
  			$this->isError = true;
  		}
  	}
  	protected final function checkData() {
  		$this->newData = $_POST[$this->name];
  		if (is_array($this->constraints)) {
  			foreach ($this->constraints as $key => $val) {
  				if ($this->constraints[$key]['op'] == "eq") {
  					if ($val['func']($this->newData) == $this->constraints[$key]['val']) {
  						;
  					} else {
  						$this->setError(true);
  						return false;
  					}
  				} else if ($this->constraints[$key]['op'] == "ge") {
  					if ($val['func']($this->newData) >= $this->constraints[$key]['val']) {
  						;
  					} else {
  						$this->setError(true);
  						return false;
  					}
  				} else {
  					if ($val['func']($this->newData) < $this->constraints[$key]['val']) {
  						;
  					} else {
  						$this->setError(true);
  						return false;
  					}
  				}
  			}
  		}
  		$this->data = $this->newData;
  		return true;
  	}
  	protected function getType() {
  		if (isset($_POST[$this->name])) {
  			if (!$this->checkData() || $this->getError()) {
  				/*errormessage*/
  				echo "<div class='errorMsg'>" . (($this->errormsg != "") ? $this->errormsg : self::$_globalerrormsg) . "</div>";
  			} else {
  				$this->data = $_POST[$this->name];
  			}
  		}
  	}
  	static function getErrorNum() {
  		return self::$_errors;
  	}
  }
}

if (!class_exists("wpdreamsHidden")) {
  class wpdreamsHidden extends wpdreamsType {
  	function getType() {
  		echo "<input type='hidden' id='wpdreamshidden_" . self::$_instancenumber . "' name='" . $this->name . "' value='" . $this->data . "' />";
  	}
  }
}

if (!class_exists("wpdreamsInfo")) {
  class wpdreamsInfo extends wpdreamsType {
  	function __construct($data) {
  		$this->data = $data;
  		$this->getType();
  	}
  	function getType() {
  		echo "<img class='infoimage' src='" . plugins_url('/types/icons/info.png', __FILE__) . "' title='" . $this->data . "' />";
  	}
  }
}

if (!class_exists("wpdreamsText")) {
  class wpdreamsText extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		if ($this->label != "")
  			echo "<label for='wpdreamstext_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input type='text' id='wpdreamstext_" . self::$_instancenumber . "' name='" . $this->name . "' value='" . $this->data . "' />";
  	}
  }
}

if (!class_exists("wpdreamsTextarea")) {
  class wpdreamsTextarea extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		echo "<label style='vertical-align: top;' for='wpdreamstextarea_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<textarea id='wpdreamstextarea_" . self::$_instancenumber . "' name='" . $this->name . "'>" . stripcslashes($this->data) . "</textarea>";
  	}
  } 
}

if (!class_exists("wpdreamsUpload")) {
  class wpdreamsUpload extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		echo "<div>";
  		if ($this->data != "") {
  			echo "<img class='preview' rel='#overlay_" . self::$_instancenumber . "' src=" . $this->data . " />";
  		} else {
  			echo "<img class='preview' style='display:none;'  rel='#overlay_" . self::$_instancenumber . "' />";
  		}
  		echo "<label for='wpdreamsUpload_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input type='text' class='wpdreamsUpload' id='wpdreamsUpload_" . self::$_instancenumber . "' name='" . $this->name . "' value='" . $this->data . "' />";
  		echo "<input class='wpdreamsUpload_button'type='button' value='Upload Image' />";
  		echo "<br />Enter an URL or upload an image!";
  		echo "<div class='overlay' id='overlay_" . self::$_instancenumber . "'><img src=" . $this->data . " /></div>";
  		echo "</div>";
  	}
  }
}

if (!class_exists("wpdreamsLabelPosition")) {
  class wpdreamsLabelPosition extends wpdreamsType {
  	function __construct($name, $label, $width, $height, $data) {
  		$this->constraints = null;
  		$this->name        = $name;
  		$this->label       = $label;
  		$this->data        = $data;
  		$this->width       = $width;
  		$this->height      = $height;
  		$this->ratio       = 400 / $this->width;
  		$this->cheight     = $this->ratio * $this->height;
  		self::$_instancenumber++;
  		$this->direction = "";
  		$this->duration  = "";
  		$this->getType();
  	}
  	function getType() {
  		parent::getType();
  		$this->processData();
  		$inst = self::$_instancenumber;
  		echo "
        <div class='labeldrag' id='labeldrag_" . $inst . "' style='height:" . ($this->cheight + 90) . "px;'>
           <div class='inner' style='overflow:auto;width:400px;height:" . $this->cheight . "px;'>
              <script>
                jQuery(document).ready(function() { 
                  var drag = jQuery('#" . $this->name . "_" . $inst . "').draggable({ containment: 'parent', refreshPositions: true, appendTo: 'body' });
                  jQuery('#" . $this->name . "_" . $inst . "').bind( 'dragstop', function(event, ui) {
                      var pos = drag.position();
                      var ratio = " . $this->ratio . ";
                      var hidden = jQuery('#labelposition_hidden_" . $inst . "');
                      var duration = jQuery('input[name=\"induration_" . $this->name . "\"]')[0];
                      var direction= jQuery('input[name=\"indirection_" . $this->name . "\"]').prev();
                      jQuery(hidden).val('duration:'+jQuery(duration).val()+';direction:'+jQuery(direction).val()+';position:'+((pos.top+5)/ratio)+'||'+((pos.left+5)/ratio)+';');
                  });
                  jQuery('#labeldrag_" . $inst . " input').keyup(function(){
                     jQuery('#" . $this->name . "_" . $inst . "').trigger('dragstop');
                  });
                  jQuery('#labeldrag_" . $inst . " select').change(function(){
                     jQuery('#" . $this->name . "_" . $inst . "').trigger('dragstop');
                  });                 
                });
              </script>
              <div class='dragme' style='top:" . (($this->top * $this->ratio) - 5) . "px;left:" . (($this->left * $this->ratio) - 5) . "px;' id='" . $this->name . "_" . $inst . "'>
              </div>
           </div>
      ";
  		echo "<div style='margin-top:" . ($this->cheight + 10) . "px;'>";
  		new wpdreamsSelect("indirection_" . $this->name, "Animation direction", $this->_direction);
  		new wpdreamsText("induration_" . $this->name, "Animation duration (ms)", $this->duration);
  		echo "</div>";
  		echo "
        </div>
        <div style='clear:both'></div>
        <input type='hidden' id='labelposition_hidden_" . $inst . "' name='" . $this->name . "' value='" . $this->data . "' />
      ";
  		echo "
      
      ";
  	}
  	function processData() {
  		// string: 'duration:123;direction:bottom-left;position:123||321;'
  		$this->data = str_replace("\n", "", $this->data);
  		preg_match("/duration:(.*?);/", $this->data, $matches);
  		$this->duration = $matches[1];
  		if ($this->duration == "")
  			$this->duration = 500;
  		preg_match("/direction:(.*?);/", $this->data, $matches);
  		$this->direction = $matches[1];
  		if ($this->direction == "")
  			$this->direction = "top-left";
  		$this->_direction = "
        Top|top;
        Bottom|bottom;
        Left|left;
        Right|right;
        Bottom-Left|bottom-left;
        Bottom-Right|bottom-right;
        Top-Left|top-left;
        Top-Right|top-right;
        Random|random|| 
      " . $this->direction;
  		preg_match("/position:(.*?);/", $this->data, $matches);
  		$this->position = $matches[1];
  		$_temp          = explode("||", $this->position);
  		$this->top      = $_temp[0];
  		$this->left     = $_temp[1];
  	}
  }
}

if (!class_exists("wpdreamsImageParser")) {
  class wpdreamsImageParser extends wpdreamsType {
  	function __construct($name, $label, $uid, $callback) {
  		$this->name     = $name;
  		$this->uid      = $uid;
  		$this->label    = $label;
  		$this->callback = $callback;
  		$this->isError  = false;
  		self::$_instancenumber++;
  		$this->getType();
  	}
  	function getType() {
  		echo "<form name='" . $this->name . "' class='wpdreams-ajaxinput' style='height:40px;margin-left: -535px;'>";
  		//echo "<label for='wpdreamsAjaxInput_".self::$_instancenumber."'>".$this->label."</label>";
  		echo "<input type='hidden' name='callback' value='" . $this->callback . "' />";
  		echo "<input type='hidden' name='uid' value='" . $this->uid . "' />";
  		echo "<input type='text' id='wpdreamsAjaxInput_" . self::$_instancenumber . "' name='url' value='Enter the feed url here...' />";
  		echo "
      <select style='width: 70px;' name='itemsnum'>
           <option value='1'>1</option>
           <option value='2'>2</option>
           <option value='3'>3</option>
           <option value='4'>4</option>
           <option value='5'>5</option>
           <option value='6'>6</option>
           <option value='7'>7</option>
           <option value='8'>8</option>
           <option value='9'>9</option>
           <option value='10'>10</option>
      </select>";
  		echo "<select  style='width: 130px;' name='itemsnum'>";
  		echo "
              <option value='flickr'>Source</option>
              <option value='flickr'>Flickr.com</option>
              <option value='500px'>500px.com</option>
      ";
  		echo "</select>";
  		echo "<input type='button' class='default' value='Generate!'/>";
  		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $this->label . "<img opened='0' style='cursor:pointer;vertical-align:middle;height:20px;' src='" . plugins_url('/types/icons/arrow-right.png', __FILE__) . "' />";
  		echo "</form>";
  	}
  }
}

if (!class_exists("wpdreamsUploadReset")) {
  class wpdreamsUploadReset extends wpdreamsType {
  	function __construct($name, $label, $data, $default_data, $constraints = null, $errormsg = "") {
  		$this->name         = $name;
  		$this->label        = $label;
  		$this->constraints  = $constraints;
  		$this->errormsg     = $errormsg;
  		$this->data         = $data;
  		$this->default_data = $default_data;
  		$this->isError      = false;
  		self::$_instancenumber++;
  		$this->getType();
  	}
  	function getType() {
  		parent::getType();
  		echo "<div>";
  		if ($this->data != "") {
  			echo "<img class='preview' rel='#overlay_" . self::$_instancenumber . "' src=" . $this->data . " />";
  		} else {
  			echo "<img class='preview' style='display:none;' rel='#overlay_" . self::$_instancenumber . "' />";
  		}
  		echo "<label for='wpdreamsUploadReset_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input type='text' class='wpdreamsUpload' id='wpdreamsUploadReset_" . self::$_instancenumber . "' name='" . $this->name . "' value='" . $this->data . "' />";
  		echo "<input class='wpdreamsUpload_button' type='button' value='Upload Image' />";
  		echo "<input type='button' class='default' name='default' value='Default' />";
  		echo "<input type='hidden' value='" . $this->default_data . "' />";
  		echo "<br />Enter an URL or upload an image!";
  		echo "<div class='overlay' id='overlay_" . self::$_instancenumber . "'><img src='" . $this->data . "'' /></div>";
  		echo "</div>";
  	}
  }
}

if (!class_exists("wpdreamsSelect")) {
  class wpdreamsSelect extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		$this->processData();
  		echo "<label for='wpdreamsselect_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<select class='wpdreamsselect' id='wpdreamsselect_" . self::$_instancenumber . "' name='" . $this->name . "_select'>";
  		foreach ($this->selects as $sel) {
  			preg_match('/(?<option>.*?)\\|(?<value>.*)/', $sel, $matches);
  			$matches['value']  = trim($matches['value']);
  			$matches['option'] = trim($matches['option']);
  			if ($matches['value'] == $this->selected)
  				echo "<option value='" . $matches['value'] . "' selected='selected'>" . $matches['option'] . "</option>";
  			else
  				echo "<option value='" . $matches['value'] . "'>" . $matches['option'] . "</option>";
  		}
  		echo "</select>";
  		echo "<input type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
  	}
  	function processData() {
  		//$this->data = str_replace("\n","",$this->data); 
  		$_temp          = explode("||", $this->data);
  		$this->selects  = explode(";", $_temp[0]);
  		$this->selected = trim($_temp[1]);
  	}
  	final function getData() {
  		return $this->data;
  	}
  	final function getSelected() {
  		return $this->selected;
  	}
  }
}

if (!class_exists("wpdreamsFont")) {
  class wpdreamsFont extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		wp_register_script('wpdreams-fonts', plugin_dir_url(__FILE__) . '/types/fonts.js', array(
  			'jquery',
  			'media-upload',
  			'thickbox'
  		));
  		wp_enqueue_script('wpdreams-fonts');
  		preg_match("/family:(.*?);/", $this->data, $_fonts);
  		$this->font = $_fonts[1];
  		preg_match("/weight:(.*?);/", $this->data, $_weight);
  		$this->weight = $_weight[1];
  		preg_match("/color:(.*?);/", $this->data, $_color);
  		$this->color = $_color[1];
  		preg_match("/size:(.*?);/", $this->data, $_size);
  		$this->size    = $_size[1];
  		$applied_style = "font-family:" . $this->font . ";font-weight:" . $this->weight . ";color:" . $this->color;
  		echo $this->getScript();
  		echo "<div>";
  		echo "<label for='wpdreamsfont_" . self::$_instancenumber . "' style='" . $applied_style . "'>" . $this->label . "</label>";
  		echo "<select class='wpdreamsfont' id='wpdreamsfont_" . self::$_instancenumber . "' name='" . $this->name . "_select'>";
  		$options = '
        <option disabled>-------Classic Webfonts-------</option>
        <option value="\'Arial\', Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif">Arial, Helvetica, sans-serif</option>
        <option value="\'Arial Black\', Gadget, sans-serif" style="font-family:\'Arial Black\', Gadget, sans-serif">"Arial Black", Gadget, sans-serif</option>
        <option value="\'Comic Sans MS\', cursive" style="font-family:\'Comic Sans MS\', cursive">"Comic Sans MS", cursive</option>
        <option value="\'Courier New\', Courier, monospace" style="font-family:\'Courier New\', Courier, monospace">"Courier New", Courier, monospace</option>
        <option value="\'Georgia\', serif" style="font-family:Georgia, serif">Georgia, serif</option>
        <option value="\'Impact\', Charcoal, sans-serif" style="font-family:Impact, Charcoal, sans-serif">Impact, Charcoal, sans-serif</option>
        <option value="\'Lucida Console\', Monaco, monospace" style="font-family:\'Lucida Console\', Monaco, monospace">"Lucida Console", Monaco, monospace</option>
        <option value="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif" style="font-family:\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
        <option value="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif" style="font-family:\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
        <option value="\'Tahoma\', Geneva, sans-serif" style="font-family:Tahoma, Geneva, sans-serif">Tahoma, Geneva, sans-serif</option>
        <option value="\'Times New Roman\', Times, serif" style="font-family:\'Times New Roman\', Times, serif">"Times New Roman", Times, serif</option>
        <option value="\'Trebuchet MS\', Helvetica, sans-serif" style="font-family:\'Trebuchet MS\', Helvetica, sans-serif">"Trebuchet MS", Helvetica, sans-serif</option>
        <option value="\'Verdana\', Geneva, sans-serif" style="font-family:Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>
        <option value="\'Symbol\'" style="font-family:Symbol">Symbol</option>
        <option value="\'Webdings\'" style="font-family:Webdings">Webdings</option>
        <option value="\'Wingdings\', \'Zapf Dingbats\'" style="font-family:Wingdings, \'Zapf Dingbats\'">Wingdings, "Zapf Dingbats"</option>
        <option value="\'MS Sans Serif\', Geneva, sans-serif" style="font-family:\'MS Sans Serif\', Geneva, sans-serif">"MS Sans Serif", Geneva, sans-serif</option>
        <option value="\'MS Serif\', \'New York\', serif" style="font-family:\'MS Serif\', \'New York\', serif">"MS Serif", "New York", serif</option>
        <option disabled>-------Google Webfonts-------</option>
        <option  value="Allan" style="font-family: Allan,Allan;"> Allan</option>
        <option  value="Allerta" style="font-family: Allerta,Allerta;"> Allerta</option>
        <option  value="Allerta Stencil" style="font-family: Allerta Stencil,Allerta Stencil;"> Allerta Stencil</option>
        <option  value="Anonymous Pro" style="font-family: Anonymous Pro,Anonymous Pro;"> Anonymous Pro</option>
        <option  value="Arimo" style="font-family: Arimo,Arimo;"> Arimo</option>
        <option  value="Arvo" style="font-family: Arvo,Arvo;"> Arvo</option>
        <option  value="Bentham" style="font-family: Bentham,Bentham;"> Bentham</option>
        <option  value="Buda" style="font-family: Buda,Buda;"> Buda</option>
        <option  value="Cabin" style="font-family: Cabin,Cabin;"> Cabin</option>
        <option  value="Calligraffitti" style="font-family: Calligraffitti,Calligraffitti;"> Calligraffitti</option>
        <option  value="Cantarell" style="font-family: Cantarell,Cantarell;"> Cantarell</option>
        <option  value="Cardo" style="font-family: Cardo,Cardo;"> Cardo</option>
        <option  value="Cherry Cream Soda" style="font-family: Cherry Cream Soda,Cherry Cream Soda;"> Cherry Cream Soda</option>
        <option  value="Chewy" style="font-family: Chewy,Chewy;"> Chewy</option>
        <option  value="Coda" style="font-family: Coda,Coda;"> Coda</option>
        <option  value="Coming Soon" style="font-family: Coming Soon,Coming Soon;"> Coming Soon</option>
        <option  value="Copse" style="font-family: Copse,Copse;"> Copse</option>
        <option  value="Corben" style="font-family: Corben,Corben;"> Corben</option>
        <option  value="Cousine" style="font-family: Cousine,Cousine;"> Cousine</option>
        <option  value="Covered By Your Grace" style="font-family: Covered By Your Grace,Covered By Your Grace;"> Covered By Your Grace</option>
        <option  value="Crafty Girls" style="font-family: Crafty Girls,Crafty Girls;"> Crafty Girls</option>
        <option  value="Crimson Text" style="font-family: Crimson Text,Crimson Text;"> Crimson Text</option>
        <option  value="Crushed" style="font-family: Crushed,Crushed;"> Crushed</option>
        <option  value="Cuprum" style="font-family: Cuprum,Cuprum;"> Cuprum</option>
        <option  value="Droid Sans" style="font-family: Droid Sans,Droid Sans;"> Droid Sans</option>
        <option  value="Droid Sans Mono" style="font-family: Droid Sans Mono,Droid Sans Mono;"> Droid Sans Mono</option>
        <option  value="Droid Serif" style="font-family: Droid Serif,Droid Serif;"> Droid Serif</option>
        <option  value="Fontdiner Swanky" style="font-family: Fontdiner Swanky,Fontdiner Swanky;"> Fontdiner Swanky</option>
        <option  value="GFS Didot" style="font-family: GFS Didot,GFS Didot;"> GFS Didot</option>
        <option  value="GFS Neohellenic" style="font-family: GFS Neohellenic,GFS Neohellenic;"> GFS Neohellenic</option>
        <option  value="Geo" style="font-family: Geo,Geo;"> Geo</option>
        <option  value="Gruppo" style="font-family: Gruppo,Gruppo;"> Gruppo</option>
        <option  value="Hanuman" style="font-family: Hanuman,Hanuman;"> Hanuman</option>
        <option  value="Homemade Apple" style="font-family: Homemade Apple,Homemade Apple;"> Homemade Apple</option>
        <option  value="IM Fell DW Pica" style="font-family: IM Fell DW Pica,IM Fell DW Pica;"> IM Fell DW Pica</option>
        <option  value="IM Fell DW Pica SC" style="font-family: IM Fell DW Pica SC,IM Fell DW Pica SC;"> IM Fell DW Pica SC</option>
        <option  value="IM Fell Double Pica" style="font-family: IM Fell Double Pica,IM Fell Double Pica;"> IM Fell Double Pica</option>
        <option  value="IM Fell Double Pica SC" style="font-family: IM Fell Double Pica SC,IM Fell Double Pica SC;"> IM Fell Double Pica SC</option>
        <option  value="IM Fell English" style="font-family: IM Fell English,IM Fell English;"> IM Fell English</option>
        <option  value="IM Fell English SC" style="font-family: IM Fell English SC,IM Fell English SC;"> IM Fell English SC</option>
        <option  value="IM Fell French Canon" style="font-family: IM Fell French Canon,IM Fell French Canon;"> IM Fell French Canon</option>
        <option  value="IM Fell French Canon SC" style="font-family: IM Fell French Canon SC,IM Fell French Canon SC;"> IM Fell French Canon SC</option>
        <option  value="IM Fell Great Primer" style="font-family: IM Fell Great Primer,IM Fell Great Primer;"> IM Fell Great Primer</option>
        <option  value="IM Fell Great Primer SC" style="font-family: IM Fell Great Primer SC,IM Fell Great Primer SC;"> IM Fell Great Primer SC</option>
        <option  value="Inconsolata" style="font-family: Inconsolata,Inconsolata;"> Inconsolata</option>
        <option  value="Irish Growler" style="font-family: Irish Growler,Irish Growler;"> Irish Growler</option>
        <option  value="Josefin Sans" style="font-family: Josefin Sans,Josefin Sans;"> Josefin Sans</option>
        <option  value="Josefin Slab" style="font-family: Josefin Slab,Josefin Slab;"> Josefin Slab</option>
        <option  value="Just Another Hand" style="font-family: Just Another Hand,Just Another Hand;"> Just Another Hand</option>
        <option  value="Just Me Again Down Here" style="font-family: Just Me Again Down Here,Just Me Again Down Here;"> Just Me Again Down Here</option>
        <option  value="Kenia" style="font-family: Kenia,Kenia;"> Kenia</option>
        <option  value="Kranky" style="font-family: Kranky,Kranky;"> Kranky</option>
        <option  value="Kristi" style="font-family: Kristi,Kristi;"> Kristi</option>
        <option  value="Lato" style="font-family: Lato,Lato;"> Lato</option>
        <option  value="Lekton" style="font-family: Lekton,Lekton;"> Lekton</option>
        <option  value="Lobster" style="font-family: Lobster,Lobster;"> Lobster</option>
        <option  value="Luckiest Guy" style="font-family: Luckiest Guy,Luckiest Guy;"> Luckiest Guy</option>
        <option  value="Merriweather" style="font-family: Merriweather,Merriweather;"> Merriweather</option>
        <option  value="Molengo" style="font-family: Molengo,Molengo;"> Molengo</option>
        <option  value="Mountains of Christmas" style="font-family: Mountains of Christmas,Mountains of Christmas;"> Mountains of Christmas</option>
        <option  value="Neucha" style="font-family: Neucha,Neucha;"> Neucha</option>
        <option  value="Neuton" style="font-family: Neuton,Neuton;"> Neuton</option>
        <option  value="Nobile" style="font-family: Nobile,Nobile;"> Nobile</option>
        <option  value="OFL Sorts Mill Goudy TT" style="font-family: OFL Sorts Mill Goudy TT,OFL Sorts Mill Goudy TT;"> OFL Sorts Mill Goudy TT</option>
        <option  value="Old Standard TT" style="font-family: Old Standard TT,Old Standard TT;"> Old Standard TT</option>
        <option  value="Orbitron" style="font-family: Orbitron,Orbitron;"> Orbitron</option>
        <option  value="PT Sans" style="font-family: PT Sans,PT Sans;"> PT Sans</option>
        <option  value="PT Sans Caption" style="font-family: PT Sans Caption,PT Sans Caption;"> PT Sans Caption</option>
        <option  value="PT Sans Narrow" style="font-family: PT Sans Narrow,PT Sans Narrow;"> PT Sans Narrow</option>
        <option  value="Permanent Marker" style="font-family: Permanent Marker,Permanent Marker;"> Permanent Marker</option>
        <option  value="Philosopher" style="font-family: Philosopher,Philosopher;"> Philosopher</option>
        <option  value="Puritan" style="font-family: Puritan,Puritan;"> Puritan</option>
        <option  value="Raleway" style="font-family: Raleway,Raleway;"> Raleway</option>
        <option  value="Reenie Beanie" style="font-family: Reenie Beanie,Reenie Beanie;"> Reenie Beanie</option>
        <option  value="Rock Salt" style="font-family: Rock Salt,Rock Salt;"> Rock Salt</option>
        <option  value="Schoolbell" style="font-family: Schoolbell,Schoolbell;"> Schoolbell</option>
        <option  value="Slackey" style="font-family: Slackey,Slackey;"> Slackey</option>
        <option  value="Sniglet" style="font-family: Sniglet,Sniglet;"> Sniglet</option>
        <option  value="Sunshiney" style="font-family: Sunshiney,Sunshiney;"> Sunshiney</option>
        <option  value="Syncopate" style="font-family: Syncopate,Syncopate;"> Syncopate</option>
        <option  value="Tangerine" style="font-family: Tangerine,Tangerine;"> Tangerine</option>
        <option  value="Tinos" style="font-family: Tinos,Tinos;"> Tinos</option>
        <option  value="Ubuntu" style="font-family: Ubuntu,Ubuntu;"> Ubuntu</option>
        <option  value="UnifrakturCook" style="font-family: UnifrakturCook,UnifrakturCook;"> UnifrakturCook</option>
        <option  value="UnifrakturMaguntia" style="font-family: UnifrakturMaguntia,UnifrakturMaguntia;"> UnifrakturMaguntia</option>
        <option  value="Unkempt" style="font-family: Unkempt,Unkempt;"> Unkempt</option>
        <option  value="Vibur" style="font-family: Vibur,Vibur;"> Vibur</option>
        <option  value="Vollkorn" style="font-family: Vollkorn,Vollkorn;"> Vollkorn</option>
        <option  value="Walter Turncoat" style="font-family: Walter Turncoat,Walter Turncoat;"> Walter Turncoat</option>
        <option  value="Yanone Kaffeesatz" style="font-family: Yanone Kaffeesatz,Yanone Kaffeesatz;"> Yanone Kaffeesatz</option> 
      ';
  		$options = explode("<option", $options);
  		unset($options[0]);
  		foreach ($options as $option) {
  			if (strpos(stripslashes($option), '"' . stripslashes($this->font) . '"') !== false) {
  				echo "<option selected='selected' " . $option;
  			} else {
  				echo "<option " . $option;
  			}
  		}
  		if ($this->weight == "")
  			$this->weight = "normal";
  		echo "</select>";
  		echo "<input type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
  		echo "<input class='wpdreans-fontweight' name='" . $this->name . self::$_instancenumber . "_font-weight' type='radio' value='normal' " . (($this->weight == 'normal') ? 'checked' : '') . ">Normal</input>";
  		echo "<input class='wpdreans-fontweight' name='" . $this->name . self::$_instancenumber . "_font-weight' type='radio' value='bold' " . (($this->weight == 'bold') ? 'checked' : '') . ">Bold</input>";
  		new wpdreamsColorPicker($this->name . "_color", "", (isset($this->color) ? $this->color : "#000000"));
  		echo "<br />" . $this->label . " size (ex.:10em, 10px or 110%): ";
  		echo "<input type='text' class='wpdreams-fontsize' style='width:70px;' name='" . $this->name . "_size' value='" . $this->size . "' />";
  		new wpdreamsInfo("You can enter the font size in pixels, ems or in percents. For example: 10px or 1.3em or 120%");
  		echo "</div>";
  	}
  	final function getData() {
  		return $this->data;
  	}
  	final function getScript() {
  		if (strpos($this->font, "'"))
  			return;
  		$font = str_replace(" ", "+", trim($this->font));
  		ob_start();
  ?>
    <style>
      @import url(http://fonts.googleapis.com/css?family=<?php echo $font; ?>:300|<?php echo $font; ?>:400|<?php echo $font; ?>:700);
    </style>
    <?php
  		$out = ob_get_contents();
  		ob_end_clean();
  		return $out;
  	}
  }
}

if (!class_exists("wpdreamsOnOff")) {
  class wpdreamsOnOff extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		echo "<label for='wpdreamstext_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<a class='wpdreamsonoff" . (($this->data == 1) ? " on" : " off") . "' id='wpdreamsonoff_" . self::$_instancenumber . "' name='" . $this->name . "_onoff'>" . (($this->data == 1) ? "ON" : "OFF") . "</a>";
  		echo "<input type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
  	}
  }
}


if (!class_exists("wpdreamsYesNo")) {
  class wpdreamsYesNo extends wpdreamsType {
  	function getType() {
  		parent::getType();
  		echo "<label for='wpdreamstext_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<a class='wpdreamsyesno" . (($this->data == 1) ? " yes" : " no") . "' id='wpdreamsyesno_" . self::$_instancenumber . "' name='" . $this->name . "_yesno'>" . (($this->data == 1) ? "YES" : "NO") . "</a>";
  		echo "<input type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
  	}
  }
}

if (!class_exists("wpdreamsColorPicker")) {
  class wpdreamsColorPicker extends wpdreamsType {
  	function getType() {
  		$this->name = $this->name . "_colorpicker";
  		parent::getType();
  		if ($this->label != "")
  			echo "<label for='wpdreamscolorpicker_" . self::$_instancenumber . "'>" . $this->label . "</label>";
  		echo "<input type='text' class='color'  name='" . $this->name . "' id='wpdreamscolorpicker_" . self::$_instancenumber . "' value='" . $this->data . "' />";
  		echo "<input type='button' class='wpdreamscolorpicker button-secondary' value='Select Color'>";
  		echo "<div class='' style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>";
  	}
  }
}

// Load external jQuery 1.7.2 - stable
if (!function_exists("load_external_jQuery")) {
  function load_external_jQuery() { // load external file
      global $concatenate_scripts;
      $concatenate_scripts = false;
      wp_deregister_script( 'jquery' ); // deregisters the default WordPress jQuery
      wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false, '3.5'); // register the external file
      wp_enqueue_script('jquery'); // enqueue the external file
  }
  add_action('admin_init', 'load_external_jQuery');
}

add_action('admin_print_styles', 'admin_stylesV01');
add_action('admin_enqueue_scripts', 'admin_scriptsV01');
add_action('wp_ajax_wpdreams-ajaxinput', "ajaxinputcallback");
if (!function_exists("ajaxinputcallback")) {
	function ajaxinputcallback() {
		$param = $_POST;
		echo call_user_func($_POST['callback'], $param);
		exit;
	}
}
function admin_scriptsV01() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('farbtastic', array(
		'wpdreams-jquerytooltip'
	));
	wp_register_script('wpdreams-others', plugin_dir_url(__FILE__) . '/types/others.js', array(
		'jquery',
		'thickbox',
		'farbtastic',
		'wpdreams-notytheme'
	));
	wp_enqueue_script('wpdreams-others');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_register_script('wpdreams-upload', plugin_dir_url(__FILE__) . '/types/upload.js', array(
		'jquery',
		'media-upload',
		'thickbox'
	));
	wp_enqueue_script('wpdreams-upload');
	wp_register_script('wpdreams-noty', plugin_dir_url(__FILE__) . '/types/js/noty/jquery.noty.js', array(
		'jquery'
	));
	wp_enqueue_script('wpdreams-noty');
	wp_register_script('wpdreams-notylayout', plugin_dir_url(__FILE__) . '/types/js/noty/layouts/top.js', array(
		'wpdreams-noty'
	));
	wp_enqueue_script('wpdreams-notylayout');
	wp_register_script('wpdreams-notytheme', plugin_dir_url(__FILE__) . '/types/js/noty/themes/default.js', array(
		'wpdreams-noty'
	));
	wp_enqueue_script('wpdreams-notytheme');
	wp_register_script('wpdreams-jquerytooltip', 'http://cdn.jquerytools.org/1.2.7/all/jquery.tools.min.js', array(
		'jquery'
	), "3.4.2");
	wp_enqueue_script('wpdreams-jquerytooltip');
}
function admin_stylesV01() {
	wp_enqueue_style('thickbox');
	wp_enqueue_style('farbtastic');
	wp_register_style('wpdreams-style', plugin_dir_url(__FILE__) . '/types/style.css');
	wp_enqueue_style('wpdreams-style');
}
/* Extra Functions */
if (!function_exists("isEmpty")) {
  function isEmpty($v) {
  	if (trim($v) != "")
  		return false;
  	else
  		return true;
  }
}
?>