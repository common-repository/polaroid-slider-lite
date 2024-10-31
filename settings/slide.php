<iframe frameborder='0' style='overflow:hidden;' width="710" height="160" src="http://wp-dreams.com/lite/index.php?ref=polaroidsliderlite" style="margin:10px;"></iframe>
<?php
  global $wpdb;
  $params = array();
  $_slide_default = array();
  $_slide_default['label'] = "<a href='Label Url here!'>I am a Label!</a>";
  $_slide_default['labelfont'] = "font-family:Anonymous Pro;font-weight:bold;color:#c9c9c9;font-size:19px;";
  $_slide_default['image100x'] = 0;
  $_slide_default['image100y'] = 0;
  $_slide_default['slidecolor'] = "#ffffff";
  $_slide_default['labelposition'] = 'duration:500;direction:bottom-left;position:60||60;';
  $_slide_default['labelwidth'] = 0;
  $_slide_default['labelborderwidth'] = 1;
  $_slide_default['labelbordercolor'] = "#222222";
  $_slide_default['labelbgcolor'] = "#000000";
  $_slide_default['labelbgopacity'] = 0.5;
  $_slide_default['parent'] = 0;
  $_slide_default['subslidealignment'] = "
    Horizontal|horizontal;
    Vertical|vertical||
    horizontal
  ";
  $_slide_default['subslidenavbar'] = 1;
  $_slide_default['hidesubslidenavbar'] = 1;
  $_slide_default['subslidenavbaralignment'] = "
    Right|right;
    Left|left;
    Top|top;
    Bottom|bottom||
    right
  ";
  $_slide_default['subslidenavbarcolor'] = "#000000";
  $_slide_default['subslidenavbaropacity'] = 0.8;
  $_slide_default['subslidenavbarborderradius'] = 0;
  $_slide_default['subslidenavbaractivecolor'] =  "#262626";
  $_slide_default['subslidenavbarinactivecolor'] =  "#5e5e5e";
  $_slide_default['subslidenavbaractivebordercolor'] =  "#262626";
  $_slide_default['subslidenavbarinactivebordercolor'] =  "#5e5e5e";  
                                                                       
  $current_slider = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."polaroid_slides_lite", ARRAY_A);
  $current_slider['data'] = json_decode($current_slider['data'], true);
  
?>
  <div class="wpdreams-slider">
    <form>
    <fieldset> 
    <legend>Slider Shortcode</legend>
     Copy and paste this code into a post or page: <input type='text' value='[wpdreams_polaroid_lite]' readonly/>
    </fieldset> 
    </form>
  </div>
  
  <div class="wpdreams-slider">
     <form name="add-slider" action="" method="POST">
       <fieldset> 
       <legend>Add a new Slide</legend>
       
           <?php
            $new_slide = new wpdreamsText("addslide", "Slide name:", "",array( array("func"=>"isEmpty", "op"=>"eq", "val"=>false) ), "Please enter a valid slide name!");
           ?>
       <input name="submit" type="submit" value="Add" /> 
          <?php
            if (isset($_POST['addslide']) && !$new_slide->getError()) {
              $max_order = $wpdb->get_var("SELECT MAX(ordering) FROM ".$wpdb->prefix."polaroid_slides_lite");
              $max_order = (($max_order!=null)?$max_order:0);
              $wpdb->query("INSERT INTO ".$wpdb->prefix."polaroid_slides_lite
                (name, data, slider_id, ordering) VALUES
                ('".$_POST['addslide']."', '".mysql_real_escape_string(json_encode($_slide_default))."', 0, ".$max_order.")
              ");
              $_slides = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."polaroid_slides_lite ORDER BY ordering ASC", ARRAY_A);
              $i = 1;
              foreach ($_slides as $_slide) {
                $wpdb->query("UPDATE  ".$wpdb->prefix."polaroid_slides SET ordering=".$i." WHERE id=".$_slide['id']);
                $i++;
              }
              echo "<div class='successMsg'>Slide Successfuly added!</div>";
            }          
          ?> 
        </fieldset>  
    </form> 
  </div>
  <div class="sortable"> 
  <?php
  
  if (isset($_POST['delete'])) {
    $wpdb->query("DELETE FROM ".$wpdb->prefix."polaroid_slides_lite WHERE id=".$_POST['did']);
    $_slides = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."polaroid_slides_lite ORDER BY ordering ASC", ARRAY_A);
    $i = 1;
    foreach ($_slides as $_slide) {
      $wpdb->query("UPDATE  ".$wpdb->prefix."polaroid_slides_lite SET ordering=".$i." WHERE id=".$_slide['id']);
      $i++;
    }
  }
  
  $slides = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."polaroid_slides_lite ORDER BY ordering DESC", ARRAY_A);
  if (is_array($slides) && count($slides)>0) {

  foreach ($slides as $slide) {
      $slide['data'] = json_decode($slide['data'], true);
    ?>
      <div class="wpdreams-slider moveable" slideid="<?php echo $slide['id']; ?>">
        <div class="slider-info">
          <img title="Click on this icon for slide settings!" src="<?php echo plugins_url('/types/icons/settings.png', __FILE__) ?>" class="settings" />
          <img title="Click here if you want to delete this slide!" src="<?php echo plugins_url('/types/icons/delete.png', __FILE__) ?>" class="delete" />
          <form name="polaroid_slider_del_<?php echo $slide['id']; ?>" action="" style="display:none;" method="POST">
            <?php 
            new wpdreamsHidden("delete", "delete", "delete"); 
            new wpdreamsHidden("did", "did", $slide['id']);
            ?>        
          </form>
          
          <span><?php
             echo $slide['name'];
          ?>
          </span>
        </div>
        <hr />
        <form name="polaroid_slider_<?php echo $slide['id']; ?>" action="" method="POST"> 
          <?php
          /*$params[$o->getName()] = $o->getData();
          $o = new wpdreamsOnOff("onofftest", "OnoffTest", $slider['data']['onofftest']);
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsColorPicker("ColorPickertest", "ColorPickerTest", $slider['data']['colorpicker']);
          $params[$o->getName()] = $o->getData();
          $o = new wpdreamsUpload("Uploadimagetest", "Uploadimagetest", $slider['data']['uploadimagetest']);
          $params[$o->getName()] = $o->getData(); */
          ?>
          <fieldset>
          <legend>Slide Content Options</legend>
            <div class="item"><?php
            $o = new wpdreamsUpload("image_".$slide['id'], "Slide Image", $slide['data']['image']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsTextarea("html_".$slide['id'], "... or HTML code", $slide['data']['html']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("slidecolor_".$slide['id'],"Slide background color", $slide['data']['slidecolor']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsYesNo("image100x_".$slide['id'], "100% image width", $slide['data']['image100x']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsYesNo("image100y_".$slide['id'], "100% image height", $slide['data']['image100y']);
            $params[$o->getName()] = $o->getData();
            ?></div>
          </fieldset>
          <fieldset>
          <legend>Slide Label Options</legend>          
            <div class="item"><?php       
            $o = new wpdreamsFont("labelfont_".$slide['id'], "Default Label font", $slide['data']['labelfont']);
            $params[$o->getName()] = $o->getData();
            $params["script-".$o->getName()] = $o->getScript();
            ?>
            </div>
            <div class="item"><?php
            $o = new wpdreamsTextarea("label_".$slide['id'], "Slidel Label HTML", $slide['data']['label']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsLabelPosition("labelposition_".$slide['id'], "Label Position", 400, 300, $slide['data']['labelposition']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsText("labelwidth_".$slide['id'], "Slide Label Width(px)", $slide['data']['labelwidth']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("labelbgcolor_".$slide['id'],"Label background color", $slide['data']['labelbgcolor']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsText("labelborderwidth_".$slide['id'], "Slide Label border Width(px)", $slide['data']['labelborderwidth']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsColorPicker("labelbordercolor_".$slide['id'],"Label border color", $slide['data']['labelbordercolor']);
            $params[$o->getName()] = $o->getData();
            ?></div>
            <div class="item"><?php
            $o = new wpdreamsText("labelbgopacity_".$slide['id'], "Label Background Opacity", $slide['data']['labelbgopacity']);
            $params[$o->getName()] = $o->getData();            
            ?></div>          
          </fieldset>
          <fieldset>
          <?php 
            ob_start();
          ?>
                       
          <?php 
            $_temp_out = ob_get_contents();
            ob_end_clean();
          ?>          
            <div class="item"><?php
            
          if (isset($_POST['submit_'.$slide['id']]) && (wpdreamsType::getErrorNum())==0) {
            /* update data */
            foreach ($params as $k=>$v) {
              $_tmp = explode("_".$slide['id'], $k);
              $params[$_tmp[0]] = $v;
              unset($params[$k]);
            }
            $data = mysql_real_escape_string(json_encode($params));
            $wpdb->query("
              UPDATE ".$wpdb->prefix."polaroid_slides_lite
              SET data = '".$data."'
              WHERE id = ".$slide['id']."
            ");
            echo "<div class='successMsg'>Slide settings saved!</div>";
          }            
          ?></div>

          <?php echo $_temp_out; ?>
          <div class="item">
            <input name="submit_<?php echo $slide['id']; ?>" type="submit" value="Save this slide!" />
          </div>             
          </fieldset>
           <script>
            jQuery(document).ready(function() { 
              jQuery("select.wpdreams_parent").change(function(){ 
                 if (jQuery(this).val()!=0) {                  
                   jQuery('.hidethis', this.parentNode.parentNode).disable();
                 } else {
                   jQuery('.hidethis', this.parentNode.parentNode).enable();
                 }
              });
              jQuery("select.wpdreams_parent").change();
            });
           </script>
        </form>
      </div>
    <?php      
      if (isset($_POST['submit_'.$slide['id']]) && (wpdreamsType::getErrorNum())==0) {
        echo "<div class='successMsg'>Slider settings saved!</div>";
      } 
  }
  } 
  

?>
</div>