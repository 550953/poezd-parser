var height = 1200;
                var tops = '300';

$(document).on('click', '.edit', function(e) {   //EDIT
            
        var id = $(this).attr('value');
        var index = $(this).attr('id');
        var name = $(this).attr('name');
        var position = $(this).position();

        $('.frame').css("display", "block");
        $('.frame').height($('.block_content').height()+ "px");
        $('.add_part').center();

                 
        $.ajax({
            type: "POST",
            url: "edit_train.php",
            data: {
                "id_edit" : id,
                "index" : index,
                "name" : name
            },
            success: function(response) {
                $('.add_part').html('');
                $('.add_part').append(response);
                console.log(response);
                    }
        });
});

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
    return this;
}


$(document).on('click', '.save', function(e) {   //SAVE DATA
              
    e.stopPropagation(); 
    e.preventDefault(); 
    var id = $(this).attr('value');
    var index = $(this).attr('id');
    var name = $('.name').attr('value');

            
    var data = new FormData();
        
    data.append("id_save", id);
    data.append("name", name);
    data.append("index", index);
     $.ajax({
            type: "POST",
            url: "edit_train.php",
            data:  data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false,
         
            success: function(response) {
                       
                $('.add_part').html('');
                $('.row' + index).html('');
                $('.row' + index).html(response);
                $('.frame').css("display", "none");
                console.log(response);
                    }
              });       
            
             
});

$(document).on('click', '.close', function(e) {
             $('.frame').css("display", "none");
             $('.add_part').html('');
             e.stopPropagation(); 
             e.preventDefault(); 
             files = null;
         });
         
         
 $(document).on('click', '.delete', function(e) {
        var id = $(this).attr('value');
        var index = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "edit_train.php",
                    data: {
                        "id_delete" : id,
                        "index" : index
                    },
                    success: function(response) {
                        $('.row' + index).html('');
                        console.log(response);
                    }
                });
});


 $(document).on('click', '.add', function(e) {
     
        var index = $(this).attr('id');
            
        $('.frame').css("display", "block");
        $('.frame').height($('.block_content').height()+ "px");
        $('.add_part').center();
        $.ajax({
            type: "POST",
            url: "edit_train.php",
            data: {
                "index_add" : index
            },
            success: function(response) {
                      
                $('.add_part').html('');
                $('.add_part').append(response);

                console.log(response);
            }
        });
});

$(document).on('click', '.save_add', function(e) {   //SAVE DATA
              
    e.stopPropagation(); 
    e.preventDefault(); 
    var index = $(this).attr('id');
    var name = $('.name').attr('value');

            
    var data = new FormData();
   
    data.append("name", name);
    data.append("index_save", index);
     $.ajax({
            type: "POST",
            url: "edit_train.php",
            data:  data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false,
         
            success: function(response) {
                       
                $('.add_part').html('');
                $(response).insertAfter('.row' + (index-1));
                $('.frame').css("display", "none");
                console.log(response);
                    }
              });       
            
             
});


$(document).on('click', '.add_book', function(e) {
     
        var index = $(this).attr('id');
            
        $('.frame').css("display", "block");
        $('.frame').height($('.content').height()+ "px");
        $('.add_part').center();
        $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data: {
                "index_add" : index
            },
            success: function(response) {
                      
                $('.add_part').html('');
                $('.add_part').append(response);

                console.log(response);
            }
        });
});


$(document).on('click', '.save_add_book', function(e) {   //SAVE DATA
              
    e.stopPropagation(); 
    e.preventDefault(); 
    var index = $(this).attr('id');
    var name = $('.name').attr('value');
	var book_id = $('.uid').attr('value');

            
    var data = new FormData();
   
    data.append("name", name);
    data.append("book_id", book_id);
    data.append("index_save", index);
     $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data:  data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false,
         
            success: function(response) {
                       
                $('.add_part').html('');
                $(response).insertAfter('.row_book' + (index-1));
                $('.frame').css("display", "none");
                console.log(response);
                    }
              });       
            
             
});

$(document).on('click', '.edit_book', function(e) {   //EDIT
            
        var id = $(this).attr('value');
        var index = $(this).attr('id');
        var position = $(this).position();

        $('.frame').css("display", "block");
        $('.frame').height($('.content').height()+ "px");
        $('.add_part').center();

                 
        $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data: {
                "id_edit" : id,
                "index" : index
           
            },
            success: function(response) {
                $('.add_part').html('');
                $('.add_part').append(response);
                console.log(response);
                    }
        });
});


$(document).on('click', '.save_book', function(e) {   //SAVE DATA
              
    e.stopPropagation(); 
    e.preventDefault(); 
    var id = $(this).attr('value');
    var index = $(this).attr('id');
	var book_id = $('.uid').attr('value');
    var name = $('.name').attr('value');

    var data = new FormData();
        
    data.append("id_save", id);
    data.append("name", name);
	data.append("book_id", book_id);
    data.append("index", index);
     $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data:  data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false,
         
            success: function(response) {
                       
                $('.add_part').html('');
                $('.row_book' + index).html('');
                $('.row_book' + index).html(response);
                $('.frame').css("display", "none");
                console.log(response);
                    }
              });           
});

 $(document).on('click', '.delete_book', function(e) {
        var id_book = $(this).val();
        var index = $(this).attr('id');
        console.log(id_book);
                $.ajax({
                    type: "POST",
                    url: "edit_book_info.php",
                    data: {
                        "id_delete" : id_book,
                        "index" : index
                    },
                    success: function(response) {
                        $('.row_book' + index).html('');
                        console.log(response);
                    }
                });
});



$(document).on('click', '.edit_url', function(e) {   //EDIT
            
        var id = $(this).attr('value');
        var index = $(this).attr('id');
		var url_app = $(this).attr('app');
        var position = $(this).position();

        $('.frame').css("display", "block");
        $('.frame').height($('.content').height()+ "px");
        $('.add_part').center();

                 
        $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data: {
                "id_edit_url" : id,
                "index" : index,
                "url_app" : url_app
           
            },
            success: function(response) {
                $('.add_part').html('');
                $('.add_part').append(response);
                console.log(response);
                    }
        });
});


$(document).on('click', '.save_url', function(e) {   //SAVE DATA
              
    e.stopPropagation(); 
    e.preventDefault(); 
    var id = $(this).attr('value');
    var index = $(this).attr('id');
    var name = $('.name').attr('value');
    var url_app = $('.url_app').attr('value');
    var data = new FormData();
  
    data.append("id_save_url", id);
    data.append("name", name);
	data.append("url_app", url_app);
    data.append("index", index);
     $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data:  data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false,
         
            success: function(response) {
                       
                $('.add_part').html('');
                $('.row_url' + index).html('');
                $('.row_url' + index).html(response);
                $('.frame').css("display", "none");
                console.log(response);
                    }
              });           
});



$(document).on('click', '.edit_ver', function(e) {   //EDIT
            
        var id = $(this).attr('value');
        var index = $(this).attr('id');
        var position = $(this).position();

        $('.frame').css("display", "block");
        $('.frame').height($('.content').height()+ "px");
        $('.add_part').center();

                 
        $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data: {
                "id_edit_ver" : id,
                "index" : index
           
            },
            success: function(response) {
                $('.add_part').html('');
                $('.add_part').append(response);
                console.log(response);
                    }
        });
});


$(document).on('click', '.save_ver', function(e) {   //SAVE DATA
              
    e.stopPropagation(); 
    e.preventDefault(); 
    var id = $(this).attr('value');
    var index = $(this).attr('id');
    var name = $('.name').attr('value');

    var data = new FormData();
        
    data.append("id_save_ver", id);
    data.append("name", name);
    data.append("index", index);
     $.ajax({
            type: "POST",
            url: "edit_book_info.php",
            data:  data,
            cache: false,
            processData: false, // Don't process the files
            contentType: false,
         
            success: function(response) {
                       
                $('.add_part').html('');
                $('.row_ver' + index).html('');
                $('.row_ver' + index).html(response);
                $('.frame').css("display", "none");
                console.log(response);
                    }
              });           
});





         
         
         