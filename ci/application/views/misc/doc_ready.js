$("#per_page select").change(function(){
    $("#per_page").submit();
})

function only_number(event){
   if (((event.which > 47) && (event.which < 58)) || (event.which==8) || (event.which==9)) {
       return true;             
   } else {
     return false;               
   } 
}

function only_float(event){
   if (((event.which > 47) && (event.which < 58)) || (event.which==8) || (event.which==9) || (event.which==46) || (event.which==44)) {
       return true;             
   } else {
     return false;               
   }
} 

$(".ccllo").click(function(){     
    $.fancybox.close();        
})

$(".printer").click(function(){     
    window.print();        
})
