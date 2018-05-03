(function(){

    //--------------------------------------------------------------------------------------------------\\
    //                                   MODULES                                                        \\
    //--------------------------------------------------------------------------------------------------\\

    var pageBuilding = angular.module("pageBuilding", []);

    pageBuilding.controller('menuCategoriesController', function () {
        this.query = 'SELECT `category_name` FROM `main_categories`';
        this.dbAnswerString = databaseFetch(this.query);
        this.dbAnswerString = this.dbAnswerString.trim();
        console.log(this.dbAnswerString);
        this.singleElement = JSON.parse(this.dbAnswerString);



    });

    //--------------------------------------------------------------------------------------------------\\
    //                                   CONTROLLERS                                                    \\
    //--------------------------------------------------------------------------------------------------\\

    pageBuilding.controller('dataRowsController', function ($rootScope, dataFilter) {
    //-------------------------------------Adding products do database
        //inputOne
        this.insertOneDb = function (event) {
          var clickedElement = event.target;
          var td=clickedElement.parentNode;
          var tr=td.parentNode;

          var prodData={};
          var allProducts=[];

          $(tr).each(function(){

            descNonFormated=$(this).find('[data-id="desc"]').text();
            desc=descNonFormated.replace(/\r?\n|\r/g, "");
            desc=desc.replace(/\t/g,'');

            title=$(this).find('[data-id="tit"]').html();
            title=title.replace(/\r?\n|\r/g, "");
            title=title.replace(/\t/g,'');

          prodData={
                'title':title,
                'desc':desc,
                  'smallInfo':$(this).find('[data-id="smallInfo"]').val(),
                'cena':$(this).find('[data-id="cena"]').html(),
                'numer':$(this).find('[data-id="numer"]').html(),
                'wymiar':$(this).find('[data-id="wymiar"]').html(),
                  'wysokosc':+$(this).find('[data-id="wysokosc"]').html(),
                  'szerokosc':$(this).find('[data-id="szerokosc"]').html(),
                  'dlugosc':$(this).find('[data-id="dlugosc"]').html(),
                'waga':$(this).find('[data-id="waga"]').html(),
                'kategoria':$(this).find('[data-id="kategoria"]').val(),

                'download1':$(this).find('[data-id="grafikaDownload"]').attr('src'),
                'fileName1':$(this).find('[data-id="grafikaNazwa"]').val(),

                'download2':$(this).find('[data-id="grafikaDownload_2"]').attr('src'),
                'fileName2':$(this).find('[data-id="grafikaNazwa_2"]').val()
              },
          stringed=JSON.stringify(prodData);

          allProducts.push(stringed);


        })

          $.post("Controller/ajax.php",
            {
                products:allProducts
            },
            function(data, status){
              alert('Dodano!');
            });

        }


        //inputs
        this.insertDb = function (event) {
          var clickedElement = event.target;
          var td=clickedElement.parentNode;
          var tr=td.parentNode;
          var buttonData=clickedElement.innerHTML;

          var prodData={};
          var allProducts=[];

          var passedData='';
          var guardian=true;

          //For single press
          if(buttonData=='Dodaj'){
            passedData=tr;
            guardian=true;
          }else{//allInputs
            guardian==false;
            passedData='tr:not(#skip)'
            if(confirm('Napewno dodajesz te produkty do bazy?')){
              guardian==true;
            }
          }

          if(guardian==true){
            $(passedData).each(function(){
              descNonFormated=$(this).find('[data-id="desc"]').text();
              desc=descNonFormated.replace(/\r?\n|\r/g, "");
              desc=desc.replace(/\t/g,'');

              title=$(this).find('[data-id="tit"]').html();
              title=title.replace(/\r?\n|\r/g, "");
              title=title.replace(/\t/g,'');

                        prodData={
                              'title':title,
                              'desc':desc,
                              'smallInfo':$(this).find('[data-id="smallInfo"]').val(),
                              'cena':$(this).find('[data-id="cena"]').html(),
                              'numer':$(this).find('[data-id="numer"]').html(),
                              'wymiar':$(this).find('[data-id="wymiar"]').html(),
                              'wysokosc':+$(this).find('[data-id="wysokosc"]').html(),
                              'szerokosc':$(this).find('[data-id="szerokosc"]').html(),
                              'dlugosc':$(this).find('[data-id="dlugosc"]').html(),
                              'waga':$(this).find('[data-id="waga"]').html(),
                              'kategoria':$(this).find('[data-id="kategoria"]').val(),
                              'download1':$(this).find('[data-id="grafikaDownload"]').attr('src'),
                              'fileName1':$(this).find('[data-id="grafikaNazwa"]').val(),
                              'download2':$(this).find('[data-id="grafikaDownload_2"]').attr('src'),
                              'fileName2':$(this).find('[data-id="grafikaNazwa_2"]').val()
                            },
                        stringed=JSON.stringify(prodData);
              allProducts.push(stringed);
            })

            $.post("Controller/ajax.php",
              {
                  products:allProducts
              },
              function(data, status){
                //alert('Dodano!');
              });
            }
        }
    //-------------------------------------enabling/disabling the element edition
        this.statusChange = function (event) {

            //Get displayed Data
            this.clickedElement = event.target;
            var clickedElement = event.target;
            num = $(clickedElement).data('number');


            tit=$('#tit_' + num);
            desc=$('#desc_' + num);
            cena=$('#cena_' + num);
            number=$('#numer_' + num);
            szerokosc=$('#wysokosc_' + num);
            wysokosc=$('#szerokosc_' + num);
            dlugosc=$('#dlugosc_' + num);
            waga=$('#waga_' + num);

            all=$('#tit_' + num+','+'#desc_' + num+','+'#cena_' + num+','+'#numer_' + num+','+'#waga_' + num+','+'#wysokosc_' + num+','+'#szerokosc_' + num+','+'#dlugosc_' + num);

        }

        //------------------------------------- Remove row
        this.remove = function (event) {
            this.statusChange(event);
            if(confirm('Usuwasz ten produkt?')){
              $(event.target).parent().remove();
            }else{

            }
        }

        //-------------------------------------Edit row value
        this.editContent = function (event) {

            //Get displayed Data
            this.statusChange(event);
            var num = $(event.target).data('number');

            //SetEditables and add class
            $(all)
                .attr('contenteditable', 'true')
                .addClass('editable');

        }

        //------------------------------------- Accept the changes
        this.revertEdition = function (event) {
          this.statusChange(event);
                //SetEditables and add class
            $(all)
                .attr('contenteditable', 'false')
                .removeClass('editable');
        }

        this.acceptChanges = function (event) {
            this.revertEdition(event);
        }

        //calls
        //--------- VOLMARG tutaj moze nie dzialac
        // bylo this.getData();


    });

    //--------------------------------------------------------------------------------------------------\\
    //                                   SERVICES                                                       \\
    //--------------------------------------------------------------------------------------------------\\

    //-----------------------------------------------------------\\
    //Service for filtering and watcher on variable for filtering\\
    //-----------------------------------------------------------\\
    pageBuilding.service('dataFilter', function ($rootScope) {

        this.getButtonName = function (event) {
            var clickedButton = event.target;
            var filterBy = $(clickedButton).text();
            $rootScope.filterRoot = filterBy;
        };
    });

})();
