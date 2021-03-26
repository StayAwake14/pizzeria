  $(document).ready(function() {
      $(document).on('keyup', '.pcode', function(b) {
        code = $(this).val();
        $.ajax({
            url: './../template/cookies.php',
            type: 'POST',
            data: {
                action: "pcode",
                code: code,
            },
            success: function(data) {
              if(data == "Kod nie istnieje")
              {
                $('#codeInfo').css('color', 'red');
                $('#codeInfo').text(data);
              }
              else
              {
                $('#codeInfo').css('color', 'green');
                $('#codeInfo').text(data);
              }
            }               
        });
        b.preventDefault();
      });

      $(document).on('keyup', '.order_number', function(b) {
        order_number = $(this).val();
        hostname = location.hostname;
        $.ajax({
            url: 'https://'+hostname+'/pizzeria/lib/template/cookies.php',
            type: 'POST',
            data: {
                action: "order_info",
                order_number: order_number,
            },
            success: function(data) {
              if(data === "Nie znaleziono zamówienia")
              {
                $('#orderInfo').css('color', 'red');
                $('#orderInfo > *').remove();
                $('#orderInfo').append("<p class='p-0 m-0'>");
                $('#orderInfo > p').text(data);
              }
              else
              {
                $('#orderInfo').css('color', 'green');
                $('#orderInfo > *').remove();
                $('#orderInfo').append(data);
              }
            }               
        });
        b.preventDefault();
      });

      $(document).on('submit','form[name=clearCart]', function(a){
        $.ajax({
            url: './../template/cookies.php',
            type: 'POST',
            data: {
                action: "clear"
            },
            success: function(data) {
                $('#cartModal2 > #cartDetails').remove();
                $("#cartModal2" ).load(" #cartModal2 > *");
                swal("Sukces!", "Koszyk wyczyszczony", "success");
            }               
        });
        a.preventDefault();
      });

      $(document).on('click', '.removeItem', function(b) {

        pizza_delete_id = $(this).parent().find('input[name=pizza_info]').val();
        pizza_delete_size = $(this).parent().find('input[name=pizza_info]').data('size');
        $.ajax({
              url: './../template/cookies.php',
              type: 'POST',
              data: {
                  action: "delete",
                  pizza_id_delete: pizza_delete_id,
                  pizza_size_delete: pizza_delete_size
              },
              success: function(data) {
                  $("#cartModal2" ).load(" #cartModal2 > *");
                  swal("Sukces!", "Przedmiot usunięty z koszyka", "success");
              }               
          });
          b.preventDefault();
        });

        $(document).on('submit','form[name=pizza_form]', function(e) {
        pizza_id = $(this).find('input[name=pizza_id').val();
        pizza_name = $(this).find('input[name=pizza_name]').val();
        pizza_quantity = $(this).find('input[name=pizza_quantity]').val();
        pizza_size = $(this).find('select[name=pizza_size] option:selected').val();
        pizza_price = $(this).find('select[name=pizza_size] option:selected').data('price');
        $.ajax({
            url: './../template/cookies.php',
            type: 'POST',
            data: {
                pizza_id: pizza_id,
                pizza_name: pizza_name,
                pizza_quantity: pizza_quantity,
                pizza_size: pizza_size,
                pizza_price: pizza_price
            },
            success: function(data) {
                $('.cart-body p').remove();
                $("#cartModal2" ).load(" #cartModal2 > *");
                swal("Sukces!", "Przedmiot dodany do koszyka", "success");
            }               
        });
        e.preventDefault();
      });

      $(document).on('click', '#filter', function(b) {
          category_id = $(this).val();
        
          var categories = $('input[name=category]:checked').map(function(){ 
              return this.value; 
          }).get();

          //console.log(categories);
          if(categories.length < 1)
          {
            $.ajax({
              url: 'catalog.php',
              type: 'POST',
              data: {
                  filter: "",
                  category_array: categories
              },
              success: function(data) {
                
                var result = $(' .pizza_container').append(data).find(' .pizza_container').html();
                //console.log(result);
                $(' .pizza_container').html(result);
                $(' .pizza_container').empty().html(result);
                swal("Sukces!", "Katalog został przeszukany", "success");
              }
            });
          } else
          {
            $.ajax({
              url: 'catalog.php',
              type: 'POST',
              data: {
                  filter: "filter",
                  category_array: categories
              },
              success: function(data) {
               
                var result = $(' .pizza_container').append(data).find(' .pizza_container').html();
                //console.log(result);
                $(' .pizza_container').html(result);
                $(' .pizza_container').empty().html(result);
                swal("Sukces!", "Katalog został przeszukany", "success");
              }
            });
          }
          
          b.preventDefault();
        });
    });
    function plus(id){
      $('.count'+id).prop('disabled', true);
      $('.count'+id).val(parseInt($('.count'+id).val()) + 1 );
    }

    function minus(id){
    $('.count'+id).val(parseInt($('.count'+id).val()) - 1 );
      if ($('.count'+id).val() == 0) {
        $('.count'+id).val(1);
      }
    }

    function next(){
      if($('.step-1').is(':visible')){
        $('.step-1').css('display', 'none')
        $('.step-2').css('display', 'block')
      }
    }

    function previous(){
      if($('.step-2').is(':visible')){
        $('.step-2').css('display', 'none')
        $('.step-1').css('display', 'block')
      }
    }
 