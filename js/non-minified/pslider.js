(function( $ ){  
  var methods = {
    init : function( options ) {
    var _self = new Object();
    var o;
      _self.animateLabel = function(node) {
        var data = $(node).attr("labeldata");
        if (data==null) return;
        var direction = data.match(/direction:(.*?);/)[1];
        var aduration = data.match(/duration:(.*?);/)[1];
        var y = data.match(/position:(.*?)\|\|/)[1]-($(node).outerHeight(true)/2);
        var x = data.match(/\|\|(.*?);/)[1]-($(node).outerWidth(true)/2);

        t = 1;
        l = 1;
        if (direction == "top") {
          l = 0;
          t = 1;
        } else if (direction == "left") {
          t = 0;
          l = -2;
        } else if (direction == "bottom") {
          l = 0;
          t = -1;
        } else if (direction == "right") {
          t = 0;
          l = 1;
        } else if (direction == "top-left") {
          l = -2;
          t = 1;
        } else if (direction == "bottom-right") {
          t = -1;
          l = 1
        } else if (direction =="bottom-left") {
          t = -1;
          l = -2;
        }
        $(node).css({
          "top":(-(t*1000))+y,
          "left": (l*1000)+x,
          "opacity": 0
        });
        $(node)
        .animate({
            top: y,
            left: x,
            opacity: 1
          },
          {
            duration: parseInt(aduration), 
            easing: 'linear'
          }
        );        
      }
      
      _self.resetLabel = function(node) {
        $(node).css({
          "top":-300,
          "left": -300,
          "opacity": 0
        });      
      }
    
      _self.resetRotations = function(items) {
        var j = 1;
        $(items).each(function(){
          var _j = j;
          if (parseInt($(this).attr("order"))!=1 && $(".slide", this).html().match(/.*<iframe(.*)/i)!=null && $(".slide", this).html().match(/<div blackout(.*?)/i)==null) {
            $(".subslide", this).each(function(){
              $(this).html("<div blackout=1 style='width:100%;height:100%;background: #000;'></div>");
            });
          }
          var i = (parseInt($(this).attr("order"))-1)*o.angle;
          _self.resetLabel($('.pcaption', this));
          $(this)
          .delay(j*1)
          .animate({
              r: i
            },
            {
              step: function(now, fx) {
                $(this).rotTo(now);
              },
              complete: function() {                
                if ($(this).getRotationDegrees()==0 && $(".slide", this).html().match(/.*<div blackout(.*)/i)!=null) {             
                  $(".subslide", this).each(function(){
                    $(this).html(_self.innerh[$(this).attr('order')]);
                    videoFix($('iframe', this));
                  });
                  $(this).css("transform", "");
                  $(this).css("-moz-transform", "");
                  $(this).css("-ms-transform", "");
                  $(this).css("-webkit-transform", "");
                  $(this).css("-o-transform", "");
                }
                if ($(this).getRotationDegrees()==0) {
                  _self.animateLabel($('.pcaption', this));
                }              
              },
              duration: o.rotduration, 
              easing: o.roteasing
            }
          );        
          i = i-o.angle;
          j++;
        });  
      }
  
      _self.initRotations = function(items) {
        var i = (items.length-1)*o.angle;
        var j = 1;
        $(items).each(function(){
          if (parseInt($(this).attr("order"))!=1 && $(".slide", this).html().match(/.*<iframe(.*)/i)!=null && $(".slide", this).html().match(/<div blackout(.*?)/i)==null) {
            $(".subslide", this).each(function(){
              $(this).html("<div blackout=1 style='width:100%;height:100%;background: #000;'></div>");
            });
          }    
          $(this)
          .delay(j*400)
          .animate({
            left: "50%",
            marginTop: "0"
          },
          {
            duration: 500, 
            easing: 'linear'
          }
          )
    
          .delay(400)
          .animate({
              r: i
            },
            {
              step: function(now, fx) {
                if (now!=0) $(this).rotTo(now);
              }, 
              complete: function() {
                if ($(this).getRotationDegrees()==0) {
                  _self.animateLabel($('.pcaption', this));
                  $(".subslide", this).each(function(){
                    videoFix($('iframe', this));
                  });
                }                
              },      
              duration: o.rotduration, 
              easing: o.roteasing
            }
          );        
          i = i-o.angle;
          
        j++;
        });
      };
      
      _self.resetSubslides = function() {
        var drgs = $(".drg", o.slider);
        drgs.each(function(){
          var pos = $(this).position();
          if (pos.top!=0 || pos.left!=0) {
            $(this).animate({
              top:0,
              left:0
            }); 
            $(".subdots .subdot", o.slider).removeClass("active");
            $(".subdots", o.slider).each(function(){
              $(this.children[0]).addClass("active");
            });            
          }       
        });      
      }      
   
      _self.sliderMarginTop = $(this).css("marginTop"); 
      _self.fullscreen = false;
      _self.moving = false;
      _self.innerh = new Array();
      _self.o = options;
      _self.sliding = false;
      o = options;
      o.slider = this;
      o.items = $(".pitem", this);
      o.slides = $(".slide", this);
      o.subslides = $(".subslide", this);
      o.subslides.each(function(){
        _self.innerh[$(this).attr('order')] = $(this).html();
      });
      
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        $('.subdots', this).removeClass('dhidden');      
      }

      o.parentDiv = this;
      var j = 1000;
      var i = 1;
      
      if (o.kenburns==1) $("img", o.slides).addClass("kenburns");
        t = 1;
        l = 1;
        if (o.direction == "top") {
          l = 0;
          t = 1;
        } else if (o.indirection == "left") {
          t = 0;
          l = -2;
        } else if (o.indirection == "bottom") {
          l = 0;
          t = -1;
        } else if (o.indirection == "right") {
          t = 0;
          l = 1;
        } else if (o.indirection == "top-left") {
          l = -2;
          t = 1;
        } else if (o.indirection == "bottom-right") {
          t = -1;
          l = 1
        } else if (o.indirection =="bottom-left") {
          t = -1;
          l = -2;
        }
      
      $(".drg", o.slider).each(function(){
        if ($(".subslide", this).length<=1) return;
        var alignment = $(this).attr("alignment");
        var pos = $(this).position();
        var count = $(".subslide", this).length;
        var width = $(".subslide", this).width();
        var height = $(".subslide", this).height();
        if (alignment=="horizontal") {
          var distance = height;
          width = 0;
          var ax = "y";
          var position = pos.top;
        } else {
          var distance = width;
          height = 0;        
          var ax = "x";
          var position = pos.left;
        }
        $this = this;
        $(this).draggable({
          axis: ax,              
          start: function (event, ui) {
              pos = $($this).position();
              if (alignment=="horizontal") {
                position = ui.originalPosition.top;
                height = $(".subslide", this).height();
                distance = height;
              } else {
                position = ui.originalPosition.left;
                width = $(".subslide", this).width();
                distance = width;
              } 
              $('.pcaption', $('.subslide', event.currentTarget)).css({
                top: "-3000px",
                left: '-3000px'
              });
          },
          stop: function(event, ui) {                 
            var movement = ((alignment=="horizontal")?(ui.originalPosition.top - ui.position.top):(ui.originalPosition.left - ui.position.left));
            if ((alignment=="horizontal")) {
              var args1 = {"top": ui.originalPosition.top-sgn(movement)*height};
              var args2 = {"top": ui.originalPosition.top};
            } else {
              var args1 = {"left": ui.originalPosition.left-sgn(movement)*width};
              var args2 = {"left": ui.originalPosition.left};
            }              
            if (Math.abs(movement)>(distance*0.2) 
                && (parseInt(position)-sgn(movement)*distance)<=0.9
                && (parseInt(position)-sgn(movement)*distance)>=(-distance*(count-1))) {
              currentSub = $(".subdots .subdot.active", this.parentNode).attr("subslidenum")-sgn(movement);
              _self.sliding = true;
              $(this).animate(args1,
              {
                  duration: 200, 
                  easing: 'linear',
                  complete: function() {
                    _self.animateLabel($('.pcaption', $('.subslide', this)[count-currentSub-1]));
                  }
              });    
              $(".subdots .subdot", this.parentNode).removeClass("active");
              $(".subdots .subdot", this.parentNode).each(function(){
                if ($(this).attr("subslidenum")==currentSub) {
                  $(this).addClass("active");
                }
              });  
            } else {
              currentSub = $(".subdots .subdot.active", this.parentNode).attr("subslidenum");
              $(this).animate(args2,
              {
                  duration: 200, 
                  easing: 'linear',
                  complete: function() {
                    _self.sliding = false;
                    _self.animateLabel($('.pcaption', $('.subslide', this)[count-currentSub-1]));
                  }
              });                             
            }
          } 
        });      
      });
      
      $(".subdots", o.slider).click(function(e){
        e.preventDefault();
        e.stopPropagation();
      });
      $(".subdots .subdot", o.slider).click(function(e){
         e.preventDefault();
         e.stopPropagation();
         if ($(this).hasClass("active")==0) {
            var num = (this.parentNode.children.length)-$(this).attr("subslidenum")-1; 
            var slideDrag = $(".drg", this.parentNode.parentNode);
            var alignment = $(slideDrag).attr('alignment');
            var width = $('.subslide', slideDrag).width();
            var height = $('.subslide', slideDrag).height();
            if (alignment == "horizontal") {
              var args = {"top": -num*height};
            } else {
              var args = {"left": -num*width};
            }
            $('.pcaption', this.parentNode.parentNode).css({
                top: "-3000px",
                left: '-3000px'
            });
            $(slideDrag).animate(args,
            {
                duration: 200, 
                easing: 'linear',
                complete: function() {
                  _self.animateLabel($('.pcaption', this.parentNode.parentNode)[num]);
                }
            });
            $(".subdot", this.parentNode).removeClass("active");
            $(this).addClass("active");                       
         }
      })

      $($(o.items).get().reverse()).each(function(){
         if (o.indirection=="random") { 
            do { 
              t = Math.floor((Math.random()*3)-1);
              l = Math.floor((Math.random()*4)-2); 
            } while (t==0 && l==0);
         }
         $(this)
         .attr("order", i)
         .css('visibility', 'visible')
         .css('z-index', j)
         .css('position', "absolute")
         .css('left', (500)*l+"%")
         .css('marginTop', (500)*(-t)+"%")
         .css('marginLeft', (-(o.imageWidth/2+9 ) + "px"));
         j--;            
         i++;   
      });
      $(o.slides).each(function(){        
         $(this)
         .css('width', o.imageWidth)
         .css('height',  o.imageHeight);         
      });
      $(this).css("width", o.sliderWidth);
      $(this).css("height", o.sliderHeight);

      //$("#outer").css("width", "100%");
      //$(this).parent().css("width", "100%");
      
      $(".aleft", this).click(function(){
          $(".pitem[order='1']", o.parentDiv).trigger("click");
      });
      $(".aright", this).click(function(){
          $(".pitem[order='"+o.items.length+"']", o.parentDiv).trigger("click");
      });
       
      
      _self.autoplayStart = function() {
         _self.autoplayStop();
         _self.timer = setInterval(function() {
           $(".pitem[order='1']", o.parentDiv).trigger("click");
         },
         o.autoplayinterval);
      };
      
      _self.autoplayStop = function() {
        if(_self.timer)
          clearInterval(_self.timer);
      };
      
      if(o.autoplay){
        _self.autoplayStart();
  	    $(o.parentDiv).bind("mouseover", function(){
          _self.autoplayStop();
        });
        if (o.autoplayrestart==1) {
          $(o.parentDiv).bind("mouseleave", function(){
            _self.autoplayStart();
          });
        }
      }

      $(o.items).bind("DOMMouseScroll onmousewheel", function(e) {
          e.preventDefault();
          if (e.originalEvent.detail<0 || e.originalEvent.wheelDelta<0)
            $(".pitem[order='1']", o.slider).trigger("click");
          else
            $(".pitem[order='"+o.items.length+"']", o.parentDiv).trigger("click");
      });


      $(window).resize(function(){
        if (_self.fullscreen) return;
        _self.resetSubslides();
        var w = ($(window).width()>(($(o.parentDiv).parent()).width())?(($(o.parentDiv).parent()).width()):$(window).width());
        var slider = o.parentDiv;
        if (w<$(slider).width() || (w > $(slider).width() && $(slider).width()<o.sliderWidth)) {
          if (w>o.sliderWidth ) {
            w = o.sliderWidth;
          }
          var ratio = (w/o.sliderWidth);
          
          var h = ratio*o.sliderHeight;
          if (h>o.sliderHeight)
            h = o.sliderHeight;
          $(slider).css("width", w);
          $(slider).css("height", h);
          if ((o.imageWidth*ratio)<=o.imageWidth) {
            $(o.slides).css('width', o.imageWidth*ratio);
            $(o.subslides).css('width', o.imageWidth*ratio);
          } else {
            $(o.slides).css('width', o.imageWidth);
            $(o.subslides).css('width', o.imageWidth);
          }
          if ((o.imageHeight*ratio)<=o.imageHeight) {
            $(o.slides).css("height", o.imageHeight*ratio);
            $(o.subslides).css("height", o.imageHeight*ratio);
          } else {
            $(o.slides).css("height", o.imageHeight);
            $(o.subslides).css("height", o.imageHeight);
          }
          $(o.items).css('marginLeft', (-(o.imageWidth*ratio/2+9) + "px"));
        } 
      }); 
      var i = 1;

      $(o.items).bind('click', function(){
         if (_self.moving) return;
         _self.moving = true;
        t = 1;
        l = 1;
        if (o.direction == "top") {
          l = 0;
          t = 1;
        } else if (o.direction == "left") {
          t = 0;
          l = -2;
        } else if (o.direction == "bottom") {
          l = 0;
          t = -1;
        } else if (o.direction == "right") {
          t = 0;
          l = 1;
        } else if (o.direction == "top-left") {
          l = -2;
          t = 1;
        } else if (o.direction == "bottom-right") {
          t = -1;
          l = 1
        } else if (o.direction =="bottom-left") {
          t = -1;
          l = -2;
        } else  {
          do { 
            t = Math.floor((Math.random()*3)-1);
            l = Math.floor((Math.random()*4)-2); 
          } while (t==0 && l==0); 
        }         
         var curve = o.sliderWidth;
         _self.templ = parseInt($(this).css("marginLeft"));
         _self.tempt = parseInt($(this).css("marginTop")); 
         $(this).animate({
              marginLeft: (_self.templ+curve*l),
              marginTop: (_self.tempt+curve*(-t))
            },
            {
              duration: 400, 
              easing: o.easing,
              step: function() {
                
              },
              complete: function() {
               var order = $(this).attr("order");
               if (order==1) {
                $(o.items).each(function(){
                   if ($(this).attr('order')!=order) {
                      $(this).attr('order', $(this).attr('order')-1);
                      $(this).css('zIndex', (parseInt($(this).css('zIndex'))+1));
                      
                   }
                });
                $(this).attr("order", o.items.length);
                $(this).css('zIndex',(parseInt($(this).css('zIndex'))-o.items.length+1));

               } else {
                 $(o.items).each(function(){
                   if ($(this).attr('order')<order) { 
                     $(this).attr('order', parseInt($(this).attr('order'))+1);
                     $(this).css('zIndex', (parseInt($(this).css('zIndex'))-1));
                   }
                  });
                  $(this).attr("order", 1);
                  $(this).css('zIndex',1000); 
                  
               }
                //if ($(".slide", this).html().match(/.*<img(.*)src.*/i)==null) {
                //  $(".slide", this).html("<div blackout=1 style='width:100%;height:100%;background: #000;'></div>");
                //} 
              
               _self.resetRotations(o.items);     
              }
                           
            }
          ).animate({
              marginLeft: _self.templ,
              marginTop: _self.tempt  
            },
            {
              duration: 300, 
              easing: o.easing,
              complete: function() {
                _self.moving = false;
              }
            }
          );
         $("#"+o.sliderDiv+" .dot").removeClass("active");
         $("#"+o.sliderDiv+" .dot[slidenum="+(parseInt($(this).attr("origorder")))+"]").addClass("active");                  
      });
      
      if (o.bounce==1) {
        var _top = parseInt($(o.items).css("top"));
        $(".bounce", o.items).bind("mouseenter", function(e){
          if ($(this).parent().parent().parent().attr('order')=='1') return;
            $($(this).parent().parent().parent()).animate({
                top: (_top-25)+"px"
              },
              {
                duration: 100, 
                easing: 'linear'
              }
            );
        });
        $(o.items).bind("mouseleave click", function(){
          $(this).animate({
              top: _top+"px"
            },
            {
              duration: 100, 
              easing: 'linear'
            }
          );
        });
      }
      
      $("#"+o.sliderDiv+" .dot").click(function(){
         if ($(this).hasClass("active")==0) {
           num = $(this).attr("slidenum");
           $(o.items[num]).trigger("click");
         }
      });
      
      /*$("#"+o.sliderDiv+" .slide img").animate({
          'marginLeft':     '-=50px',
          'marginTop':      '-=50px',
          'width': '+=50px',
          'height': 'auto',
          'opacity':  1
      }, 2000); */

      if (o.showArrows) {
        
      }

       if (o.fullscreen==1){
        var anim = null;
        $($("#"+o.sliderDiv)).hover( 
          function(){
            $(anim).stop();
            $(".fullscreen", this).stop().fadeIn(100);
          },
          function(){
            anim = $(".fullscreen", this).delay(1000).fadeOut(100);
          }
        );
        
        $(".bgdiv",$("#"+o.sliderDiv).parent()).click(function(){
            $("#"+o.sliderDiv).css("marginTop", _self.sliderMarginTop);
            $this = $("#"+o.sliderDiv).parent();
            $(".fullscreen", $this).css("width", "25px");
            $(".fullscreen", $this).css("height", "25px"); 
            $(".dots", $this).css("display", "inline");         
            $("#wpadminbar").css("display", "inline");
            $this.css("position", "");
            $this.css("width", "100%");
            $this.css("height", _self._temph);
            $this.css("top", "");
            $this.css("left", "");
            $(".aleft", $this).css("display", "inline");
            $(".aright", $this).css("display", "inline");   
            $(o.items).css({
             'left': "50%"  
            });             
            var bgdiv = $(".bgdiv",$this);
            bgdiv.css({
              width: 0,
              height: 0,
              zIndex: 0,
              opacity: 0
            });
            $(o.parentDiv).css("zIndex", 0);
            bgdiv.animate({opacity:0});
            _self.fullscreen = false;
            $(window).trigger("resize");
            $("body").css("overflow", "auto");
            
         });
         $(".fullscreen", $("#"+o.sliderDiv)).click(function(){
            _self.resetSubslides();
            $("#"+o.sliderDiv).css("marginTop", "5%");
            $this = $("#"+o.sliderDiv).parent();
            if (_self._temph==null) _self._temph = parseInt($("#"+o.sliderDiv).outerHeight(true));
            $(".fullscreen", $this).css("width", "0");
            $(".fullscreen", $this).css("height", "0");
            $("#wpadminbar").css("display", "none");
            $(".dots", $this).css("display", "none");                                                                       
            $this.css("position", "fixed");
            $this.css("width", $(window).width());          
            $this.css("height", $(window).height());
            $this.css("top", 0);
            $this.css("left", 0);
            $(".aleft", $this).css("display", "none");
            $(".aright", $this).css("display", "none");         
            $("body").css("overflow", "hidden");
  
            var bgdiv = $(".bgdiv",$this);
            bgdiv.css({
              display: 'inline',
              margin: 0,
              left: 0,
              zIndex: 1000000,
              width: $(window).width(),
              height: $(window).height()
            });
            bgdiv.animate({opacity:0.5});
            $(o.parentDiv).css("zIndex", 1000001);
            
            var slider = o.parentDiv; 
            var correction = 0.9;
            var w = $(window).width();
            var ratio = (w/o.sliderWidth);          
            var h = $(window).height();
            if ((ratio*o.sliderHeight)>h) {
                ratio = (h/o.sliderHeight);
                w = w*ratio;
            } 
            $(slider).css("width", w*correction);
            $(slider).css("height", ratio*o.sliderHeight*correction);

            $(o.slides).animate({
                height: ratio*o.imageHeight*correction,
                width: ratio*o.imageWidth*correction
               
              },
              {
                duration: 500, 
                easing: 'linear'
              }
            );
            $(o.subslides).animate({
                height: ratio*o.imageHeight*correction,
                width: ratio*o.imageWidth*correction
               
              },
              {
                duration: 500, 
                easing: 'linear'
              }
            );
            //$(o.slides).css('width', ratio*o.imageWidth*correction);  
            //$(o.slides).css("height", ratio*o.imageHeight*correction);
           
            $(o.items).css({
              'marginLeft': (($(window).width()-ratio*o.imageWidth*correction)/2*0.95),
              'left': 0  
            });                   
            _self.fullscreen = true;
        });
      }
      _self.initRotations(o.items);
      
    }
    
  };

  $.fn.polaroidslider = function( method ) {
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.polaroidslider' );
    }    
  
  };

})( jQuery );    

function sgn(x){
  if(x>0)return 1;
  else if(x<0)return -1;
  else return 0;
}

function videoFix(node) {
  var src = jQuery(node).attr('src');
  if (src!=null) {
    if (src.match(/(.*)youtube.com(.*)/)!=null) {
       jQuery(node).attr('src', src+"?wmode=opaque");
    }
  }
}

