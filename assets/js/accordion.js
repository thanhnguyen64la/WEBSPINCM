









// FOR ORDER LIST
jQuery(document).ready(function($){
    
    var panels = $(".orderlist-body").hide();

    panels.first().show();
    
     $(".orderlist-head").click(function(){

         var $this = $(this);

         panels.slideUp();
         $this.next().slideDown();
         
    });

});