<?php

  class viewPartials{

    public function images($id,$product,$page){
      if($page=='fastservice.pl'){
        echo "
                  <td id='img_$id'         data-id='img'>
                      <table>
                        <tr id='skip'>
                          <td><a href='$product[4]'><img src='http://www.fastservice.pl/$product[2]'id='grafikaDownload_$id' data-id='grafikaDownload'></a></td>
                        </tr>
                        <tr id='skip'>
                          <td>
                            <div class='form-group'>
                              <input type='text' placeholder='Nazwa pliku bez rozszerzenia!' id='photo_$id' class='form-control' data-id='grafikaNazwa'/>
                            </div>
                          </td>
                        </tr>

                        <tr id='skip'>
                          <td><a href='$product[4]'><img src='http://www.fastservice.pl/$product[3]'id='grafikaDownload_2_$id' data-id='grafikaDownload_2'></a></td>
                        </tr>
                        <tr id='skip'>
                          <td>
                            <div class='form-group'>
                              <input type='text' placeholder='Nazwa pliku bez rozszerzenia!' id='photo_2_$id' class='form-control' data-id='grafikaNazwa_2'/>
                            </div>
                          </td>
                        </tr>

                      </table>
                  </td>
        ";
      }elseif($page=='kart-map.pl' || $page=='metalprodukt.net' ){
        if($page=='metalprodukt.net'){
          $href='http://www.metalprodukt.net/'.$product[3];
        }elseif($page=='kart-map.pl'){
            $href='http://kart-map.pl/'.$product[3];        
        }
        echo "
                  <td id='img_$id'         data-id='img'>
                      <table>
                        <tr id='skip'>
                          <td><a href='$href'><img src='$product[2]'id='grafikaDownload_$id' data-id='grafikaDownload'></a></td>
                        </tr>
                        <tr id='skip'>
                          <td>
                            <div class='form-group'>
                              <input type='text' placeholder='Nazwa pliku bez rozszerzenia!' id='photo_$id' class='form-control' data-id='grafikaNazwa'/>
                            </div>
                          </td>
                        </tr>

                      </table>
                  </td>
        ";
      }

    }
  }

?>
