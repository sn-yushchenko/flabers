/*Начало скрипта*/
$(document).ready(function(){
    
                                /*Получаем списки городов*/
    
    $('.city').keyup(function(){
        $(this).removeAttr('name');
        var value = $(this).val();
        var id = $(this).attr("id");
        $("."+id+">ul").empty();
        if(value=="")
        {
            $("."+id).hide();
        }
        else
        {
            $.ajax({
                url:"nova.php",
                type:"POST",
                data:({value:value,method:"getCity"}),
                dataType:"html",
                success:function(data){
                    var result = jQuery.parseJSON(data); 
                    if(result.length==0)
                    {
                       $("."+id).hide();
                    }
                    else
                    {
                        for (var key in result)
                        {
                           $("."+id+">ul").append('<li id='+key+'>'+result[key]+'</li>'); 
                        }
                        $("."+id).show();
                    }
                }
            })//конец блока городов 
        }
    })//конец блока выбора городов
    
                            /*Подтягиваем список отделений в городе*/
    
    $(document).on("click","li", function() {
        var chCity=$(this).parent().parent().attr('class');
        if(chCity=="city_send" || chCity=="city_receive")
       {
            $('#'+chCity).val($(this).text());
           $('#'+chCity).attr('name',$(this).attr('id'));
           var idBlock=$(this).parent().parent().attr('class').replace("city","dep");
            $($(this).parent().parent()).hide();
            var refCity = $(this).attr('id');
            $.ajax({
                url:"nova.php",
                type:"POST",
                data:({refCity:refCity,method:"getDep"}),
                dataType:"html",
                success:function(depName){
                    var dep = jQuery.parseJSON(depName);
                    if(dep.length==0)
                    {
                       $($(this).parent().parent()).hide();
                    }
                    else
                    {
                        for (var key in dep)
                        {
                           $('.'+idBlock+">ul").append('<li id='+dep[key]+'>'+key+'</li>'); 
                        }
                        $('.'+idBlock+">ul").parent().show();
                    }
                }
            })
       }
        else if(chCity=="dep_send"||chCity=="dep_receive")
        {
            $('#'+chCity).val($(this).text());
            $('#'+chCity).attr('name',$(this).attr('id'));
            $(this).parent().parent().hide();
        }
        
        
    })//конец выбора списка отделений
                            /*Запрос на стоимость и срок доставки*/
    $("#submit").bind("click", function(){
        var CitySender = $('#city_send').attr('name');
        var CityRecipient = $('#city_receive').attr('name');
        $.ajax({
                url:"nova.php",
                type:"POST",
                data:({CitySender:CitySender,CityRecipient:CityRecipient,method:"getCost"}),
                dataType:"html",
                success:function(cost){
                   $("#summ").text(cost);
                }
            })
        $.ajax({
                url:"nova.php",
                type:"POST",
                data:({CitySender:CitySender,CityRecipient:CityRecipient,method:"getDate"}),
                dataType:"html",
                success:function(date){
                   var date = date.substr(0,10);
                   $("#d").text(date);
                }
            })
        
    });//конец рассчета

    
});//конец функции загрузки