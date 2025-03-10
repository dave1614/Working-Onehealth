<style>
  tr{
    cursor: pointer;
  }
  body {
  
}

</style>
      <!-- End Navbar -->
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
            
          }else{
            $data_url_img = '<img src="'.base_url('assets/images/'.$health_facility_logo).'" style="display: none;" alt="" id="facility_img">';
          }
          $admin = false;
          $user_id = $this->onehealth_model->getUserIdWhenLoggedIn();
        }
        echo $data_url_img;
      ?>
<script> 

  String.prototype.trunc = String.prototype.trunc ||
      function(n){
          return (this.length > n) ? this.substr(0, n-1) + '&hellip;' : this;
      };
  var user_info = [];
  var edit_clinic_initiation_code = "";
  var edit_ward_initiation_code = "";
  var selected_drugs = [];
  var selected_drugs_edit_clinic = [];
  var selected_drugs_edit_ward = [];
  var patient_facility_id = "";
  var registered_patient = false;
  var patient_disp_type = "normal";

  function getTodayCurrentFullDate(){
    var date = new Date();
    date.setDate(date.getDate() + 1);

    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    let year = date.getFullYear();

    return `${year}-${month}-${day}`
  }

   function getYesterdayCurrentFullDate(){
    var date = new Date();
    // date.setDate(date.getDate() - 1);

    // let day = date.getDate();
    // let month = date.getMonth() + 1;
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    let year = date.getFullYear();

    return `${year}-${month}-${day}`
  }

  function goBackSelectDrugs (elem,evt) {
    user_info = [];
    selected_drugs = [];

    if(!registered_patient){
      $("#select-drugs-card .card-title").html("");
      
      $("#enter-patient-data-modal").modal("show");
      $("#perform-functions-card").show();
      
      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
    }else{
      $("#select-drugs-card .card-title").html("");
      
      $("#perform-action-on-patient-modal").modal("hide");
      // $("#registered-patients-card").show();

      if(patient_disp_type == "normal"){
        $("#registered-patients-card").show();
        
      }else{
        $("#searched-patients-card").show();
        
      }
      
      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
    }
  }

  function selectDrugsForPatient (elem,evt) {
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_patient_info_for_drugs_selection_pharmacy') ?>";

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&patient_facility_id="+patient_facility_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){ 
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form_pharmacy') ?>";
          var form_data = response.messages;
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success && response.messages != "" && response.user_info != ""){
                var messages = response.messages;
                var patient_name = response.patient_name;
                user_info = response.user_info;
                registered_patient = true;

                $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
                $("#select-drugs-card .card-body").html(messages);
                $("#perform-action-on-patient-modal").modal("hide");
                // $("#registered-patients-card").hide();
                if(patient_disp_type == "normal"){
                  $("#registered-patients-card").hide();
                  
                }else{
                  $("#searched-patients-card").hide();
                  
                }
                $("#select-drugs-card #select-drugs-table").DataTable();
                $("#select-drugs-card").show();
                $("#select-drugs-proceed-btn").show("fast");
              }else if(response.expired){
                $.notify({
                message:"Date Entered Must Be In The In The Future"
                },{
                  type : "warning"  
                });
              }else{
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
              }
            },error : function () {
              $(".spinner-overlay").hide();
              swal({
               title: 'Ooops',
                text: "Something Went Wrong. Please Check Your Internet Connection",
                type: 'error'
              })
            } 
          });
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong"
        },{
          type : "danger"  
        });
      } 
    }); 
  }

  function userTypeChangedEdit (elem,evt) {
    elem = $(elem);
    var val = elem.val();
    console.log(val);
    if(val == "fp"){
      $("#edit-patient-info-card #code-form-group").hide();
    }else if(val == "pfp"){
      $("#edit-patient-info-card #code-form-group").show();
    }else if(val == "nfp"){
      $("#edit-patient-info-card #code-form-group").show();
    }
  }

  function submitEditPatientInfoForm(elem,evt){
    elem = $(elem);
    evt.preventDefault();
    $(".spinner-overlay").show();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_edit_patint_info_form') ?>";

    var form_data = elem.serializeArray();
    form_data = form_data.concat({
      "name" : "patient_facility_id",
      "value" : patient_facility_id
    })

    console.log(form_data)

    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : form_data,
      success : function (response) {
        $(".spinner-overlay").hide();
       
        if(response.success){  
          $.notify({
          message:"Patient Type Edited Successfully"
          },{
            type : "success"  
          });
          setTimeout(function () {
            document.location.reload();
          }, 1000);
        }else if(response.invalid_code){
          $.notify({
          message:"Invalid Code Entered"
          },{
            type : "warning"  
          }); 
        }else{
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "warning"  
          }); 
        }

        // $('.table').DataTable();
      },
      error : function () {
        $(".spinner-overlay").hide();
        
        $.notify({
        message:"Sorry something went wrong"
        },{
          type : "danger"  
        });
      } 
    }); 
  }


  

  function editPatientInfo(elem,evt){
    if(patient_facility_id != ""){
      console.log(patient_facility_id)
     
      var get_tests_url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/'.$fourth_addition.'/load_edit_patient_info_form') ?>";
    
   
      $(".spinner-overlay").show();

      $.ajax({
        url : get_tests_url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "patient_facility_id="+patient_facility_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          var messages = response.messages;
          // console.log(messages)
          if(messages !== ""){  
            // $("#registered-patients-card").hide();
            if(patient_disp_type == "normal"){
              $("#registered-patients-card").hide();
              
            }else{
              $("#searched-patients-card").hide();
              
            }
            $("#perform-action-on-patient-modal").modal("hide"); 
            $("#edit-patient-info-card .card-body").html(messages);
            $("#edit-patient-info-card .my-select").selectpicker();
            $("#edit-patient-info-card").show();
          }

          // $('.table').DataTable();
        },
        error : function () {
          $(".spinner-overlay").hide();
          
          $.notify({
          message:"Sorry something went wrong"
          },{
            type : "danger"  
          });
        } 
      }); 
     
    }
  }

  function performActionOnPatient (id, patient_full_name) {
    patient_disp_type = "normal";
    if(id != ""){
      
      patient_facility_id = id;
      $("#perform-action-on-patient-modal .modal-title").html("Choose Action To Perform On " + patient_full_name + ":")
      $("#perform-action-on-patient-modal").modal("show");
    }
  }

  function performActionOnPatient2 (elem,evt,id) {
    patient_disp_type = "search";
    var patient_name = elem.getAttribute('data-patient-name');

    patient_full_name = patient_name;
    patient_facility_id = id;
    $("#perform-action-on-patient-modal .modal-title").html("Choose Action To Perform On " + patient_full_name + ":")
    $("#perform-action-on-patient-modal").modal("show");
    
  }


  function goBackFromEditPatientInfoCard (elem,evt) {
    // $("#registered-patients-card").show();
    // $("#perform-action-on-patient-modal").modal("show"); 
    $("#perform-action-on-patient-modal").modal("show"); 
    if(patient_disp_type == "normal"){
      $("#registered-patients-card").show();
      
    }else{
      $("#searched-patients-card").show();
      
    }
    
    
    $("#edit-patient-info-card").hide();
  }

  function goBackRegisteredPatientsCard (elem,evt) {
    $("#perform-functions-card").show("fast");
    $("#registered-patients-card").hide("fast");
  }

  function transcribePrescriptionsHospital(elem,evt){
    swal({
      title: 'Choose Action',
      text: "Do You Want Select Drugs For: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Registered Patients',
      cancelButtonText: 'Over The Counter Patients'
    }).then(function(){
      // var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_all_registered_patients_pharmacy'); ?>";
      // $(".spinner-overlay").show();
      // $.ajax({
      //   url : url,
      //   type : "POST",
      //   responseType : "json",
      //   dataType : "json",
      //   data : "show_records=true",
      //   success : function (response) {
      //     console.log(response)
      //     $(".spinner-overlay").hide();
      //     if(response.success && response.messages != ""){
      //       var messages = response.messages;
      //       $("#perform-functions-card").hide("fast");
      //       $("#registered-patients-card .card-body").html(messages);
      //       $("#registered-patients-card #registered-patients-table").DataTable();
      //       $("#registered-patients-card").show("fast");
      //     }else{
      //       $.notify({
      //       message:"Sorry Something Went Wrong."
      //       },{
      //         type : "warning"  
      //       });
      //     }
          
      //   },
      //   error: function (jqXHR,textStatus,errorThrown) {
      //     $(".spinner-overlay").hide();
      //     $.notify({
      //     message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
      //     },{
      //       type : "danger"  
      //     });
      //   }
      // });

      // var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_all_registered_patients_pharmacy'); ?>";
      // $("#perform-functions-card").hide("fast");
      // var html = `<p class="text-primary">Click Patient To Perform Action.</p><div class="table-div material-datatables table-responsive" style=""><table class="table table-test table-striped table-bordered nowrap hover display" id="registered-patients-table" cellspacing="0" width="100%" style="width:100%"><thead><tr><th>Id</th><th class="sort">#</th><th class="no-sort">Patient Name</th><th class="no-sort">User Name</th><th class="no-sort">Registration Number</th><th class="no-sort">Gender</th><th class="no-sort">Age</th><th class="no-sort">User Type</th><th class="no-sort">Date Registered</th><th class="no-sort">Time Registered</th><th class="no-sort">Registered By</th></tr></thead></table></div>`;

     
      // $("#registered-patients-card .card-body").html(html);
      

      // var table = $("#registered-patients-card #registered-patients-table").DataTable({
        
      //   initComplete : function() {
      //     var self = this.api();
      //     var filter_input = $('#registered-patients-card .dataTables_filter input').unbind();
      //     var search_button = $('<button type="button" class="p-3 btn btn-primary btn-fab btn-fab-mini btn-round"><i class="fa fa-search"></i></button>').click(function() {
      //         self.search(filter_input.val()).draw();
      //     });
      //     var clear_button = $('<button type="button" class="p-3 btn btn-danger btn-fab btn-fab-mini btn-round"><i class="fa fa fa-times"></i></button>').click(function() {
      //         filter_input.val('');
      //         search_button.click();
      //     });

      //     $(document).keypress(function (event) {
      //         if (event.which == 13) {
      //             search_button.click();
      //         }
      //     });

      //     $('#registered-patients-card .dataTables_filter').append(search_button, clear_button);
      //   },
      //   'processing': true,
      //    "ordering": true,
      //   'serverSide': true,
      //   'serverMethod': 'post',
      //   'ajax': {
      //      'url': url
      //   },
      //   "language": {
      //     processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
      //   },
      //   search: {
      //       return: true,
      //   },
      //   'columns': [
      //     { data: 'id' },
      //     { data: 'index' },
      //     { data: 'patient_name' },
          
      //     { data: 'user_name' },
      //     { data: 'registration_num' },
      //     { data: 'gender' },
      //     { data: 'age' },
      //     { data: 'user_type' },
      //     { data: 'date_registered' },
      //     { data: 'time_registered' },
      //     { data: 'registered_by' },
          
      //   ],
      //   'columnDefs': [
      //     {
      //         "targets": [0],
      //         "visible": false,
      //         "searchable": false,

      //     },
          
      //     {
      //       orderable: false,
      //       targets: "no-sort"
      //     }
      //   ],
      //   order: [[1, 'desc']]
      // });
      // $('#registered-patients-card tbody').on( 'click', 'tr', function () {
      //     // console.log( table.row( this ).data() );
      //     var data = table.row( this ).data();
      //     // var patient_name = data.title + " " + data.first_name + " " + data.last_name;
      //     performActionOnPatient(data.id,data.patient_name)
          
      // } );
      // $("#registered-patients-card").show("fast");


      elem = $(elem);
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_all_registered_patients_pharmacy'); ?>";
          
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true",
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            var messages = response.messages;
            
            $("#perform-functions-card").hide("fast");
            $("#registered-patients-card .card-body").html(messages);
            // $('.my-select').selectpicker();
            $("#registered-patients-card #registered-patients-table").DataTable();
            $("#registered-patients-card").show();

            
          }
          else{
           $.notify({
            message:"Sorry Something Went Wrong"
            },{
              type : "warning"  
            });
          }
        },
        error: function (jqXHR,textStatus,errorThrown) {
          $(".spinner-overlay").hide();
          $.notify({
          message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
          },{
            type : "danger"  
          });
        }
      });
    }, function(dismiss){
      if(dismiss == 'cancel'){
        registered_patient = false;
        $("#enter-patient-data-modal").modal("show");
      }
    });  
  }
  
  function goBackPerformFunctions (elem,evt) {
    $("#main-card").show();
    $("#perform-functions-card").hide();
  }

  

  function goBackSelectedInfoDrugs(elem,evt){
    $("#select-drugs-card").show();
    $("#select-drugs-proceed-btn").show("fast");
    $("#select-drugs-proceed-btn-2").hide("fast");
    $("#selected-drugs-info-card").hide();    
  }



  function calculatePrescription (dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i) {
    // console.log(main_store_quantity + " : " + dispensary_quantity);
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;

      quantity = Math.round(quantity * 100) / 100;



      if(!isNaN(quantity)){
        var price = selected_drugs[i].price;
        price = Math.round(price * 100) / 100;
        price = parseFloat(price).toFixed(2);
        if(price != null){
          var total_price = price * quantity;
          var html = "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
          html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
          html += "<p>Total Price: " + addCommas(total_price) +"</p>";
          if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
            var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
            console.log(total_store_quantity)
            if(quantity > total_store_quantity){
              html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
            }
          }
          $("#prescription-data-div .form-row").eq(i).find(".display-div").html(html);
        }
      }
    }
  }

  function calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,i) {
    // console.log(dosage)
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      var ret = {};
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;
      quantity = Math.round(quantity * 100) / 100;

      if(!isNaN(quantity)){
        var price = selected_drugs[i].price;
        price =  Math.round(price * 100) / 100;
        if(price != null){
          var total_price = price * quantity;
          ret = {
            'quantity' : quantity,
            'total_price' : total_price
          };
        }
      }

      return ret;
    }
  }

  function selectDrugsProceed2 () {
    var proceed = false;

    for(var i = 0; i < selected_drugs.length; i++) { 
      var is_poison = selected_drugs[i].is_poison; 
      if(is_poison == 1){ 
        proceed = false;
        break; 
      }else{
        proceed = true;
      }
    }

    if(!proceed){
      if(user_info.clinician !== ""){
        proceed = true;
      }
    }

    console.log(proceed)
    
    // evt.preventDefault();
    if(proceed){
      var me = $("#prescription-data-div");
      var form_data = {
        drugs_info : []
      };
      
      var drugs_info = [];
      var num = 0;
      var sum = 0;
      var total_quantity = 0;
      me.find(".form-row").each(function(index, el) {
        var el = $(el);
        var id = el.attr("data-id");
        var price = selected_drugs[index].price;
        var dosage = el.find(".dosage").val();
        var frequency_num = el.find(".frequency_num").val();
        var frequency_time = el.find(".frequency_time").val();
        var duration_num = el.find(".duration_num").val();
        var duration_time = el.find(".duration_time").val();

        var obj = {
          id : id,
          frequency_num : frequency_num,
          frequency_time : frequency_time,
          duration_num : duration_num,
          duration_time : duration_time,
          dosage : dosage,
          price : price
        };

        drugs_info[index] = obj;

        if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
          num++;
          var prescription_obj = calculatePrescription2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
          // console.log(prescription_obj)
          if(prescription_obj != {}){
            var quantity = prescription_obj.quantity;
            var total_price = prescription_obj.total_price;

            total_quantity += quantity;
            sum += total_price;
          }
        }

      });

      form_data['drugs_info'] = drugs_info;

      if(registered_patient){
        user_info.patient_facility_id = patient_facility_id;
      }
      
      $.each(user_info, function(index, val) {
         form_data[index] = val;
      });
      console.log(form_data);
      // console.log(JSON.stringify(form_data))

      if(num > 0){
        swal({
          title: 'Proceed?',
          text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Proceed?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Proceed'
        }).then((result) => {
          
          $(".spinner-overlay").show();
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_drugs_selected_pharmacy'); ?>";
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : form_data,
            success : function (response) {
              console.log(response)
              $(".spinner-overlay").hide();
              if(response.success){
                var text = "Prescription Made Successfully"
                if(response.nfp){
                  text += ". This Patient Is A None Fee Paying Patient. Proceed To Dispensing And Dispatchig Officer."
                }
                
                $.notify({
                message : text
                },{
                  type : "success"  
                });

                document.location.reload();
              }else{
                swal({
                 title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'error'
                })
              }
            },error : function () {
              $(".spinner-overlay").hide();
              swal({
               title: 'Ooops',
                text: "Something Went Wrong",
                type: 'error'
              })
            } 
          });
        }); 
      }else{
        swal({
          title: 'Warning! ',
          text: "You Must Enter Complete Prescription Info For At Least One Selected Drug To Proceed",
          type: 'error'
        })
      }
    }else{
      $("#enter-clinician-name-modal").modal("show");
    }
  }

  function dosageEvent(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceed2()
    }
  }

  function frequencyEvent1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceed2()
    }
  }

  function frequencyEvent2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
  }

  function durationEvent1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceed2()
    }
  }

  function durationEvent2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescription(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);   
  }

  function selectDrugsProceed(elem,evt) {
    console.log(selected_drugs);
    console.log(user_info);
    if(selected_drugs.length > 0){
      var selected_drugs_info_html = "<div class='container-fluid'>";
      var j = 0;
      <?php 
        $attr = array('id' => 'prescription-data-form');
      ?>
      selected_drugs_info_html += "<div id='prescription-data-div'>";
      for(var i = 0; i < selected_drugs.length; i++){
        var id = selected_drugs[i].id;
        var price = selected_drugs[i].price;
        var brand_name = selected_drugs[i].brand_name;
        var generic_name = selected_drugs[i].generic_name;
        var formulation = selected_drugs[i].formulation;
        var strength = selected_drugs[i].strength;
        var strength_unit = selected_drugs[i].strength_unit;
        var main_store_quantity = selected_drugs[i].main_store_quantity;
        var dispensary_quantity = selected_drugs[i].dispensary_quantity;
        var unit = selected_drugs[i].unit;

        j++;
        selected_drugs_info_html += j + ". ";
        
        
        selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";
        selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
        selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
        selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
        selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
        selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-1 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
        selected_drugs_info_html += "<input class='form-control dosage' type='number' onkeyup='dosageEvent(this,event,"+i+")'>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
        selected_drugs_info_html += "<input class='form-control frequency_num' type='number' onkeyup='frequencyEvent1(this,event,"+i+")'>";
        selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "<option value='minutely' selected>Minutely</option>";
        selected_drugs_info_html += "<option value='hourly'>Hourly</option>";
        selected_drugs_info_html += "<option value='daily'>Daily</option>";
        selected_drugs_info_html += "<option value='weekly'>Weekly</option>";
        selected_drugs_info_html += "<option value='monthly'>Monthly</option>";
        selected_drugs_info_html += "<option value='yearly'>Yearly</option>";
        selected_drugs_info_html += "<option value='nocte'>Nocte</option>";
        selected_drugs_info_html += "<option value='stat'>Stat</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group'>";
        selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
        selected_drugs_info_html += "<input class='form-control duration_num' type='number' onkeyup='durationEvent1(this,event,"+i+")'>";
        selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEvent2(this,event,"+i+")'>";
        selected_drugs_info_html += "option value='minutes' selected>Minutes</option>";
        selected_drugs_info_html += "<option value='hours'>Hours</option>";
        selected_drugs_info_html += "<option value='days'>Days</option>";
        selected_drugs_info_html += "<option value='weeks'>Weeks</option>";
        selected_drugs_info_html += "<option value='months'>Months</option>";
        selected_drugs_info_html += "<option value='years'>Years</option>";
        selected_drugs_info_html += "</select>";
        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

        selected_drugs_info_html += "</div>";

        selected_drugs_info_html += "</div>";
        
      }
      selected_drugs_info_html += "</div>";

      $("#select-drugs-card").hide();
      $("#select-drugs-proceed-btn").hide("fast");
      $("#select-drugs-proceed-btn-2").show("fast");
      $("#selected-drugs-info-card .card-title").html("Enter prescription Details For: " + user_info.full_name);
      $("#selected-drugs-info-card .card-body").html(selected_drugs_info_html);
      $("#selected-drugs-info-card").show();
      selected_drugs_info_html += "</div>";
    }else{
      swal({
       title: 'Sorry',
        text: "You Must Select At Least One Drug To Proceed",
        type: 'error'
      })
    }
  }

  function checkBoxEvt (elem,evt) {
    elem = $(elem);
    var id = elem.attr("data-id");
    var price = elem.attr("data-price");
    var brand_name = elem.attr("data-brand-name");
    var generic_name = elem.attr("data-generic-name");
    var formulation = elem.attr("data-formulation");
    var strength = elem.attr("data-strength");
    var strength_unit = elem.attr("data-strength-unit");
    var main_store_quantity = elem.attr("data-main-store-quantity");
    var dispensary_quantity = elem.attr("data-dispensary-quantity");
    var is_poison = elem.attr("data-is-poison");
    var unit = elem.attr("data-unit");
    

    var data = {
      id : id,
      price : price,
      brand_name : brand_name,
      generic_name : generic_name,
      formulation : formulation,
      strength : strength,
      strength_unit : strength_unit,
      main_store_quantity : main_store_quantity,
      dispensary_quantity : dispensary_quantity,
      unit : unit,
      is_poison : is_poison
    };
    if(elem.prop('checked')){
      isChecked = true;
    }else{
      isChecked = false;
    }

    if(isChecked){
      selected_drugs.push(data);
    }else{
      var index = selected_drugs.map(function(obj, index) {
          if(obj.id === id) {
              return index;
          }
      }).filter(isFinite);
      if(index > -1){
        selected_drugs.splice(index, 1);
      }
    }
  }

  function transcribePrescriptions(elem,evt){
    $("#enter-patient-data-modal").modal("show");
  }

  function performFunctions (elem,evt) {
    $("#main-card").hide();
    $("#perform-functions-card").show();
  }

  function goBackErrorRegister (elem,evt) {
    $("#perform-functions-card").show();
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
  }

  function goBackAddNewErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-card").hide();
    $("#add-new-error-btn").show("fast");

  }

  function addNewError (elem,evt) {
    $("#error-register-card").hide();
    $("#add-new-error-btn").hide("fast");
    $("#add-new-error-card").show();
  }

  function errorRegister (elem,evt) {
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pharmacy_error_register'); ?>";
    $(".spinner-overlay").show();
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "get_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#error-register-card .card-body").html(messages)
          $("#error-register-card").show();
          $("#add-new-error-btn").show("fast");
          $("#error-register-table").DataTable();
        }
      },error : function () {
        $(".spinner-overlay").hide();
        swal({
         title: 'Ooops',
          text: "Something Went Wrong",
          type: 'error'
        })
      }
    });    
  }

  function goBackEditErrorRegister (elem,evt) {
    $("#error-register-card").show();
    $("#add-new-error-btn").show("fast");
    
    $("#edit-new-error-card").hide();
  }

  function loadErrorRegisterInfo(elem,evt,id){
    if(id !== ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/get_pharmacy_error_register_info_for_edit'); ?>";
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "get_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#error-register-card").hide();
            $("#add-new-error-btn").hide("fast");
            $("#edit-new-error-form").attr("data-id",id);
            var event = response.messages.event;
            var action = response.messages.action;
            var remedied = response.messages.remedied;

            $("#edit-new-error-card #event").val(event);
            $("#edit-new-error-card #action").val(action);
            if(remedied == 1){
              $("#edit-new-error-card #yes").prop("checked",true);
            }else{
              $("#edit-new-error-card #yes").prop("checked",false);
            }
            
            $("#edit-new-error-card").show();
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        }
      });   
    }
  }

  function goBackMainStoreCard () {
    $("#perform-functions-card").show("slow");
    $("#main-store-card").hide("slow");
  }

  function goBackViewDrugMainStoreCard () {
    $("#main-store-card").show("slow");
    
    $("#view-drug-main-store-card").hide("slow");
    
  }

  function viewDrugMainStore (elem,event,id) {
    if(id != ""){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_drug_main_store'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#main-store-card").hide();
            $("#view-drug-main-store-card .card-body").html(messages);
            $("#view-drug-main-store-card").show();
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function viewStoreRecords (elem,evt) {
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_main_store'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#main-store-card .card-body").html(messages);
          $("#main-store-card #main-store-table").DataTable();
          $("#main-store-card").show();
          
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
  }

  function poisonRegister(elem,evt){
    $(".spinner-overlay").show();
    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/view_pharmacy_poison_register'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "",
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#poison-register-card .card-body").html(messages);
          $("#poison-register-card #poison-register-table").DataTable();
          $("#poison-register-card").show();
          
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
  }

  function goBackPoisonRegisterCard (elem,evt) {
    $("#perform-functions-card").show();
    $("#poison-register-card").hide();
  }

  function searchPatients(elem, evt){
    $("#search-patient-modal").modal("show");
  }

  function goBackFromSearchedPatientsCard(){
    $("#search-patient-modal").modal("show");
    $("#perform-functions-card").show();
    $("#searched-patients-card").hide();
  }

  

  function selectTimeRangeClinicPatients(elem,event){
    elem = $(elem);
    var start_date = elem.parent().find('.start-date').val();
    var end_date = elem.parent().find('.end-date').val();
    

    console.log(start_date)
    console.log(end_date)
    
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_transcribed_clinics_drugs'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&start_date="+start_date+"&end_date="+end_date,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          var messages = response.messages;
          $("#clinic-prescriptions-card .card-body").html(messages);
          $("#clinic-prescriptions-card #clinic-prescriptions-table").DataTable();
          
          
        }
        else{
         swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function viewClinicPrescriptions(){
    

    var start_date = getYesterdayCurrentFullDate();
    var end_date = getTodayCurrentFullDate();
    console.log(start_date + " " + end_date)
    $(".spinner-overlay").show();

    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_transcribed_clinics_drugs'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&start_date="+start_date+"&end_date="+end_date,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#clinic-prescriptions-card .card-body").html(messages);
          $("#clinic-prescriptions-card #clinic-prescriptions-table").DataTable();
          $("#clinic-prescriptions-card").show();
          
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
    
    
  }

  function goBackFromclinicPrescriptionsCard(elem, evt) {
    $("#perform-functions-card").show();     
    $("#clinic-prescriptions-card").hide();
  }

  function loadPendingClinicPatientsInfo(initiation_code){
    if(initiation_code != ""){
      edit_clinic_initiation_code = initiation_code
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_transcribed_clinics_drugs_info'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#clinic-prescriptions-card").hide();
            $("#clinic-prescriptions-info-card .card-body").html(messages);
            
            

            var drugs_info = response.drugs_info
            console.log(drugs_info)

            selected_drugs_edit_clinic = drugs_info

            if(drugs_info.length > 0){
              var selected_drugs_info_html = "<div class='container-fluid' style='margin-top: 40px;'>";
              var j = 0;
              <?php 
                $attr = array('id' => 'prescription-edit-data-form');
              ?>
              selected_drugs_info_html += "<div id='prescription-edit-data-div'>";
              for(var i = 0; i < drugs_info.length; i++){

                var message = drugs_info[i].message;

                j++;
                // selected_drugs_info_html += j + ". ";

                

                var id = drugs_info[i].id;
                var generic_name = drugs_info[i].generic_name;

                selected_drugs_info_html += `<div class='rol-col' id='rol-col-${id}'>`;

                selected_drugs_info_html += '<button onclick="deleteSelectedClinicDrug('+i+','+id+', `'+generic_name+'`, `'+initiation_code+'`)" title="Delete this selected drug" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
                if(message == 'okay'){

                  
                  var price = drugs_info[i].price;
                  var brand_name = drugs_info[i].brand_name;
                  
                  var formulation = drugs_info[i].formulation;
                  var strength = drugs_info[i].strength;
                  var strength_unit = drugs_info[i].strength_unit;
                  var main_store_quantity = drugs_info[i].main_store_quantity;
                  var dispensary_quantity = drugs_info[i].dispensary_quantity;
                  var unit = drugs_info[i].unit;

                  var dosage = drugs_info[i].dosage;
                  var frequency_num = drugs_info[i].frequency_num;
                  var frequency_time = drugs_info[i].frequency_time;
                  var duration_num = drugs_info[i].duration_num;
                  var duration_time = drugs_info[i].duration_time;
                  var quantity = drugs_info[i].quantity;

                  quantity = (duration_num / frequency_num);
                  quantity = dosage * quantity;

                  quantity = Math.round(quantity * 100) / 100;

                  

                  price = Math.round(price * 100) / 100;
                  price = parseFloat(price).toFixed(2);

                  var total_price = price * quantity;

                  
                  
                  
                  selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";

                  
                  selected_drugs_info_html += "<input type='hidden' class='price' value='"+price+"'>";
                  selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
                  selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
                  selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
                  selected_drugs_info_html += "<div class='col-md-2 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
                  selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
                  selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
                  selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
                  selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
                  selected_drugs_info_html += "<input value='"+dosage+"' class='form-control dosage' type='number' onkeyup='dosageEventEditClinic(this,event,"+i+")'>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-2 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
                  selected_drugs_info_html += "<input value='"+frequency_num+"' class='form-control frequency_num' type='number' onkeyup='frequencyEventEditClinic1(this,event,"+i+")'>";
                  selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEventEditClinic2(this,event,"+i+")'>";
                  selected_drugs_info_html += `<option value='minutely' ${frequency_time == 'minutely' ? 'selected' : ''} >Minutely</option>`;
                  selected_drugs_info_html += `<option value='hourly' ${frequency_time == 'hourly' ? 'selected' : ''} >Hourly</option>`;
                  selected_drugs_info_html += `<option value='daily' ${frequency_time == 'daily' ? 'selected' : ''} >Daily</option>`;
                  selected_drugs_info_html += `<option value='weekly' ${frequency_time == 'weekly' ? 'selected' : ''} >Weekly</option>`;
                  selected_drugs_info_html += `<option value='monthly' ${frequency_time == 'monthly' ? 'selected' : ''} >Monthly</option>`;
                  selected_drugs_info_html += `<option value='yearly' ${frequency_time == 'yearly' ? 'selected' : ''} >Yearly</option>`;
                  selected_drugs_info_html += `<option value='nocte' ${frequency_time == 'nocte' ? 'selected' : ''} >Nocte</option>`;
                  selected_drugs_info_html += `<option value='stat' ${frequency_time == 'stat' ? 'selected' : ''} >Stat</option>`;
                  selected_drugs_info_html += "</select>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-2 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
                  selected_drugs_info_html += "<input value='"+duration_num+"' class='form-control duration_num' type='number' onkeyup='durationEventEditClinic1(this,event,"+i+")'>";
                  selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEventEditClinic2(this,event,"+i+")'>";
                  selected_drugs_info_html += `option value='minutes' ${duration_time == 'minutes' ? 'selected' : ''} >Minutes</option>`;
                  selected_drugs_info_html += `<option value='hours' ${duration_time == 'hours' ? 'selected' : ''} >Hours</option>`;
                  selected_drugs_info_html += `<option value='days' ${duration_time == 'days' ? 'selected' : ''} >Days</option>`;
                  selected_drugs_info_html += `<option value='weeks' ${duration_time == 'weeks' ? 'selected' : ''} >Weeks</option>`;
                  selected_drugs_info_html += `<option value='months' ${duration_time == 'months' ? 'selected' : ''} >Months</option>`;
                  selected_drugs_info_html += `<option value='years' ${duration_time == 'years' ? 'selected' : ''} >Years</option>`;
                  selected_drugs_info_html += "</select>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

                  

                  selected_drugs_info_html += "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
                  selected_drugs_info_html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
                  selected_drugs_info_html += "<p>Total Price: " + addCommas(total_price) +"</p>";

                  if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
                    var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
                    console.log(total_store_quantity)
                    if(quantity > total_store_quantity){
                      selected_drugs_info_html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
                    }
                  }
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "</div>";

                }else{
                  selected_drugs_info_html += "<div class='form-row' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";

                  selected_drugs_info_html += message;

                  selected_drugs_info_html += "</div>";
                }
                
                selected_drugs_info_html += "</div>";  
              }
              selected_drugs_info_html += "</div>";

              $("#clinic-prescriptions-info-card .card-body").append(selected_drugs_info_html)

              $("#clinic-prescriptions-info-card").show();
              $("#select-drugs-proceed-edit-clinic-btn-2").show();
              
            }
            
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function goBackFromclinicPrescriptionsInfoCard (elem, evt) {
    $("#clinic-prescriptions-card").show();
    
    $("#clinic-prescriptions-info-card").hide();
    $("#select-drugs-proceed-edit-clinic-btn-2").hide();
  }

  function deleteSelectedClinicDrug(index, id, generic_name, initiation_code){
    console.log(index)
    console.log(id)

    
    swal({
      title: 'Warning',
      text: `You are about to delete the clinic selection of <em class="text-primary">${generic_name}</em> for this user. This is irreversible. Are you sure you want to proceed?`,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Yes Proceed!',
      cancelButtonText: 'Cancel'
    }).then(function(){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/delete_pending_transcribed_clinics_drugs_info'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code+"&id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success){

            swal({
              title: 'Success',
              allowEscapeKey: false,
              allowOutsideClick: false,
              text: `You have successfully deleted the selection of <em class="text-primary">${generic_name}</em> for this user.`,
              type: 'success'
              
            }).then(function(){
              
              // $(`#prescription-edit-data-div #rol-col-${id}`).remove();

              if($(`#prescription-edit-data-div .rol-col`).length == 0){
                $("#clinic-prescriptions-info-card").hide();
                viewClinicPrescriptions()
              }else{
                loadPendingClinicPatientsInfo(initiation_code)
              }
              
            });

          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });

    });
  }

  function dosageEventEditClinic(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();

      calculatePrescriptionEditClinic(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceedEditClinic2()
    }
  }

  function frequencyEventEditClinic1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescriptionEditClinic(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceedEditClinic2()
    }
  }

  function frequencyEventEditClinic2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescriptionEditClinic(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
  }

  function durationEventEditClinic1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescriptionEditClinic(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceedEditClinic2()
    }
  }

  function durationEventEditClinic2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    selectDrugsProceedEditClinic2(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);   
  }

  function calculatePrescriptionEditClinic (dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i) {
    // console.log(main_store_quantity + " : " + dispensary_quantity);
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;

      quantity = Math.round(quantity * 100) / 100;



      if(!isNaN(quantity)){
        var price = 0.00;

        var me = $("#prescription-edit-data-div");
        me.find(".form-row").each(function(index, el) {
          var el = $(el);
          
          if(index == i){
          
            price = el.find(".price").val();
          }
        });
        price = Math.round(price * 100) / 100;
        price = parseFloat(price).toFixed(2);
        if(price != null){
          var total_price = price * quantity;
          var html = "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
          html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
          html += "<p>Total Price: " + addCommas(total_price) +"</p>";
          if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
            var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
            console.log(total_store_quantity)
            if(quantity > total_store_quantity){
              html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
            }
          }
          $("#prescription-edit-data-div .form-row").eq(i).find(".display-div").html(html);
        }
      }
    }
  }

  function calculatePrescriptionEditClinic2 (dosage,frequency_num,frequency_time,duration_num,duration_time,i) {
    // console.log(dosage)
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      var ret = {};
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;
      quantity = Math.round(quantity * 100) / 100;

      if(!isNaN(quantity)){

        var price = 0.00;

        var me = $("#prescription-edit-data-div");
        me.find(".form-row").each(function(index, el) {
          var el = $(el);
          
          if(index == i){
          
            price = el.find(".price").val();
          }
        });
        price =  Math.round(price * 100) / 100;
        if(price != null){
          var total_price = price * quantity;
          ret = {
            'quantity' : quantity,
            'total_price' : total_price
          };
        }
      }

      return ret;
    }
  }

  function selectDrugsProceedEditClinic2 () {
    
    var me = $("#prescription-edit-data-div");
    var form_data = {
      initiation_code: edit_clinic_initiation_code,
      drugs_info : []
    };
    
    var number_of_drugs = me.find(".form-row").length
    var drugs_info = [];
    var num = 0;
    var sum = 0;
    var total_quantity = 0;

    me.find(".form-row").each(function(index, el) {
      var el = $(el);
      var id = el.attr("data-id");
      
      var price = el.find(".price").val();
      var dosage = el.find(".dosage").val();
      var frequency_num = el.find(".frequency_num").val();
      var frequency_time = el.find(".frequency_time").val();
      var duration_num = el.find(".duration_num").val();
      var duration_time = el.find(".duration_time").val();

      var obj = {
        id : id,
        frequency_num : frequency_num,
        frequency_time : frequency_time,
        duration_num : duration_num,
        duration_time : duration_time,
        dosage : dosage,
        price : price
      };

      drugs_info[index] = obj;

      if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
        num++;
        var prescription_obj = calculatePrescriptionEditClinic2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
        console.log(prescription_obj)
        if(prescription_obj != {}){
          var quantity = prescription_obj.quantity;
          var total_price = prescription_obj.total_price;

          total_quantity += quantity;
          sum += total_price;
        }
      }

    });

    form_data['drugs_info'] = drugs_info;

    
    // $.each(user_info, function(index, val) {
    //    form_data[index] = val;
    // });
    console.log(form_data);
    // console.log(JSON.stringify(form_data))

    if(num == number_of_drugs){
      swal({
        title: 'Proceed?',
        text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Mark These Drugs As Transcribed?",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed'
      }).then((result) => {
        
        $(".spinner-overlay").show();
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_as_transcribed_clinics_drugs'); ?>";
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success){
              var text = "Prescription marked as transcribed successfully"
              if(response.nfp){
                text += ". This Patient Is A None Fee Paying Patient. Proceed To Dispensing And Dispatching Officer."
              }
              
              $.notify({
              message : text
              },{
                type : "success"  
              });

              document.location.reload();
            }else{
              swal({
               title: 'Ooops',
                text: "Something Went Wrong",
                type: 'error'
              })
            }
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
             title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          } 
        });
      }); 
    }else{
      swal({
        title: 'Warning! ',
        text: "Every field must be entered to proceed",
        type: 'error'
      })
    }
  
  }  


  function selectTimeRangeWardPatients(elem,event){
    elem = $(elem);
    var start_date = elem.parent().find('.start-date').val();
    var end_date = elem.parent().find('.end-date').val();
    

    console.log(start_date)
    console.log(end_date)
    
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_transcribed_wards_drugs'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&start_date="+start_date+"&end_date="+end_date,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true){
          var messages = response.messages;
          $("#ward-prescriptions-card .card-body").html(messages);
          $("#ward-prescriptions-card #ward-prescriptions-table").DataTable();
          
          
        }
        else{
         swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },
      error: function (jqXHR,textStatus,errorThrown) {
        $(".spinner-overlay").hide();
        $.notify({
        message:"Sorry Something Went Wrong. Please Check Your Internet Connection And Try Again"
        },{
          type : "danger"  
        });
      }
    });
  }

  function viewWardPrescriptions(){
    

    var start_date = getYesterdayCurrentFullDate();
    var end_date = getTodayCurrentFullDate();
    console.log(start_date + " " + end_date)
    $(".spinner-overlay").show();

    $.ajax({
      url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_transcribed_wards_drugs'); ?>",
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&start_date="+start_date+"&end_date="+end_date,
      success : function (response) {
        $(".spinner-overlay").hide();
        if(response.success == true){
          var messages = response.messages;
          $("#perform-functions-card").hide();
          $("#ward-prescriptions-card .card-body").html(messages);
          $("#ward-prescriptions-card #ward-prescriptions-table").DataTable();
          $("#ward-prescriptions-card").show();
          
        }else{
          swal({
            title: 'Ooops!',
            text: "Sorry Something Went Wrong. Please Try Again",
            type: 'warning'
            
          })
        }
      },error :function () {
        $(".spinner-overlay").hide();
         swal({
          title: 'Error!',
          text: "Sorry Something Went Wrong",
          type: 'error'
          
        })
      }
    });
    
    
  }

  function goBackFromwardPrescriptionsCard(elem, evt) {
    $("#perform-functions-card").show();     
    $("#ward-prescriptions-card").hide();
  }

  function loadPendingWardPatientsInfo(initiation_code){
    if(initiation_code != ""){
      edit_ward_initiation_code = initiation_code
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/get_pending_transcribed_wards_drugs_info'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages != ""){
            var messages = response.messages;
            $("#ward-prescriptions-card").hide();
            $("#ward-prescriptions-info-card .card-body").html(messages);
            
            

            var drugs_info = response.drugs_info
            console.log(drugs_info)

            selected_drugs_edit_ward = drugs_info

            if(drugs_info.length > 0){
              var selected_drugs_info_html = "<div class='container-fluid' style='margin-top: 40px;'>";
              var j = 0;
              <?php 
                $attr = array('id' => 'prescription-edit-data-form');
              ?>
              selected_drugs_info_html += "<div id='prescription-edit-data-div'>";
              for(var i = 0; i < drugs_info.length; i++){

                var message = drugs_info[i].message;

                j++;
                // selected_drugs_info_html += j + ". ";

                

                var id = drugs_info[i].id;
                var generic_name = drugs_info[i].generic_name;

                selected_drugs_info_html += `<div class='rol-col' id='rol-col-${id}'>`;

                selected_drugs_info_html += '<button onclick="deleteSelectedWardDrug('+i+','+id+', `'+generic_name+'`, `'+initiation_code+'`)" title="Delete this selected drug" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
                if(message == 'okay'){

                  
                  var price = drugs_info[i].price;
                  var brand_name = drugs_info[i].brand_name;
                  
                  var formulation = drugs_info[i].formulation;
                  var strength = drugs_info[i].strength;
                  var strength_unit = drugs_info[i].strength_unit;
                  var main_store_quantity = drugs_info[i].main_store_quantity;
                  var dispensary_quantity = drugs_info[i].dispensary_quantity;
                  var unit = drugs_info[i].unit;

                  var dosage = drugs_info[i].dosage;
                  var frequency_num = drugs_info[i].frequency_num;
                  var frequency_time = drugs_info[i].frequency_time;
                  var duration_num = drugs_info[i].duration_num;
                  var duration_time = drugs_info[i].duration_time;
                  var quantity = drugs_info[i].quantity;

                  quantity = (duration_num / frequency_num);
                  quantity = dosage * quantity;

                  quantity = Math.round(quantity * 100) / 100;

                  

                  price = Math.round(price * 100) / 100;
                  price = parseFloat(price).toFixed(2);

                  var total_price = price * quantity;

                  
                  
                  
                  selected_drugs_info_html += "<div class='form-row' data-id='"+id+"' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";

                  
                  selected_drugs_info_html += "<input type='hidden' class='price' value='"+price+"'>";
                  selected_drugs_info_html += "<span class='main_store_quantity' style='display: none;'>"+main_store_quantity+"</span>";
                  selected_drugs_info_html += "<span class='dispensary_quantity' style='display: none;'>"+dispensary_quantity+"</span>";
                  selected_drugs_info_html += "<span class='unit' style='display: none;'>"+unit+"</span>";
                  selected_drugs_info_html += "<div class='col-md-2 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Generic Name:</h5>";
                  selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + generic_name.trunc(20) + "</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Formulation:</h5>";
                  selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + formulation.trunc(20) + "</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Brand Name:</h5>";
                  selected_drugs_info_html += "<p style='text-transform: capitalize;'>" + brand_name.trunc(25) + "</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Strength:</h5>";
                  selected_drugs_info_html += "<p>" + strength + " " + strength_unit.trunc(4) +"</p>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-1 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Dosage:</h5>";
                  selected_drugs_info_html += "<input value='"+dosage+"' class='form-control dosage' type='number' onkeyup='dosageEventEditWard(this,event,"+i+")'>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-2 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Frequency:</h5>";
                  selected_drugs_info_html += "<input value='"+frequency_num+"' class='form-control frequency_num' type='number' onkeyup='frequencyEventEditWard1(this,event,"+i+")'>";
                  selected_drugs_info_html += "<select class='form-control frequency_time' title='Select Frequency Time Range' onchange='frequencyEventEditWard2(this,event,"+i+")'>";
                  selected_drugs_info_html += `<option value='minutely' ${frequency_time == 'minutely' ? 'selected' : ''} >Minutely</option>`;
                  selected_drugs_info_html += `<option value='hourly' ${frequency_time == 'hourly' ? 'selected' : ''} >Hourly</option>`;
                  selected_drugs_info_html += `<option value='daily' ${frequency_time == 'daily' ? 'selected' : ''} >Daily</option>`;
                  selected_drugs_info_html += `<option value='weekly' ${frequency_time == 'weekly' ? 'selected' : ''} >Weekly</option>`;
                  selected_drugs_info_html += `<option value='monthly' ${frequency_time == 'monthly' ? 'selected' : ''} >Monthly</option>`;
                  selected_drugs_info_html += `<option value='yearly' ${frequency_time == 'yearly' ? 'selected' : ''} >Yearly</option>`;
                  selected_drugs_info_html += `<option value='nocte' ${frequency_time == 'nocte' ? 'selected' : ''} >Nocte</option>`;
                  selected_drugs_info_html += `<option value='stat' ${frequency_time == 'stat' ? 'selected' : ''} >Stat</option>`;
                  selected_drugs_info_html += "</select>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-2 form-group'>";
                  selected_drugs_info_html += "<h5 style='font-weight: bold;'>Duration:</h5>";
                  selected_drugs_info_html += "<input value='"+duration_num+"' class='form-control duration_num' type='number' onkeyup='durationEventEditWard1(this,event,"+i+")'>";
                  selected_drugs_info_html += "<select class='form-control duration_time' data-style='btn btn-primary btn-round' title='Select Duration Time Range' onchange='durationEventEditWard2(this,event,"+i+")'>";
                  selected_drugs_info_html += `option value='minutes' ${duration_time == 'minutes' ? 'selected' : ''} >Minutes</option>`;
                  selected_drugs_info_html += `<option value='hours' ${duration_time == 'hours' ? 'selected' : ''} >Hours</option>`;
                  selected_drugs_info_html += `<option value='days' ${duration_time == 'days' ? 'selected' : ''} >Days</option>`;
                  selected_drugs_info_html += `<option value='weeks' ${duration_time == 'weeks' ? 'selected' : ''} >Weeks</option>`;
                  selected_drugs_info_html += `<option value='months' ${duration_time == 'months' ? 'selected' : ''} >Months</option>`;
                  selected_drugs_info_html += `<option value='years' ${duration_time == 'years' ? 'selected' : ''} >Years</option>`;
                  selected_drugs_info_html += "</select>";
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "<div class='col-md-2 form-group display-div'>";

                  

                  selected_drugs_info_html += "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
                  selected_drugs_info_html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
                  selected_drugs_info_html += "<p>Total Price: " + addCommas(total_price) +"</p>";

                  if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
                    var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
                    console.log(total_store_quantity)
                    if(quantity > total_store_quantity){
                      selected_drugs_info_html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
                    }
                  }
                  selected_drugs_info_html += "</div>";

                  selected_drugs_info_html += "</div>";

                }else{
                  selected_drugs_info_html += "<div class='form-row' style='border-bottom: 1px solid black; border-radius: 2px; margin-bottom: 10px;'>";

                  selected_drugs_info_html += message;

                  selected_drugs_info_html += "</div>";
                }
                
                selected_drugs_info_html += "</div>";  
              }
              selected_drugs_info_html += "</div>";

              $("#ward-prescriptions-info-card .card-body").append(selected_drugs_info_html)

              $("#ward-prescriptions-info-card").show();
              $("#select-drugs-proceed-edit-ward-btn-2").show();
              
            }
            
          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });
    }
  }

  function goBackFromwardPrescriptionsInfoCard (elem, evt) {
    $("#ward-prescriptions-card").show();
    
    $("#ward-prescriptions-info-card").hide();
    $("#select-drugs-proceed-edit-ward-btn-2").hide();
  }

  function deleteSelectedWardDrug(index, id, generic_name, initiation_code){
    console.log(index)
    console.log(id)

    
    swal({
      title: 'Warning',
      text: `You are about to delete the ward selection of <em class="text-primary">${generic_name}</em> for this user. This is irreversible. Are you sure you want to proceed?`,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#9c27b0',
      confirmButtonText: 'Yes Proceed!',
      cancelButtonText: 'Cancel'
    }).then(function(){
      $(".spinner-overlay").show();
      $.ajax({
        url : "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' .$fourth_addition.'/delete_pending_transcribed_wards_drugs_info'); ?>",
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "initiation_code="+initiation_code+"&id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success){

            swal({
              title: 'Success',
              allowEscapeKey: false,
              allowOutsideClick: false,
              text: `You have successfully deleted the selection of <em class="text-primary">${generic_name}</em> for this user.`,
              type: 'success'
              
            }).then(function(){
              
              // $(`#prescription-edit-data-div #rol-col-${id}`).remove();

              if($(`#prescription-edit-data-div .rol-col`).length == 0){
                $("#ward-prescriptions-info-card").hide();
                viewWardPrescriptions()
              }else{
                loadPendingWardPatientsInfo(initiation_code)
              }
              
            });

          }else{
            swal({
              title: 'Ooops!',
              text: "Sorry Something Went Wrong. Please Try Again",
              type: 'warning'
              
            })
          }
        },error :function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error!',
            text: "Sorry Something Went Wrong",
            type: 'error'
            
          })
        }
      });

    });
  }

  function dosageEventEditWard(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();

      calculatePrescriptionEditWard(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceedEditWard2()
    }
  }

  function frequencyEventEditWard1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescriptionEditWard(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceedEditWard2()
    }
  }

  function frequencyEventEditWard2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    calculatePrescriptionEditWard(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
  }

  function durationEventEditWard1(elem,event,i){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode !== 13){
      elem = $(elem);
      var val = elem.val();
      var dosage = elem.parent().parent().find(".dosage").val();
      var frequency_num = elem.parent().parent().find(".frequency_num").val();
      var frequency_time = elem.parent().parent().find(".frequency_time").val();
      var duration_num = elem.parent().parent().find(".duration_num").val();
      var duration_time = elem.parent().parent().find(".duration_time").val();
      var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
      var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
      var unit = elem.parent().parent().find(".unit").html();
      if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
        elem.parent().parent().find(".frequency_num").val("1");
        frequency_num = 1;
      }

      calculatePrescriptionEditWard(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);
      
    }else{
      selectDrugsProceedEditWard2()
    }
  }

  function durationEventEditWard2(elem,evt,i){
    elem = $(elem);
    var val = elem.val();
    var dosage = elem.parent().parent().find(".dosage").val();
    var frequency_num = elem.parent().parent().find(".frequency_num").val();
    var frequency_time = elem.parent().parent().find(".frequency_time").val();
    var duration_num = elem.parent().parent().find(".duration_num").val();
    var duration_time = elem.parent().parent().find(".duration_time").val();
    var main_store_quantity = elem.parent().parent().find(".main_store_quantity").html();
    var dispensary_quantity = elem.parent().parent().find(".dispensary_quantity").html();
    var unit = elem.parent().parent().find(".unit").html();

    if(frequency_time == "daily" || frequency_time == "weekly" || frequency_time == "monthly" || frequency_time == "yearly" || frequency_time == "nocte" || frequency_time == "stat"){
      elem.parent().parent().find(".frequency_num").val("1");
      frequency_num = 1;
    }

    selectDrugsProceedEditWard2(dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i);   
  }

  function calculatePrescriptionEditWard (dosage,frequency_num,frequency_time,duration_num,duration_time,main_store_quantity,dispensary_quantity,unit,i) {
    // console.log(main_store_quantity + " : " + dispensary_quantity);
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;

      quantity = Math.round(quantity * 100) / 100;



      if(!isNaN(quantity)){
        var price = 0.00;

        var me = $("#prescription-edit-data-div");
        me.find(".form-row").each(function(index, el) {
          var el = $(el);
          
          if(index == i){
          
            price = el.find(".price").val();
          }
        });
        price = Math.round(price * 100) / 100;
        price = parseFloat(price).toFixed(2);
        if(price != null){
          var total_price = price * quantity;
          var html = "<p>Total Quantity: " + addCommas(parseFloat(quantity).toFixed(2)) + " " + unit+ "</p>";
          html += "<p>Price Per Unit: " + addCommas(price) +"</p>";
          html += "<p>Total Price: " + addCommas(total_price) +"</p>";
          if(!isNaN(main_store_quantity) && !isNaN(dispensary_quantity)){
            var total_store_quantity = parseFloat(main_store_quantity) + parseFloat(dispensary_quantity);
            console.log(total_store_quantity)
            if(quantity > total_store_quantity){
              html += "<span class='text-warning' style='font-style: italic;'>Quantity Prescribed Exceeds Total Quantity In Drug Store Which Is " + addCommas(parseFloat(total_store_quantity).toFixed(2)) + " " + unit+ ". Please Remember To Restock Before Dispensing.</span>";
            }
          }
          $("#prescription-edit-data-div .form-row").eq(i).find(".display-div").html(html);
        }
      }
    }
  }

  function calculatePrescriptionEditWard2 (dosage,frequency_num,frequency_time,duration_num,duration_time,i) {
    // console.log(dosage)
    if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != ""){
      // console.log(i)
      var quantity = 0;
      var ret = {};
      if(frequency_time == "nocte" || frequency_time == "stat"){
        frequency_time = "daily";
      }

      if(frequency_time == "yearly" && duration_time == "years"){
        
      }else if(frequency_time == "monthly" && duration_time == "years"){
        duration_num = 12 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "years"){
        duration_num = 12 * 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "years"){
        duration_num = 12 * 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "years"){
        duration_num = 12 * 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "monthly" && duration_time == "months"){
        
      }else if(frequency_time == "weekly" && duration_time == "months"){
        duration_num = 4 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "months"){
        duration_num = 28 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "months"){
        duration_num = 28 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "months"){
        duration_num = 28 * 24 * 60 * duration_num;
      }else if(frequency_time == "weekly" && duration_time == "weeks"){
        
      }else if(frequency_time == "daily" && duration_time == "weeks"){
        duration_num = 7 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "weeks"){
        duration_num = 7 * 24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "weeks"){
        duration_num = 7 * 24 * 60 * duration_num;
      }else if(frequency_time == "daily" && duration_time == "days"){
        
      }else if(frequency_time == "hourly" && duration_time == "days"){
        duration_num =  24 * duration_num;
      }else if(frequency_time == "minutely" && duration_time == "days"){
        duration_num =  24 * 60 * duration_num;
      }else if(frequency_time == "hourly" && duration_time == "hours"){
        
      }else if(frequency_time == "minutely" && duration_time == "hours"){
        duration_num =  60 * duration_num;
      }else{
        duration_num = 0;
        frequency_num = 0;
      }
      

      quantity = (duration_num / frequency_num);
      quantity = dosage * quantity;
      quantity = Math.round(quantity * 100) / 100;

      if(!isNaN(quantity)){

        var price = 0.00;

        var me = $("#prescription-edit-data-div");
        me.find(".form-row").each(function(index, el) {
          var el = $(el);
          
          if(index == i){
          
            price = el.find(".price").val();
          }
        });
        price =  Math.round(price * 100) / 100;
        if(price != null){
          var total_price = price * quantity;
          ret = {
            'quantity' : quantity,
            'total_price' : total_price
          };
        }
      }

      return ret;
    }
  }

  function selectDrugsProceedEditWard2 () {
    
    var me = $("#prescription-edit-data-div");
    var form_data = {
      initiation_code: edit_ward_initiation_code,
      drugs_info : []
    };
    
    var number_of_drugs = me.find(".form-row").length
    var drugs_info = [];
    var num = 0;
    var sum = 0;
    var total_quantity = 0;

    me.find(".form-row").each(function(index, el) {
      var el = $(el);
      var id = el.attr("data-id");
      
      var price = el.find(".price").val();
      var dosage = el.find(".dosage").val();
      var frequency_num = el.find(".frequency_num").val();
      var frequency_time = el.find(".frequency_time").val();
      var duration_num = el.find(".duration_num").val();
      var duration_time = el.find(".duration_time").val();

      var obj = {
        id : id,
        frequency_num : frequency_num,
        frequency_time : frequency_time,
        duration_num : duration_num,
        duration_time : duration_time,
        dosage : dosage,
        price : price
      };

      drugs_info[index] = obj;

      if(dosage != "" && frequency_num != "" && frequency_time != "" && duration_num != "" && duration_time != "" && price != ""){
        num++;
        var prescription_obj = calculatePrescriptionEditWard2 (dosage,frequency_num,frequency_time,duration_num,duration_time,index);
        console.log(prescription_obj)
        if(prescription_obj != {}){
          var quantity = prescription_obj.quantity;
          var total_price = prescription_obj.total_price;

          total_quantity += quantity;
          sum += total_price;
        }
      }

    });

    form_data['drugs_info'] = drugs_info;

    
    // $.each(user_info, function(index, val) {
    //    form_data[index] = val;
    // });
    console.log(form_data);
    // console.log(JSON.stringify(form_data))

    if(num == number_of_drugs){
      swal({
        title: 'Proceed?',
        text: "<span class='text-primary' style='font-style: italic;'>"+ num +"</span> Drugs Prescription Info Has Been Entered With Total Quantity Of <span class='text-primary' style='font-style: italic;'>" + addCommas(total_quantity) + "</span> And Total Price Of <span class='text-primary' style='font-style: italic;'>" + addCommas(sum) + "</span>. Are You Sure You Want To Mark These Drugs As Transcribed?",
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Proceed'
      }).then((result) => {
        
        $(".spinner-overlay").show();
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/mark_as_transcribed_wards_drugs'); ?>";
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success){
              var text = "Prescription marked as transcribed successfully"
              if(response.nfp){
                text += ". This Patient Is A None Fee Paying Patient. Proceed To Dispensing And Dispatching Officer."
              }
              
              $.notify({
              message : text
              },{
                type : "success"  
              });

              document.location.reload();
            }else{
              swal({
               title: 'Ooops',
                text: "Something Went Wrong",
                type: 'error'
              })
            }
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
             title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          } 
        });
      }); 
    }else{
      swal({
        title: 'Warning! ',
        text: "Every field must be entered to proceed",
        type: 'error'
      })
    }
  
  }  



</script>    
      <div class="spinner-overlay" style="display: none;">
        <div class="spinner-well">
          <img src="<?php echo base_url('assets/images/tests_loader.gif') ?>" alt="Loading...">
        </div>
      </div>
     
      <div class="content" tabindex="-1">
        <div class="container-fluid">
         <h2 class="text-center"><?php echo $health_facility_name; ?></h2>
          <?php
            $logged_in_user_name = $user_name;
           
          ?>
          <?php
           if($user_position == "admin"){ ?>
          <span style="text-transform: capitalize; font-size: 13px;" ><a class="text-info" href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/admin') ?>"><?php echo $dept_name; ?></a>&nbsp;&nbsp; > >  <a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$third_addition.'/admin') ?>" class="text-info"><?php echo $sub_dept_name; ?></a> &nbsp;&nbsp; > > <?php echo $personnel_name; ?></span>
          <?php  } elseif($user_position == "sub_admin"){ ?>
           <span style="text-transform: capitalize; font-size: 13px;" ><a href="<?php echo site_url('onehealth/index/'.$health_facility_slug.'/'.$dept_slug.'/'.$sub_dept_slug.'/admin') ?>" class="text-info"><?php echo $sub_dept_name; ?></a> &nbsp;&nbsp; > > <?php echo $personnel_name; ?></span>
          <?php  } ?>
          <h3 class="text-center" style="text-transform: capitalize;"><?php echo $personnel_name; ?></h3>
          <?php if($user_position == "admin" || $user_position == "sub_admin"){ ?>
            <?php if($personnel_num > 0){ ?>
          <h4>No. Of Personnel: <a href="<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/personnel') ?>"><?php echo $personnel_num; ?></a></h4>
        <?php } ?>
          <?php } ?>
          <div class="row">
              
            <div class="col-md-12">

              <div class="card" id="ward-prescriptions-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Ward prescription pending transcription</h3>
                  <button onclick="goBackFromwardPrescriptionsInfoCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="ward-prescriptions-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Ward prescriptions pending transcription</h3>
                  <button onclick="goBackFromwardPrescriptionsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>


              
              <div class="card" id="clinic-prescriptions-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Clinic prescription pending transcription</h3>
                  <button onclick="goBackFromclinicPrescriptionsInfoCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="clinic-prescriptions-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Clinic prescriptions pending transcription</h3>
                  <button onclick="goBackFromclinicPrescriptionsCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="edit-patient-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title">Change Patient Type</h3>
                  <button onclick="goBackFromEditPatientInfoCard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="registered-patients-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackRegisteredPatientsCard(this,event)">Go Back</button>
                  <h3 class="card-title"></h3>
                  
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="performFunctions(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="select-drugs-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading" style="text-transform: capitalize;"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="poison-register-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPoisonRegisterCard(this,event)">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Poison Register</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="selected-drugs-info-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading"></h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackSelectedInfoDrugs(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Main Store </h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div>

              <div class="card" id="view-drug-main-store-card" style="display: none;">
                <div class="card-header">
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackViewDrugMainStoreCard()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                </div>
                <div class="card-body">
                              
                </div> 
              </div>

              <div class="card" id="error-register-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Error Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="add-new-error-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Add New Data To Error / Occurrence Register</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackAddNewErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'add-new-error-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/add_data_error_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="event" class="label-control"> <span class="form-error1">*</span> Enter Event / Occurrence</label>
                        <textarea name="event" id="event" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="action" class="label-control"> <span class="form-error1">*</span> Action: </label>
                        <textarea name="action" id="action" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-md-6">
                        <p class="label"><span class="form-error1">*</span>  Remedied: </p>
                        <div id="remedied">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="1" id="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="0" id="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="edit-new-error-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Edit Data</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackEditErrorRegister(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <?php 
                    $attr = array('id' => 'edit-new-error-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/edit_data_error_register',$attr);
                  ?>
                    <span class="form-error1">*</span> : Required
                    <div class="form-row">
                      <div class="form-group col-sm-12">
                        <label for="event" class="label-control"> <span class="form-error1">*</span> Enter Event / Occurrence</label>
                        <textarea name="event" id="event" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="action" class="label-control"> <span class="form-error1">*</span> Action: </label>
                        <textarea name="action" id="action" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-md-6">
                        <p class="label"><span class="form-error1">*</span>  Remedied: </p>
                        <div id="remedied">
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="1" id="yes"> Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="remedied" value="0" id="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>

                </div>
              </div>

              <div class="card" id="searched-patients-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackFromSearchedPatientsCard(this,event)">Go Back</button>
                  <h3 class="card-title">Searched Patients</h3>
                </div>
                <div class="card-body">

                </div>
              </div>
              

              <div class="card" id="perform-functions-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Your Functions</h3>
                  <button type="button" class="btn btn-round btn-warning" onclick="goBackPerformFunctions(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-2" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>

                      <?php if($health_facility_structure == "pharmacy"){ ?>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="transcribePrescriptions(this,event)">Transcribe / Make Prescription</td>
                        </tr>

                        <tr>
                          <td>2</td>
                          <td onclick="searchPatients(this,event)">Search Patient</td>
                        </tr>

                       
                        
                        <tr>
                          <td>3</td>
                          <td onclick="errorRegister(this,event)" style="text-transform: capitalize;">View & Update error/occurrence register</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td onclick="poisonRegister(this,event)" style="text-transform: capitalize;">View Poison register</td>
                        </tr>
                        
                        <tr>
                          <td>5</td>
                          <td onclick="viewStoreRecords(this,event)" style="text-transform: capitalize;">View Store Records</td>
                        </tr>
                        <!-- <tr>
                          <td>8</td>
                          <td onclick="viewAndCreateRecords(this,event)" style="text-transform: capitalize;">View And Create Records</td>
                        </tr> -->
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>9</td>
                          <td onclick="viewWardPatients(this,event)" style="text-transform: capitalize;">View Ward Patients</td>
                        </tr >-->
                        <?php } ?>
                      </tbody>

                    <?php }else if($health_facility_structure == "hospital"){ ?>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="transcribePrescriptionsHospital(this,event)">Transcribe / Make Prescription</td>
                        </tr>
                        
                        <tr>
                          <td>2</td>
                          <td onclick="searchPatients(this,event)">Search Patient</td>
                        </tr>
                         <tr>
                          <td>3</td>
                          <td onclick="viewClinicPrescriptions()">View Clinic Prescriptions</td>
                        </tr>

                        <tr>
                          <td>4</td>
                          <td onclick="viewWardPrescriptions(this,event)">View Ward Prescriptions</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td onclick="errorRegister(this,event)" style="text-transform: capitalize;">View & Update error/occurrence register</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td onclick="poisonRegister(this,event)" style="text-transform: capitalize;">View Poison register</td>
                        </tr>
                        
                        <tr>
                          <td>7</td>
                          <td onclick="viewStoreRecords(this,event)" style="text-transform: capitalize;">View Store Records</td>
                        </tr>
                        <!-- <tr>
                          <td>8</td>
                          <td onclick="viewAndCreateRecords(this,event)" style="text-transform: capitalize;">View And Create Records</td>
                        </tr> -->
                        <?php if($health_facility_structure == "hospital"){ ?>
                        <!-- <tr>
                          <td>9</td>
                          <td onclick="viewWardPatients(this,event)" style="text-transform: capitalize;">View Ward Patients</td>
                        </tr >-->
                        <?php } ?>
                      </tbody>
                    <?php } ?>
                    </table>
                  </div>              
                </div> 
              </div>


            </div>
          </div>


          <div id="add-new-error-btn" onclick="addNewError(this,event)" rel="tooltip" data-toggle="tooltip" title="Add New Data To Error / Occurrence Register" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="fa fa-plus" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true"></i>

            </div>
          </div>

          

          <div id="select-drugs-proceed-edit-ward-btn-2" onclick="selectDrugsProceedEditWard2()" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>

          <div id="select-drugs-proceed-edit-clinic-btn-2" onclick="selectDrugsProceedEditClinic2()" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>


          <div id="select-drugs-proceed-btn-2" onclick="selectDrugsProceed2()" rel="tooltip" data-toggle="tooltip" title="Proceed" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>
          
          <div id="select-drugs-proceed-btn" onclick="selectDrugsProceed(this,event)" rel="tooltip" data-toggle="tooltip" title="Proceed After Selecting Drugs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
            </div>
          </div>

          
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="search-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Search Patient By Full Name </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <?php
              $attr = array('id' => 'search-patient-form');
              echo form_open("",$attr);
              ?>
                

                <div class="form-group">
                  <label for="full_name">Enter Full Name: </label>
                  <input type="text" class="form-control" name="full_name" id="full_name">
                  <span class="form-error"></span>
                </div>

                <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" data-backdrop="static" id="perform-action-on-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center" style="text-transform: capitalize;">Choose Action To Perform On: </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>


            <div class="modal-body" id="modal-body">
              <div class="table-responsive">
      
                <table class="table table-hover" id="perform-action-on-patient-modal" cellspacing="0" width="100%" style="width:100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Option</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td onclick="selectDrugsForPatient(this,event)" class="text-primary">Select Drugs For This Patient</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td onclick="editPatientInfo(this,event)" class="text-primary">Edit Patient Info</td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="enter-patient-data-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" style="text-transform: capitalize;">Enter Patient Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <?php 
                $attr = array('id' => 'enter-patient-data-form');
                echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_patient_data_form_pharmacy',$attr);
              ?>
              <span class="form-error1">*</span> : Required
              <div class="wrap">
                <div class="form-row">                 
                  <div class="form-group col-md-12">
                    <label for="full_name" class="label-control"><span class="form-error1">*</span> FullName: </label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="">
                    <span class="form-error"></span>
                  </div>
                  

                  <div class="form-group col-md-12">
                    <label for="dob" class="label-control">  Date Of Birth: </label>
                    <input type="date" class="form-control" name="dob" id="dob">
                    <span class="form-error"></span>
                  </div>
                  
                  <div class="form-group col-md-12">
                    <label for="age" class="label-control">  Age: </label>
                    <input type="number" class="form-control" name="age" id="age">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <div id="age_unit">
                      <label for="age_unit" class="label-control">  Age Unit: </label>
                      <select name="age_unit" id="age_unit" class="form-control selectpicker" data-style="btn btn-link">
                        <option value="minutes">Minutes</option>
                        <option value="hours">Hours</option>
                        <option value="days">Days</option>
                        <option value="weeks">Weeks</option>
                        <option value="months">Months</option>
                        <option value="years" selected>Years</option>

                      </select>
                    </div>
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <p class="label"><span class="form-error1">*</span>  Gender: </p>
                    <div id="sex">
                      <div class="form-check form-check-radio form-check-inline">
                        <label class="form-check-label">
                          <input class="form-check-input" type="radio" name="sex" value="female" id="female"> Female
                          <span class="circle">
                              <span class="check"></span>
                          </span>
                        </label>
                      </div>
                      <div class="form-check form-check-radio form-check-inline">
                        <label class="form-check-label">
                          <input class="form-check-input" type="radio" name="sex" value="male" id="male"> Male
                          <span class="circle">
                              <span class="check"></span>
                          </span>
                        </label>
                      </div>
                      <div class="form-check form-check-radio form-check-inline">
                        <label class="form-check-label">
                          <input class="form-check-input" type="radio" name="sex" value="na" checked> N/A
                          <span class="circle">
                              <span class="check"></span>
                          </span>
                        </label>
                      </div>
                    </div>
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="clinician" class="label-control">  Clinician: </label>
                    <input type="text" class="form-control" name="clinician" id="clinician">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="race" class="label-control">  Race/Tribe: </label>
                    <input type="text" class="form-control" id="race" name="race">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="mobile" class="label-control">  Mobile No: </label>
                    <input type="number" class="form-control" id="mobile" name="mobile">
                    <span class="form-error"></span>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="email" class="label-control">  Email: </label>
                    <input type="email" class="form-control" id="email" name="email">
                    <span class="form-error"></span>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="address" class="label-control">  Address: </label>
                    <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                    <span class="form-error"></span>
                  </div>

                </div>
              </div>
              <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


      <div class="modal fade" style="z-index: 10000" data-backdrop="static" id="enter-clinician-name-modal" data-focus="true" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title text-primary" style="text-transform: capitalize;">One Or More Poisons Selected.</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <p>You Need To Enter Clinician's Name To Proceed You Need To Enter Clinician's Name To Proceed</p>
              <?php 
                $attr = array('id' => 'enter-clinician-name-form');
                echo form_open('',$attr);
              ?>
              <div class="form-group">
                <input type="text" id="name" name="name" class="form-control" placeholder="Clinician's Name">
              </div>
              <input type="submit" class="btn btn-primary" value="Submit">
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <!-- <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer> -->
        </div>
       
      </footer>
  </div>
  
</body>
<script>
  $(document).ready(function() {

    $("#search-patient-form").submit(function (evt) {
        evt.preventDefault();
        var me  = $(this);
        var form_data = me.serializeArray();

        // console.log(form_data[0].value)
        // return
       
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/search_patients_pharmacy_counselling') ?>";

        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          dataType : "json",
          responseType : "json",
          data : form_data,
          success : function (response) {
            $(".spinner-overlay").hide();
            console.log(response)
            if(response.success && response.messages != ""){
              var messages = response.messages;
              
              $("#searched-patients-card .card-title").html('Patients Matching <em class="text-primary">'+ form_data[0].value +'</em>');
              $("#searched-patients-card .card-body").html(messages);
              $("#search-patient-modal").modal("hide");
              $("#perform-functions-card").hide();
              $("#searched-patients-card").show();
              $("#searched-patients-card #searched-patients-table").DataTable();
            }else if(response.no_results){
              swal({
                title: 'No Results!',
                text: "No Patients Matched This Search",
                type: 'info'             
              })
            }
          },error : function () {
            $(".spinner-overlay").hide();
            $.notify({
              message:"Sorry Something Went Wrong. Please Check Your Internet Connection"
              },{
                type : "danger" 
            });
          }
        });
      })

    $("#edit-new-error-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      var id = me.attr("data-id");
      form_data = form_data.concat({
        "name" : "id",
        "value" : id
      })
      console.log(form_data);
      if(id !== ""){
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : form_data,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success){
              $.notify({
              message:"This Register Has Been Edited Successfully"
              },{
                type : "success"  
              });
            }else if(response.expired){
              $.notify({
              message:"Date Entered Must Be In The In The Future"
              },{
                type : "warning"  
              });
            }else{
              $.each(response.error_messages, function (key,value) {

              var element = $('#edit-new-error-form #'+key);
              
              element.closest('div.form-group')
                      
                      .find('.form-error').remove();
              element.after(value);
              
             });
              $.notify({
              message:"Some Values Where Not Valid. Please Enter Valid Values"
              },{
                type : "warning"  
              });
            }
          },error : function () {
            $(".spinner-overlay").hide();
            swal({
             title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          } 
        }); 
      }
    })

    $("#add-new-error-form").submit(function (evt) {
      evt.preventDefault();
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      console.log(form_data);
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : form_data,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success){
            document.location.reload()
          }else if(response.expired){
            $.notify({
            message:"Date Entered Must Be In The In The Future"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.error_messages, function (key,value) {

            var element = $('#add-new-error-form #'+key);
            
            element.closest('div.form-group')
                    
                    .find('.form-error').remove();
            element.after(value);
            
           });
            $.notify({
            message:"Some Values Where Not Valid. Please Enter Valid Values"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
    })
    
    $("#enter-clinician-name-form").submit(function (evt) {
      evt.preventDefault();
      var name = $(this).find("#name").val();
      console.log(name)
      console.log(name.length)
      if(name !== ""){
        if(name.length >= 10 && name.length <= 100){
          user_info.clinician = name;
          $("#enter-clinician-name-modal").modal("hide");
          selectDrugsProceed2();
        }else{
          $.notify({
          message:"This Field Must Be Between 10 To 100 Characters"
          },{
            type : "warning"  
          });
        }
      }else{
        $.notify({
        message:"This Field Is Required"
        },{
          type : "warning"  
        });
      }
    })

    $("#enter-patient-data-form").submit(function (evt) {
      evt.preventDefault();
      registered_patient = false;
      var me = $(this);
      var form_data = me.serializeArray();
      var url = me.attr("action");
      console.log(form_data);
      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : form_data,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != "" && response.user_info != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            user_info = response.user_info;

            $("#select-drugs-card .card-title").html("Select Drugs For: " + user_info.full_name);
            $("#select-drugs-card .card-body").html(messages);
            $("#enter-patient-data-modal").modal("hide");
            $("#perform-functions-card").hide();
            $("#select-drugs-card #select-drugs-table").DataTable();
            $("#select-drugs-card").show();
            $("#select-drugs-proceed-btn").show("fast");
          }else if(response.expired){
            $.notify({
            message:"Date Entered Must Be In The In The Future"
            },{
              type : "warning"  
            });
          }else{
            $.each(response.error_messages, function (key,value) {

            var element = $('#enter-patient-data-form #'+key);
            
            element.closest('div.form-group')
                    
                    .find('.form-error').remove();
            element.after(value);
            
           });
            $.notify({
            message:"Some Values Where Not Valid. Please Enter Valid Values"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
           title: 'Ooops',
            text: "Something Went Wrong",
            type: 'error'
          })
        } 
      }); 
    });

    <?php if($this->session->prescription_success){ ?>
     $.notify({
      message:"Prescription Made Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

    <?php if($this->session->error_register_data_entered){ ?>
     $.notify({
      message:"Data Has Been Added To The Error / Occurrence Register Successfully"
      },{
        type : "success"  
      });
    <?php }  ?>

  });



</script>
