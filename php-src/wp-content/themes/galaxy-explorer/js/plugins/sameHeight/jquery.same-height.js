/*
 * jQuery SameHeight plugin
 * 
 * jQuery('#myelement').sameHeight({  // apply to elements within "#myelement"
 * 			elements: '.mysubelement',  // selector elements to match within the "#myelement" selector. Defaults to `>*` (all children elements)
 * 			skipClass: 'same-height-ignore',  // the name of any CSS class to ignore for matching heights. Defaults to `same-height-ignore`
 * 			leftEdgeClass: 'same-height-left',  // class name to apply to the first element whose height has been resized. Defaults to `same-height-left`
 * 			rightEdgeClass: 'same-height-right',  // Class name to apply to the last element whose height has been resized. Defaults to `same-height-right`
 * 			flexible: false,  // Update heights when window size changes. Defaults to `false`
 * 			multiLine: false,  // Elements whose heights to match are organized in rows instead of columns. Defaults to `false`
 * 			useMinHeight: false,  // Set CSS `min-height` of elements instead of CSS `height`. Defaults to `false`
 * 			mixWidth: false  // The minimum width required before applying any height changes
 *});

 */
;(function($){
  $.fn.sameHeight = function(opt) {
    var options = $.extend({
      skipClass: 'same-height-ignore',
      leftEdgeClass: 'same-height-left',
      rightEdgeClass: 'same-height-right',
      elements: '>*',
      flexible: false,
      multiLine: false,
      useMinHeight: false,
      minWidth: false
    },opt);
    return this.each(function() {
      var holder = $(this), postResizeTimer, ignoreResize;
      var elements = holder.find(options.elements).not('.' + options.skipClass);
      if(!elements.length) return;
      
      /**
       * Check if max width has been hit.
       */
      function hitMinWidth() {
        return options.minWidth !== false && options.minWidth > $(document).width();
      }
			
      // resize handler
      function doResize() {
        elements.css(options.useMinHeight && supportMinHeight ? 'minHeight' : 'height', '');
        if (!hitMinWidth()) {  // update height if within max width
          if(options.multiLine) {
            // resize elements row by row
            resizeElementsByRows(elements, options);
          } else {
            // resize elements by holder
            resizeElements(elements, holder, options);
          }
        }
      }
      doResize();
			
      // handle flexible layout / font resize
      var delayedResizeHandler = function() {
        if(!ignoreResize) {
          ignoreResize = true;
          doResize();
          clearTimeout(postResizeTimer);
          postResizeTimer = setTimeout(function() {
            doResize();
            setTimeout(function(){
              ignoreResize = false;
            }, 10);
          }, 100);
        }
      };

      // handle flexible/responsive layout
      if(options.flexible) {
        $(window).bind('resize orientationchange fontresize', delayedResizeHandler);
      }

      // handle complete page load including images and fonts
      $(window).bind('load', delayedResizeHandler);
    });
  };
	
  // detect css min-height support
  var supportMinHeight = typeof document.documentElement.style.maxHeight !== 'undefined';
	
  // get elements by rows
  function resizeElementsByRows(boxes, options) {
    var currentRow = $(), maxHeight, firstOffset = boxes.eq(0).offset().top;
    boxes.each(function(ind){
      var curItem = $(this);
      if(curItem.offset().top === firstOffset) {
        currentRow = currentRow.add(this);
      } else {
        maxHeight = getMaxHeight(currentRow);
        resizeElements(currentRow, maxHeight, options);
        currentRow = curItem;
        firstOffset = curItem.offset().top;
      }
    });
    if(currentRow.length) {
      maxHeight = getMaxHeight(currentRow);
      resizeElements(currentRow, maxHeight, options);
    }
  }
	
  // calculate max element height
  function getMaxHeight(boxes) {
    var maxHeight = 0;
    boxes.each(function(){
      maxHeight = Math.max(maxHeight, $(this).outerHeight());
    });
    return maxHeight;
  }
	
  // resize helper function
  function resizeElements(boxes, parent, options) {
    var parentHeight = typeof parent === 'number' ? parent : parent.height();
    boxes.removeClass(options.leftEdgeClass).removeClass(options.rightEdgeClass).each(function(i){
      var element = $(this);
      var depthDiffHeight = 0;
			
      if(typeof parent !== 'number') {
        element.parents().each(function(){
          var tmpParent = $(this);
          if(this === parent[0]) {
            return false;
          } else {
            depthDiffHeight += tmpParent.outerHeight() - tmpParent.height();
          }
        });
      }
      var calcHeight = parentHeight - depthDiffHeight - (element.outerHeight() - element.height());
      if(calcHeight > 0) {
        element.css(options.useMinHeight && supportMinHeight ? 'minHeight' : 'height', calcHeight);
      }
    });
    boxes.filter(':first').addClass(options.leftEdgeClass);
    boxes.filter(':last').addClass(options.rightEdgeClass);
  }
}(jQuery));