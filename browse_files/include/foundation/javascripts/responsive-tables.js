$(document).ready(function() {
  var switched = false;
  var updateTables = function() {
    if (($(window).width() < 767) && !switched ){
      switched = true;
      $("table.responsive").each(function(i, element) {
        splitTable($(element));
      });
      return true;
    }
    else if (switched && ($(window).width() > 767)) {
      switched = false;
      $("table.responsive").each(function(i, element) {
        unsplitTable($(element));
      });
    }
  };
   
  $(window).load(updateTables);
  $(window).on("redraw",function(){switched=false;updateTables();}); // An event to listen for
  $(window).on("resize", updateTables);
   
	
	function splitTable(original)
	{
		original.wrap("<div class='table-wrapper' />");
		
		var copy_left = original.clone();
		
		copy_left.find("td:not(:first-child), th:not(:first-child)").remove();
		copy_left.removeClass("responsive");
		
		var copy_right = original.clone();
		
		copy_right.find("td:not(.actions), th:not(.actions)").remove();
		copy_right.removeClass("responsive");
		
		original.closest(".table-wrapper").append(copy_left).append(copy_right);
		copy_left.wrap("<div class='pinned left' />");
		copy_right.wrap("<div class='pinned right' />");
		original.wrap("<div class='scrollable' />");
	}
	
	function unsplitTable(original) {
    original.closest(".table-wrapper").find(".pinned").remove();
    original.unwrap();
    original.unwrap();
	}


});
