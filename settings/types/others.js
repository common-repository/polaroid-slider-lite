function wpdreamsOthers (args)  {
  var _self = this;  
  this.constructor = function() {
    _self.init = true; 
    jQuery('.wpdreamscolorpicker').prev('input').bind("change", this.colorPickerInit);
    jQuery('.wpdreamscolorpicker').prev('input').trigger("change");
    jQuery('.wpdreams-slider.moveable .settings').trigger("click");
    /*jQuery("#wpdreams img[title].infoimage").tooltip({
        effect: 'slide', 
        delay: 500,
        events: {
          def:  'click, mouseout',
          img: 'click, blur',
          checkbox: 'mouseover click, mouseout',
          date: 'click, blur'
        }
    });*/
    jQuery( ".sortable" ).sortable({ 
      items: 'div.wpdreams-slider.moveable', 
      dropOnEmpty: false,
      delay: 200, 
      stop: function(event, ui) { 
        var nodes = jQuery(".sortable div.wpdreams-slider.moveable");
        var nodesArr = Array();
        for(var j=0,i=nodes.length;i>=0;j++,i--) {
          nodesArr[j] = jQuery(nodes[i]).attr("slideid");
        }
      	var data = {
      	  action: 'reorder_slides',
      		nodes: nodesArr,
      		ordering: i
      	};

        jQuery(nodes).fadeTo(400, 0.4); 
      	jQuery.post(ajaxurl, data, function(response) {
            jQuery(nodes).fadeTo(400, 1);   		
      	});
      }
    });
    _self.init = false;
  },
  
  jQuery('.wpdreamsselect').change(function() {
     _self.hidden = jQuery(this).next();
     var val = jQuery(_self.hidden).val().match(/(.*[\S\s]*?)\|\|(.*)/);
     var options = val[1];
     var selected = val[2];
     jQuery(_self.hidden).val(options+"||"+jQuery(this).val());
  });
  
  jQuery('.wpdreamsonoff').click(function() {
     var hidden = jQuery(this).next();
     var val = jQuery(hidden).val();
     if (val==1) {
      val = 0;
      jQuery(this).removeClass("on"); 
      jQuery(this).addClass("off");
      jQuery(this).html("OFF");
     } else {
      val = 1;
      jQuery(this).removeClass("off"); 
      jQuery(this).addClass("on");
      jQuery(this).html("ON");
     }
     jQuery(hidden).val(val);
  });
  
  jQuery('.wpdreamsyesno').click(function() {
     var hidden = jQuery(this).next();
     var val = jQuery(hidden).val();
     if (val==1) {
      val = 0;
      jQuery(this).removeClass("yes"); 
      jQuery(this).addClass("no");
      jQuery(this).html("NO");
     } else {
      val = 1;
      jQuery(this).removeClass("no"); 
      jQuery(this).addClass("yes");
      jQuery(this).html("YES");
     }
     jQuery(hidden).val(val);
  });
  
  this.colorPickerInit = function(event) {
      colorPicker = jQuery(this).next().next('div');
      input = this;
      jQuery(colorPicker).farbtastic(input);
  };
  
  jQuery('.wpdreams-slider.moveable .settings').click(function(){
     var moveable = this.parentNode.parentNode;
     if (jQuery('.errorMsg', moveable).length) return;
     if (_self.sliderHeight==null) {
       _self.sliderHeight = jQuery(moveable).height();
       
     }  
     if (jQuery(moveable).height()<27) {
      if (_self.init) {
        jQuery(moveable).css({
          height: _self.sliderHeight
        });      
      } else {
        jQuery(moveable).animate({
          height: _self.sliderHeight
        }, 500, function() {
          
        });  
      }
     } else {
      if (_self.init) {
        jQuery(moveable).css({
          height: "26px"
        });      
      } else {
        jQuery(moveable).animate({
          height: "26px"
        }, 500, function() {
          
        });  
      }
     }
  });
  
  jQuery('.successMsg').each(function() {
     jQuery(this).delay(4000).fadeOut();
  }); 

  jQuery('img.delete').click(function() {
     var del = confirm("Do yo really want to delete this item?");
     if (del) {
      jQuery(this).next().submit();
     }
  });
  
  jQuery('img.ordering').click(function() {
    jQuery(this).next().submit();
  });
                                        
  jQuery('.wpdreamscolorpicker').click( function(e) {
      colorPicker = jQuery(this).next('div');
      input = jQuery(this).prev('input');
      jQuery(colorPicker).farbtastic(input);
      colorPicker.show();
      var inputPos = input.position();
      colorPicker.css("left",inputPos.left);
      e.preventDefault();
      jQuery(document).mousedown( function() {
          jQuery(colorPicker).hide();
          jQuery(input).trigger('change');
      });
      
  });
  
  jQuery('form.wpdreams-ajaxinput').each(function(){
      var _tmpmargin = jQuery(this).css("marginLeft");
      jQuery("img", this).click(function() {
        var src = jQuery(this).attr('src');
        var img = src.match(/(.*)\/.*$/)[1];
        if (jQuery(this).attr('opened')=="0") {
          jQuery(this).attr('opened', '1');
          jQuery(this).attr('src', img+'/arrow-left.png');
          (jQuery(this).parent()).animate({marginLeft:0});
        } else {
          jQuery(this).attr('opened', '0');
          jQuery(this).attr('src', img+'/arrow-right.png');
          (jQuery(this).parent()).animate({marginLeft:_tmpmargin});
        }
      });      
      jQuery("input[name=url]", this).click(function() {
        if (jQuery(this).val()=="Enter the feed url here...")
           jQuery(this).val("");
      });
      jQuery("input[name=url]", this).blur(function() {
        if (jQuery(this).val()=="")
           jQuery(this).val("Enter the feed url here...");
      });
      jQuery("input[type=button]", this).bind("click",function(e){
        e.preventDefault();
      	var data = {
      	  action: 'wpdreams-ajaxinput',
      	  url: jQuery("input[name=url]", jQuery(this).parent()).val(),
      	  uid: jQuery("input[name=uid]", jQuery(this).parent()).val(),
      	  callback: jQuery("input[name=callback]", jQuery(this).parent()).val(),
      	  itemsnum: jQuery("select[name=itemsnum]", jQuery(this).parent()).val()
      	};
      	var _tmpnode = jQuery("input[type=button]", jQuery(this).parent());
      	var _tmpval = jQuery("input[type=button]", jQuery(this).parent()).val();
      	_tmpnode.val("Wait..");
      	_tmpnode.css("opacity", 0.8);
      	_tmpnode.attr("disabled", "disabled");
      	jQuery.post(
          ajaxurl, 
          data, 
          function(response) {
            if (response.status==0) {
              noty({
                text: response.msg,
                type: 'error',
                timeout: '2000'
              });
            } else {
              noty({
                text: response.msg,
                type: 'success',
                timeout: '2000'
              });
            }
      	   _tmpnode.css("opacity", 1);
      	   _tmpnode.removeAttr("disabled");
           _tmpnode.val(_tmpval);		
      	}, 'json');
      });
  });
  
   
  
  this.constructor();
}(jQuery);

jQuery(document).ready(function() { 
  var x = new wpdreamsOthers();
});

(function( $ ){
  $.fn.disable = function() {
      return this.each(function() {
          if ($('.hider', this)[0]==null) {
            $(this).css('position', 'relative');
            var w = $(this).width();
            var h = $(this).height();
            this.$innerDiv = $(this)
                .append($('<div></div>')
                    .css({
                      position: 'absolute',
                      opacity: 0.7,
                      top: 0,
                      left: 0,
                      background: "#FFFFFF",
                      width: w,
                      height: h
                    })
                    .addClass('hider')
                )
            ;
          } else {
            $('.hider', this).css({
              display: 'block'
            });
          }
      });
  }
  
  $.fn.enable = function() {
      return this.each(function() {
          if ($('.hider', this)[0]!=null) {
            $('.hider', this).css({
              display: "none"
            });
          }
      });
  }
})( jQuery );
