
$(function(){
        
    function onSubmitArticle(e) {

        e.preventDefault();

        let titre=$('#article_title').val();
        let contenu=$('#article_contenu').val();

        $.ajax({
            url:'/article/save',
            method:'POST',
            data:{title:titre ,contenu:contenu},
            success: function (response){
                $("#response_message").text(
                    response.message
                );
                //console.log(response.message);
            
            },
            error: function (error){
                console.log(error);
            
            }
        });
        //console.log(titre ,contenu);
    }

    
    $('#submit_article').on("click",onSubmitArticle);


    $('a.like').click(function(event) {
        event.preventDefault();

        var id=$(this).attr('data-id');
        $.ajax({
            url:'/article/like/'+id,
            method:'GET',
            data:{id:id},           
            success:function(response){
                console.log(response);
                $('#'+id+'Like').text(response.nbrLike);
                $('#'+id+'Dis').text(response.nbrDislike);
              }




        })
        
    });


    $('a.dislike').click(function(event) {
        event.preventDefault();

        var id=$(this).attr('data-id');

        $.ajax({
            url:'/article/dislike/'+id,
            method:'GET',
            data:{id:id},           
            success:function(response){
                console.log(response);
                $('#'+id+'Like').text(response.nbrLike);
                $('#'+id+'Dis').text(response.nbrDislike);
              }

        })
        
    });


    

} );



