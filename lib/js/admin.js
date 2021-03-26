
    
    $(document).ready(function() {

        $(document).on('submit','form[name=editOrderStatus]', function(e) {
              order_id = $(this).parent().find('input[name=order_id]').val();
              order_status = $(this).parent().find('select[name=orderStatus]').val();

              $.ajax({
                    url: 'profile.php',
                    type: 'POST',
                    data: {
                        order_id: order_id,
                        order_status: order_status
                    },
                    success: function(data) {
                        $("#editOrderStatus"+order_id+" > form-group > label").remove();
                        $("#editOrderStatus"+order_id ).load(" #editOrderStatus"+order_id+" > *");
                        swal("Sukces!", "Zamówienie zaktualizowane!", "success");
                    }               
              });
          e.preventDefault();
        });
        
        $(document).on('submit','form[name=addCakeForm]', function(e) {


          size = $(this).parent().find('input[name=csize]').val();
          price = $(this).parent().find('input[name=cprice]').val();
          cm = $(this).parent().find('input[name=cm]').val();

          var letters = /^[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ]+$/;
          var numbers = /^[0-9]+$/;

          if(size.length > 3 || !size.match(letters))
          {
            swal("Błąd!", "Rozmiar powinien zawierać tylko 3 znaki (litery) np. XXL.", "error");
            return false;
          }

          if(!price.match(numbers) || price < 0)
          {
            swal("Błąd!", "Cena powinna zawierać wartości liczbowe nieujemne.", "error");
            return false;
          }

          if(!cm.match(numbers) || cm.length > 3 || cm < 0)
          {
            swal("Błąd!", "Rozmiar w centymetrach powinien zawierać wartości liczbowe, maksymalnie 3 znakowe (cyfry) nieujemne.", "error");
            return false;
          }
          
          $.ajax({
                url: 'profile.php',
                type: 'POST',
                data: {
                    cake_add_size: size,
                    cake_add_price: price,
                    cake_add_cm: cm
                },
                success: function(data) {
                    $('#addCakeForm')[0].reset();
                    $("#deleteCakeForm > form-group > *").remove();
                    $("#deleteCakeForm " ).load(" #deleteCakeForm > *");
                    swal("Sukces!", "Ciasto dodane!", "success");
                },
                error: function(data){
                  swal("Error!", "Rozmiar ciasta już istnieje!", "error");
                }                  
          });
          e.preventDefault();
        });

        $(document).on('submit','form[name=deleteCakeForm]', function(e) { 
          id = $(this).parent().find('option:selected').val();
          $.ajax({
                url: 'profile.php',
                type: 'POST',
                data: {
                    cake_delete_id: id,
                },
                success: function(data) {
                    $("#deleteCakeForm > form-group > *").remove();
                    $("#deleteCakeForm " ).load(" #deleteCakeForm > *");
                    swal("Sukces!", "Ciasto usunięte!", "success");
                }               
          });
          e.preventDefault();
        });

        $(document).on('submit','form[name=addCategoryForm]', function(e) {
          name = $(this).parent().find('input[name=catname]').val();

          var letters = /^[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ ]+$/;

          if(!name.match(letters))
          {
            swal("Błąd!", "Nazwa kategorii powinna zawierać tylko litery.", "error");
            return false;
          }

          $.ajax({
                url: 'profile.php',
                type: 'POST',
                data: {
                    category_add_name: name
                },
                success: function(data) {           
                    $('#addCategoryForm')[0].reset();
                    $("#addPizzaForm > form-group > *").remove();
                    $("#addPizzaForm " ).load(" #addPizzaForm > *");
                    $("#deleteCategoryForm > form-group > *").remove();
                    $("#deleteCategoryForm " ).load(" #deleteCategoryForm > *");
                    swal("Sukces!", "Kategoria dodana!", "success");
                },
                error: function(data){
                  swal("Error!", "Kategoria już istnieje!", "error");
                }               
          });
          e.preventDefault();
        });

        $(document).on('submit','form[name=deleteCategoryForm]', function(e) { 
          id = $(this).parent().find('option:selected').val();
          $.ajax({
                url: 'profile.php',
                type: 'POST',
                data: {
                    category_delete_id: id,
                },
                success: function(data) {
                    $("#addPizzaForm > form-group > *").remove();
                    $("#addPizzaForm " ).load(" #addPizzaForm > *");
                    $("#deleteCategoryForm > form-group > *").remove();
                    $("#deleteCategoryForm " ).load(" #deleteCategoryForm > *");
                    swal("Sukces!", "Kategoria usunięta!", "success");
                },
                error: function(data){
                  swal("Error!", "Kategoria jest używana w przepisach. Należy najpierw usunąć przepisy przypisane do kategorii.", "error");
                }                  
          });
          e.preventDefault();
        });

        $(document).on('submit','form[name=addProductForm]', function(e) {
          name = $(this).parent().find('input[name=prname]').val();
          price = $(this).parent().find('input[name=prprice]').val();

          var letters = /^[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ ]+$/;
          var numbers = /^[0-9]+$/;

          if(!name.match(letters))
          {
            swal("Błąd!", "Nazwa produktu powinna zawierać tylko litery.", "error");
            return false;
          }

          if(price < 0)
          {
            swal("Błąd!", "Cena produktu powinna zawierać wartości liczbowe nieujemne.", "error");
            return false;
          }

          $.ajax({
                url: 'profile.php',
                type: 'POST',
                data: {
                    product_add_name: name,
                    product_add_price: price
                },
                success: function(data) {
                    $('#addProductForm')[0].reset();
                    $("#addPizzaForm > form-group > *").remove();
                    $("#addPizzaForm " ).load(" #addPizzaForm > *");
                    $("#deleteProductForm > form-group > *").remove();
                    $("#deleteProductForm " ).load(" #deleteProductForm > *");
                    swal("Sukces!", "Produkt dodany!", "success");
                },
                error: function(data){
                  swal("Error!", "Produkt już istnieje!", "error");
                }             
          });
          e.preventDefault();
        });

        $(document).on('submit','form[name=deleteProductForm]', function(e) { 
          id = $(this).parent().find('option:selected').val();
          $.ajax({
                url: 'profile.php',
                type: 'POST',
                data: {
                    product_delete_id: id,
                },
                success: function(data) {
                    $("#addPizzaForm > form-group > *").remove();
                    $("#addPizzaForm " ).load(" #addPizzaForm > *");
                    $("#deleteProductForm > form-group > *").remove();
                    $("#deleteProductForm " ).load(" #deleteProductForm > *");
                    swal("Sukces!", "Produkt usunięty!", "success");
                },
                error: function(data){
                  swal("Error!", "Produkt jest używany w przepisach. Należy najpierw usunąć przepisy zawierające produkt.", "error");
                }                  
          });
          e.preventDefault();
        });

        $(document).on('submit','form[name=addPromotionForm]', function(e) {
            code = $(this).parent().find('input[name=pcode]').val();
            percentage = $(this).parent().find('input[name=percentage]').val();

            var letters = /^[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ]+$/;
            var numbers = /^[0-9]+$/;

            if(!code.match(letters) || code.length > 10)
            {
              swal("Błąd!", "Kod promocyjny powinien zawierać maksymalnie 10 znakowy ciąg liter lub cyfr.", "error");
              return false;
            }

            if(percentage < 0 || percentage > 100 || !percentage.match(numbers) || percentage.length > 3)
            {
              swal("Błąd!", "Procent powinien zawierać 3 znakowe wartości liczbowe nieujemne i mniejsze niż 100.", "error");
              return false;
            }

            $.ajax({
                  url: 'profile.php',
                  type: 'POST',
                  data: {
                      promotion_code: code,
                      percentage: percentage
                  },
                  success: function(data) {
                      $('#addPromotionForm')[0].reset();
                      $("#deletePromotionForm > form-group > *").remove();
                      $("#deletePromotionForm " ).load(" #deletePromotionForm > *");
                      swal("Sukces!", "Kod promocyjny dodany!", "success");
                  },
                  error: function(data){
                    swal("Error!", "Kod promocyjny już istnieje!", "error");
                  }                  
            });
            e.preventDefault();
          });

          $(document).on('submit','form[name=deletePromotionForm]', function(e) { 
            id = $(this).parent().find('option:selected').val();
            $.ajax({
                  url: 'profile.php',
                  type: 'POST',
                  data: {
                      promotion_delete_id: id,
                  },
                  success: function(data) {
                      $("#deletePromotionForm > form-group > *").remove();
                      $("#deletePromotionForm " ).load(" #deletePromotionForm > *");
                      swal("Sukces!", "Kod promocyjny usunięty!", "success");
                  }               
            });
            e.preventDefault();
          });

          $(document).on('submit','form[name=addPizzaForm]', function(e) {
            pname = $(this).parent().find('input[name=pname]').val();
            pizzaPicture = $(this).parent().find('input[name=pizzaPicture]').val();

            var letters = /^[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ ]+$/;
            var numbers = /^[0-9]+$/;
            var fileExtension = ['jpg'];
            var ext = pizzaPicture.split('.').pop().toLowerCase();

            if(!pname.match(letters))
            {
              swal("Błąd!", "Nazwa pizzy powinna zawierać tylko litery.", "error");
              return false;
            }

            if(fileExtension.lastIndexOf(ext) == -1) {
              swal("Błąd!", "Obrazek pizzy powinien być w rozszerzeniu jpg.", "error");
              return false;
            }

            $.ajax({
                  url: 'profile.php',
                  type: 'POST',
                  enctype: 'multipart/form-data',
                  contentType: false,
                  cache: false,
                  processData:false,
                  data: new FormData(this),
                  success: function(data) {
                      $('#addPizzaForm')[0].reset();
                      $("#deletePizzaForm > form-group > *").remove();
                      $("#deletePizzaForm " ).load(" #deletePizzaForm > *");
                      swal("Sukces!", "Przepis dodany!", "success");
                  },
                  error: function(data){
                    swal("Error!", "Nazwa przepisu  już istnieje!", "error");
                  }                
            });
            e.preventDefault();
          });

          $(document).on('submit','form[name=deletePizzaForm]', function(e) { 
            pizza_delete_id = $(this).parent().find('#deletePizzaSelection :selected').val();
            $.ajax({
                  url: 'profile.php',
                  type: 'POST',
                  data: {
                    pizza_delete_id: pizza_delete_id,
                  },
                  success: function(data) {
                      $("#deletePizzaForm > form-group > *").remove();
                      $("#deletePizzaForm " ).load(" #deletePizzaForm > *");
                      swal("Sukces!", "Przepis usunięty!", "success");
                  }               
            });
            e.preventDefault();
          });
    });