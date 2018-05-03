<?php
#includes of libs
include_once 'Model/databaseConnection.php';
include_once 'View/partials.php';

  class subElements{
    public function catsSelection($iteratorId){
      $db=new askDatabase();

      #get products ID first
      $sql="SELECT `term_id` FROM `wp_term_taxonomy` WHERE `taxonomy`='product_cat';";
      $id=$db->getDataFromDatabase($sql);
      $ids=$id[0];

      echo '<select class="form-control" id="cat_'.$iteratorId.'" data-id="kategoria">';
        #now for each id get corresponding category
        foreach($ids as $id){
          $sql="SELECT `name` FROM `wp_terms` WHERE `term_id`='$id[0]';";
          $ide=$db->getDataFromDatabase($sql);

          echo '<option value="'.$id[0].'">';
            echo $ide[0][0][0];
          echo '</option>';

        }
      echo '</select>';
    }
  }


  class buildInterface extends subElements{

    public function buildTable($productsData){
      #var_dump($productsData);
      $parts=new viewPartials();

      foreach($productsData as $id=>$product){

        //$secondImage -> 4
        echo '<tr>';
          $parts->images($id,$product,$_GET["page"]);

          echo "<td id='tit_$id'         data-id='tit'>$product[0]</td>";
          echo "<td id='desc_$id'        data-id='desc' style='width:400px'>$product[1]</td>";

          echo '<td>
                  <div class="form-group">
                        <textarea class="form-control" rows="5" id="smallInfo_'.$id.'" data-id="smallInfo" placeholder="To jest obszar nad \'Dodaj do koszyka\', tutaj można stawić np. info o czasie dostawy."></textarea>
                  </div>
                </td>';

		   .
		   .
		   .
		   .
          echo '<td>';
            $this->catsSelection($id);
          echo '</td>';

          echo '<td id="edit_'.$id.'"    data-id="edit" ng-click="dataCtrl.editContent($event)" data-number="'.$id.'" class="button">✎</td>';
          echo '<td id="remove_'.$id.'"  data-id="remove" ng-click="dataCtrl.remove($event)" data-number="'.$id.'" class="button">✖</td>';
          echo '<td id="accept_'.$id.'"  data-id="accept" ng-click="dataCtrl.acceptChanges($event)" data-number="'.$id.'" class="button">✓</td>';
          echo '<td id="add_'.$id.'"  data-id="addDB"  data-number="'.$id.'" class="button"><button class="btn-primary btn-sm" style="width: 103px;font-size: 13px;" ng-click="dataCtrl.insertOneDb($event)">Dodaj</button></td>';
        echo '</tr>';
      }

    }


  }


?>
