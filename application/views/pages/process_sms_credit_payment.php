      <?php

        if(is_array($curr_health_facility_arr)){
          foreach($curr_health_facility_arr as $row){
            $health_facility_id = $row->id;
            $health_facility_name = $row->name;
            $health_facility_logo = $row->logo;
            $health_facility_structure = $row->facility_structure;
            $health_facility_email = $row->email;
            $health_facility_phone = $row->phone;
            $health_facility_country = $row->country;
            $health_facility_state = $row->state;
            $health_facility_address = $row->address;
            $health_facility_table_name = $row->table_name;
            $health_facility_date = $row->date;
            $health_facility_time = $row->time;
            $health_facility_slug = $row->slug;
            $color = $row->color;
          }
          if(is_null($health_facility_logo)){
            $no_logo = true;
            
            $data_url_img = "<img style='display:none;' id='facility_img' width='100' height='100' class='round img-raised rounded-circle img-fluid' avatar='".$health_facility_name."' col='".$color."'>";
            
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
      ?>

           <!-- End Navbar -->
      <?php
         if(is_null($health_facility_logo)){
          echo $data_url_img; 
         }else{ 
          ?> 
        <img src="<?php echo base_url('assets/images/'.$health_facility_logo); ?>" style="display: none;" alt="" id="facility_img">
        <?php } ?>
      <script>
         function addCommas(nStr)
  {
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }
       </script>
      <div class="spinner-overlay">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
        <p>Processing.... Please Do Not Leave Or Reload This Page</p>
      </div>
      <div class="content">
        <div class="container-fluid">
          
          <div class="row">
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
      </footer>
      <?php 

      ?>
    
      <script>
        window.onbeforeunload = function() {
            return "If You Reload Or Leave This Page You Could Lose Your Money!";
        }
        $(document).ready(function() {
         
          var health_facility_logo = $("#facility_img");
          var logo_src = health_facility_logo.attr("src");
          var logo_src_substr = logo_src.substring(0,4);
          
          if(logo_src_substr !== "data"){
            var img_data_url = $("#facility_img").attr("src");
            var company_logo = {
             src:img_data_url,
              w: 80,
              h: 50
            };
          }else{
            var img_data_url = $("#facility_img").attr("src");
            var company_logo = "";
          }
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/verify_sms_credit_payment/?reference='.$_GET["reference"]); ?>";
          $(".spinner-overlay").show();
          $.ajax({
              url : url,
              type : "POST",
              responseType : "json",
              dataType : "json",
              data : "show_records=true",
              success : function(response){
                $(".spinner-overlay").hide();
                if(response.success && response.url != "" && response.amount != ""){
                  var url = response.url;
                  var amount = response.amount;
                  $.notify({
                  message:"You Have Successfully Credited Your SMS Account With " + amount
                  },{
                    type : "success"  
                  });
                  setTimeout(function () {
                    window.location.assign(url);
                  }, 1500);
                  
                }else{
                  swal({
                    title: 'Error!',
                    text: "Sorry Something Went Wrong",
                    type: 'warning',
                    
                  })
                }
              },
              error: function () {
                $(".spinner-overlay").hide();
                $.notify({
                message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
                },{
                  type : "danger"  
                });
              }
            
          })
           
    
        });
      </script>
    </div>
  </div>
  <!--   Core JS Files   -->
 