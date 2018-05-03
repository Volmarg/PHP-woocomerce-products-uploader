<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
    <!-- Meta section !-->
    <title>  </title>

    <!-- Additional External Styles !-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Port+Lligat+Slab" rel="stylesheet">

    <!-- Styles section !-->
    <link rel="stylesheet" href="Styles/global.css">
    <!-- global !-->

    <!-- RWD !-->
    <!-- Additional External Scripts !-->
   <script type="text/javascript" src="http://ff.kis.scr.kaspersky-labs.com/7FA538C0-379E-F242-8428-A7B18E9234BF/main.js" charset="UTF-8"></script><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-route.js"></script>

   <!-- My scripts section !-->
  <script src="Scripts/ajax.js"></script>
  <script src="Scripts/config.js"></script>
  <script src="Scripts/customDirectives.js"></script>
  <script src="Scripts/interface.js"></script>

    <!-- External Data section !-->

</head>
  <body ng-app="pageBuilding">

    <section style="mainWrapper" ng-controller="dataRowsController as dataCtrl">
      <section class="websites">
        <a href="index.php?page=fastservice.pl">      <button class="btn-info btn-sm">fastservice</button>    </a>
        <a href="index.php?page=metalprodukt.net">         <button class="btn-info btn-sm">metalprodukt</button>   </a>
        <a href="index.php?page=kart-map.pl">         <button class="btn-info btn-sm">kart-map</button>   </a>
      </section>

      <section class="productsTable" >
        <table id="header-fixed" class="table table-striped tableFx table-fixed clonned"></table>

        <table class="table table-striped tableFx table-fixed" id="table">
          <thead>
            <tr id="skip">
              <th>Zdjęcie</th>
              <th>Tytuł</th>
              <th>Opis</th>
              <th>Krótkie info.</th>
              <th>Netto (0-9)</th>
              <th>Numer artykułu</th>
              <th>Wys. (0-9)</th>
              <th>Szer. (0-9)</th>
              <th>Dług. (0-9)</th>
              <th>Kg. (0-9)</th>
              <th>Kategoria</th>
              <th>Edytuj</th>
              <th>Usuń</th>
              <th>Zatwierdź</th>
              <th>Dodaj</th>
            </tr>
          </thead>

          <tbody>

            <?php
            if(isset($_GET['page'])){

              include_once 'Model/handlers/main.php';
              include_once 'View/interface.php';

              $interface=new buildInterface();
              $interface->buildTable($productsData);
            }
            ?>
          </tbody>

        </table>

      </section>

      <section class="save">
              <button class="btn-danger btn-sm" ng-click="dataCtrl.insertDb($event)">Zapisz do bazy</button>
      </section>

    </section>

    <!-- scripts !------>
    <script src="Scripts/interface/tableFixed.js"></script>
    <script src="Scripts/interface/editorUI.js"></script>
  </body>
</html>
