<?php
  add_shortcode( 'wpdreams_polaroid_lite', 'polaroid_slider_lite');

  function polaroid_slider_lite( $atts ) {

    global $wpdb;
    global $wpdreams_polaroids;
    $slides = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."polaroid_slides_lite ORDER BY ordering ASC", ARRAY_A);

    if (!is_array($slides) || count($slides)<2) {
      echo "You need at least 2 slides!";
      return;
    }
    ?>
    
    <script>
    jQuery(document).ready(function() {
       jQuery("#pslider").polaroidslider({
          "path": "<?php echo plugins_url('/polaroid-slider/'); ?>",
          "sliderDiv": "pslider",
          "sliderWidth": 450,
          "sliderHeight": 350,
          "imageWidth": 400,
          "imageHeight": 300,
          "direction": 'top-left',
          "indirection": 'top-left',
          "easing": 'linear',
          "roteasing": 'linear',
          "duration": 300,
          "rotduration": 300,
          "bounce": 1,
          "showArrows": 1,
      		"autoplay": 0,
      		"autoplayrestart": 0,
      		"fullscreen": 1,
      		"autoplayinterval": 6000,
          "kenburns": 1,
          "angle": 5     
       });
    });
       
    </script> 
    <div style="width:auto;height:auto;">
      <div style="width:0;height:0;background:#000;position:fixed;opacity:0;" class="bgdiv"></div>
      <div id="pslider" class="pslider" style="margin:10% auto;">
        <div class="fullscreen" style="display:none;"></div>
        <div class="aleft" style="margin-left:-20px;margin-top:130px;background:url('<?php echo plugins_url('/polaroid-slider/'); ?>/img/arrow-left.png');"></div>
        <div class="aright" style="margin-right:-20px;margin-top:130px;background:url('<?php echo plugins_url('/polaroid-slider/'); ?>/img/arrow-right.png');"></div> 
        
        <?php
        $i = -1;
        $_order = 0;
        foreach ($slides as $slide) {
          $slide['data'] = json_decode($slide['data'], true);
          $i++;
          ?>
               
          <div class="pitem" origorder="<?php echo $i; ?>">
            <div class="slide" id='pslide_<?php echo $slide['id']; ?>' style="position:relative;overflow:hidden;background:<?php echo $slide['data']["slidecolor"]; ?>">
            <div class="drg" alignment="<?php echo ($slide['data']['selected-subslidealignment']); ?>" style="<?php if ($slide['data']['selected-subslidealignment']=='vertical') echo "width:100000px;"; ?>overflow:hidden;">
              <div class="bounce" style="width:20px;height: 40px;position:absolute;top:-7px;left:-7px;z-index:1000;"></div>
              <div class="subslide" order='<?php echo $_order; ?>' style="<?php if ($slide['data']['selected-subslidealignment']=='vertical') echo "float:left;"; ?>overflow:hidden;width:400px;height:300px;">
              <?php echo $slide['data']["script-labelfont"]; ?>
                <style>
                        #pslider<?php echo $id; ?> #pcaption_<?php echo $slide['id']; ?> {
                          <?php echo $slide['data']["labelfont"] ?>
                          margin: 0;
                          padding: 0;
                        }
                        #pslider<?php echo $id; ?> #pcaption_<?php echo $slide['id']; ?> a {
                         <?php echo $slide['data']["labelfont"] ?>
                        }
                        #pslider<?php echo $id; ?> #pcaption_<?php echo $slide['id']; ?> {
                          width: <?php echo (($slide['data']["labelwidth"]==0)?10000:$slide['data']["labelwidth"]); ?>px;
                          background: rgb(<?php echo hex2rgb($slide['data']["labelbgcolor"]); ?>) transparent;
                          background: rgba(<?php echo hex2rgb($slide['data']["labelbgcolor"]); ?>, <?php echo $slide['data']["labelbgopacity"]; ?>);
                          <?php
                            $filterbg = dechex(255*$slide['data']["labelbgopacity"]);
                            $color = substr($slide['data']["labelbgcolor"], 1);
                          ?>
                          
                        	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#<?php echo $filterbg.$color; ?>000000, endColorstr=#<?php echo $filterbg.$color; ?>);
                          -ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#<?php echo $filterbg.$color; ?>000000, endColorstr=#<?php echo $filterbg.$color; ?>)";
                          border: <?php echo $slide['data']["labelborderwidth"] ?> solid <?php echo $slide['data']["labelbordercolor"] ?>;
                        }        
                </style>              
                <?php if($slide['data']["label"]!="") { ?>
                <div class="pcaption" id='pcaption_<?php echo $slide['id']; ?>' labeldata="<?php echo $slide['data']["labelposition"]; ?>">
                      <?php echo stripcslashes($slide['data']["label"]); ?>
                </div>                
                <?php } ?>
                  <?php if ($slide['data']['html']!="") { 
                    echo stripcslashes($slide['data']['html']);
                  } else { 
                  ?>
                    <img src='<?php echo $slide['data']["image"] ?>' style="<?php echo (($slide['data']["image100x"])?"width:100%;":""); ?><?php echo (($slide['data']["image100y"])?"height:100%;":""); ?>">
                  <?php } ?>
              </div>
              <?php $_order++; ?>
            </div>                                              
          </div>
        </div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php    
  }  
  
?>