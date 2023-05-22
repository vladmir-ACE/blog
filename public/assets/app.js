
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

    

} )

