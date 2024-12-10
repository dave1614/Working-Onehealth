<style>
  tr{
    cursor: pointer;
  }
  body {
    
  }
  .spinner{
    position: absolute; 
    right: 25px; 
    top: 13px;
    width: 20px;
      height: 20px;
      display: none;
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
            $clinic_structure = $row->clinic_structure;
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
  var patient_facility_id = "";
  var patient_full_name = "";

  var ward_record_id = "";
  var consultation_id = "";
  var patient_name = "<?php echo $patients_full_name; ?>";
  var patient_user_id = <?php echo $patient_user_id; ?>;
  var header_title = "";

  console.log(patient_name)
  console.log(patient_user_id)

  
  function viewClinicConsultations (elem,evt) {
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/medical_records/view_patients_clinic_consultations/' . $patient_info->user_id); ?>";
     console.log(url)
        

    $("#main-card").hide("fast");
    var html = `<p class="text-primary"></p><div class="table-div material-datatables table-responsive" style=""><table class="table table-test table-striped table-bordered nowrap hover display" id="clinic-consultations-table" cellspacing="0" width="100%" style="width:100%"><thead><tr><th>Id</th><th class="sort">#</th><th class="no-sort">Clinic Name</th><th class="no-sort">Start Date</th><th class="no-sort">End Date</th><th class="no-sort">Nurse</th><th class="no-sort">Doctor</th></tr></thead></table></div>`;

   
    $("#clinic-consultations-card .card-body").html(html);
    

    var table = $("#clinic-consultations-card #clinic-consultations-table").DataTable({
      
      initComplete : function() {
        var self = this.api();
        var filter_input = $('#clinic-consultations-card .dataTables_filter input').unbind();
        var search_button = $('<button type="button" class="p-3 btn btn-primary btn-fab btn-fab-mini btn-round"><i class="fa fa-search"></i></button>').click(function() {
            self.search(filter_input.val()).draw();
        });
        var clear_button = $('<button type="button" class="p-3 btn btn-danger btn-fab btn-fab-mini btn-round"><i class="fa fa fa-times"></i></button>').click(function() {
            filter_input.val('');
            search_button.click();
        });

        $(document).keypress(function (event) {
            if (event.which == 13) {
                search_button.click();
            }
        });

        $('#clinic-consultations-card .dataTables_filter').append(search_button, clear_button);
      },
      'processing': true,
       "ordering": true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
         'url': url
      },
      "language": {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
      },
      search: {
          return: true,
      },
      'columns': [
        { data: 'id' },
        { data: 'index' },
        { data: 'clinic_name' },
        
        { data: 'start_date_time' },
        { data: 'end_date_time' },
        { data: 'nurse' },
        { data: 'doctor' },
        
      ],
      'columnDefs': [
        {
            "targets": [0],
            "visible": false,
            "searchable": false,

        },
        
        {
          orderable: false,
          targets: "no-sort"
        }
      ],
      order: [[1, 'desc']]
    });
    $('#clinic-consultations-card tbody').on( 'click', 'tr', function () {
        // console.log( table.row( this ).data() );
        var data = table.row( this ).data();
        
        viewClinicConsultationDetails(data.id)
        
    } );
    $("#clinic-consultations-card").show("fast");
    
       
    
  }

  function goBackClinicConsultationsCard(){
    $("#main-card").show("fast");
    
    $("#clinic-consultations-card").hide("fast");
    
  }

  function viewClinicConsultationDetails (consultation_id) {
    console.log(consultation_id)
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/medical_records/view_clinic_consultation_details/' . $patient_info->user_id); ?>";
     console.log(url)
     

    $(".spinner-overlay").show();
          
    
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages !== ""){
          var messages = response.messages;



          $("#clinic-consultations-card").hide();
          $("#clinic-consultations-details-card .card-body").html(messages);

          var editors = response.editors

          // Object.keys(obj).length === 0
          if(Object.keys(editors).length > 0){
            var keys = Object.keys(editors);

            keys.forEach((key, index) => {
                // console.log(`${key}: ${editors[key]}`);

              var quill =  new Quill('#clinic-consultations-details-card #'+key , {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
              });
              quill.setContents(JSON.parse(editors[key]));
            });
          }
          
          $("#clinic-consultations-details-card").show();
          $("#go-back-from-consultation-details-btn").show("fast");

          
          
        }
        else{
         $.notify({
          message:"Sorry Something Went Wrong"
          },{
            type : "danger"  
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
       
    
  }

  function goBackClinicConsultationDetailsCard(){
    $("#clinic-consultations-card").show();
    
    
    $("#clinic-consultations-details-card").hide();
    $("#go-back-from-consultation-details-btn").hide("fast");
  }


  function viewWardAdmissions(elem,evt){
    evt.preventDefault();
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/medical_records/view_patients_ward_admissions/' . $patient_info->user_id); ?>";
     console.log(url)
        

    $("#main-card").hide("fast");
    var html = `<p class="text-primary"></p><div class="table-div material-datatables table-responsive" style=""><table class="table table-test table-striped table-bordered nowrap hover display" id="ward-admissions-table" cellspacing="0" width="100%" style="width:100%"><thead><tr><th>Id</th><th>Consultation Id</th><th class="sort">#</th><th class="no-sort">Ward</th><th class="no-sort">Clinic Name</th><th class="no-sort">Admission Date/ Time</th><th class="no-sort">Discharge Date/ Time</th><th class="no-sort">Doctor</th></tr></thead></table></div>`;

   
    $("#wards-patients-card .card-body").html(html);
    

    var table = $("#wards-patients-card #ward-admissions-table").DataTable({
      
      initComplete : function() {
        var self = this.api();
        var filter_input = $('#wards-patients-card .dataTables_filter input').unbind();
        var search_button = $('<button type="button" class="p-3 btn btn-primary btn-fab btn-fab-mini btn-round"><i class="fa fa-search"></i></button>').click(function() {
            self.search(filter_input.val()).draw();
        });
        var clear_button = $('<button type="button" class="p-3 btn btn-danger btn-fab btn-fab-mini btn-round"><i class="fa fa fa-times"></i></button>').click(function() {
            filter_input.val('');
            search_button.click();
        });

        $(document).keypress(function (event) {
            if (event.which == 13) {
                search_button.click();
            }
        });

        $('#wards-patients-card .dataTables_filter').append(search_button, clear_button);
      },
      'processing': true,
       "ordering": true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
         'url': url
      },
      "language": {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
      },
      search: {
          return: true,
      },
      'columns': [
        { data: 'id' },
        { data: 'consultation_id' },
        { data: 'index' },
        { data: 'ward_name' },
        { data: 'referring_clinic' },
        { data: 'admission_date_time' },
        { data: 'discharge_date_time' },
        
        { data: 'doctor' },
        
      ],
      'columnDefs': [
        {
            "targets": [0],
            "visible": false,
            "searchable": false,

        },
        {
            "targets": [1],
            "visible": false,
            "searchable": false,

        },
        
        {
          orderable: false,
          targets: "no-sort"
        }
      ],
      order: [[2, 'desc']]
    });
    $('#wards-patients-card tbody').on( 'click', 'tr', function () {
        // console.log( table.row( this ).data() );
        var data = table.row( this ).data();
        // var patient_name = data.title + " " + data.first_name + " " + data.last_name;
        
        openChoosePatientAction(data.id,data.consultation_id,data.ward_name,data.referring_clinic,data.admission_date_time,data.discharge_date_time,data.doctor)
        
    } );
    $("#wards-patients-card").show("fast");
    
       
    
  }

  function goBackWardAdmissionsCard(){
    $("#wards-patients-card").hide("fast");
    $("#main-card").show("fast");
  }

  function openChoosePatientAction(id,consult_id,ward_name,referring_clinic,admission_date_time,discharge_date_time,doctor) {
    if(id !== ""){
      ward_record_id = id;
      consultation_id = consult_id;
      
      header_title = `Choose Action <br>Ward Name: <em class="text-primary">${ward_name}</em><br>Referring Clinic: <em class="text-primary">${referring_clinic}</em><br>Admission Date: <em class="text-primary">${admission_date_time}</em><br>Discharge Date: <em class="text-primary">${discharge_date_time}</em><br>Doctor: <em class="text-primary">${doctor}</em>`;
      $("#choose-action-patient-modal .modal-title").html(header_title);
      $("#choose-action-patient-modal").modal("show");
    }
  }

  function patientBioData (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=bio_data&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#patient-bio-data-card .card-body").html(messages);
            $("#patient-bio-data-card").show();
          }else if(!response.user_exists){
            swal({
              title: 'Ooops',
              text: "Sorry This user Does Not Exist",
              type: 'error'
            })
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackBioData (elem,evt) {
    $("#patient-bio-data-card").hide();
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
  }

  function patientAdmissionRecords (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=admission_records&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#patient-admission-data-card .card-body").html(messages);
            $("#patient-admission-data-card").show();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackOnAdmissionInfo(elem,evt){
    $("#patient-admission-data-card").hide();
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
  }

  
  function viewWardConsultations (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=current_dr_records&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#drs-current-consultations .card-body").html(messages);
            $("#drs-current-consultations").show();
            // $("#add-consultation-btn").show("fast");
            $(".table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#drs-current-consultations .card-body").html("<h5 class='text-warning'>No Previous Consultations</h5>");
            $("#drs-current-consultations").show();
            $("#add-consultation-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackCurrentConsultations(elem,evt){
    // $("#add-consultation-btn").hide("fast");
    $("#drs-current-consultations").hide();
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
  }

  function viewConsultation(elem,evt,id,ward_id){
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_consultation&id="+ward_id+"&consultation_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#drs-current-consultations").hide();
            $("#view-consultation-card .card-body").html(messages);
            $("#view-consultation-card").show();
            
            
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackViewConsultation (elem,evt) {
    $("#drs-current-consultations").show();
    
    $("#view-consultation-card").hide();
    
  }

  function requestLabTests (elem,evt) {
    $("#choose-action-patient-modal").modal("hide");
    $("#wards-patients-card").hide();
    $("#request-lab-tests-card").show();
  }

  function goBackRequestTests (elem,evt) {
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
    $("#request-lab-tests-card").hide();
  }

  function onAdmissionTests (elem,evt) {
    
    
    if(ward_record_id !== "" && consultation_id != ""){
      $(".spinner-overlay").show();
        
        $(".spinner-overlay").show();
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_selected_tests_by_consultation_id'); ?>";
        
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true&type=ward&consultation_id="+consultation_id,
          success : function (response) {
            console.log(response)
            $(".spinner-overlay").hide();
            if(response.success == true && response.messages !== ""){
              var messages = response.messages;
              $("#on-admission-selected-tests .card-body").html(messages);
              $("#on-admission-selected-tests #selected-tests-table").DataTable();
              $("#request-lab-tests-card").hide();
              $("#on-admission-selected-tests").show();
            }else{
               $.notify({
              message: "No Data To Display"
              },{
                type : "warning"  
              });
            }
          },error : function () {
            $(".spinner-overlay").hide();
            $.notify({
            message: "Sorry Something Went Wrong"
            },{
              type : "danger"  
            });
          } 
        });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackOnAdmissionSelectedTests (elem,evt) {
    
    $("#on-admission-selected-tests").hide();
    
    $("#request-lab-tests-card").show();
  }

  function viewRadiologyResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;
            var facility_name = response.health_facility_name;

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-card-ward .card-body").html(messages)
            $("#view-patient-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-ward #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            $("#view-patient-results-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsCardWard (elem,evt) {
    $("#on-admission-selected-tests").show();
    $("#view-patient-results-card-ward").hide();
  }

  function viewTestResultImagesWard (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-images-card-ward .card-body").html(messages)
            $("#view-patient-results-images-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsImagesCardWard (elem,evt) {
    $("#on-admission-selected-tests").show();
    $("#view-patient-results-images-card-ward").hide();
  }

  function viewMiniTestResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-card-ward .card-body").html(messages)
            $("#view-patient-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward #test-result-table").DataTable();
            $("#view-patient-results-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewStandardTestResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#on-admission-selected-tests").hide();
            $("#view-patient-results-card-ward .card-body").html(messages);
            $("#view-patient-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward #test-result-table").DataTable();
            $("#view-patient-results-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewTestsSubTestsResultsWard (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_clinic_tracking') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&ward=true",
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#on-admission-selected-tests").hide();
            $("#view-sub-tests-results-card-ward .card-body").html(messages)
            $("#view-sub-tests-results-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-ward #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewSubTestResultWard(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id +"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;
            var facility_name = response.health_facility_name;

            $("#view-sub-tests-results-card-ward").hide();
            $("#view-sub-tests-results-and-images-card-ward .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-ward #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewSubTestResultImagesWard (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#view-sub-tests-results-card-ward").hide();
            $("#view-patient-results-sub-test-images-card-ward .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-ward .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
            $("#view-patient-results-sub-test-images-card-ward").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewSubTestsResultsCardWard (elem,evt) {
    $("#on-admission-selected-tests").show();
    $("#view-sub-tests-results-card-ward").hide();
  }

  
  function goBackFromViewPatientsResultsSubTestImagesCardWard (elem,evt) {
    $("#view-sub-tests-results-card-ward").show();
    $("#view-patient-results-sub-test-images-card-ward").hide();
  }

  function goBackFromViewSubTestsResultsAndImagesCardWard (elem,evt) {
    $("#view-sub-tests-results-card-ward").show();
    $("#view-sub-tests-results-and-images-card-ward").hide();
  }

  function currentTests (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=requested_ward_tests&id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#current-selected-tests .card-body").html(messages);
            $("#current-selected-tests").show();
            $("#current-selected-tests table").DataTable();
            $("#request-lab-tests-card").hide();
            // $("#request-new-tests-btn").show("fast");

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }
 
  function goBackCurrentSelectedTests (elem,evt) {
    $("#current-selected-tests").hide();
    $("#request-lab-tests-card").show();
    $("#request-new-tests-btn").hide("fast");
  }

  function viewRadiologyResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_radiology_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;
            var facility_name = response.health_facility_name;

            $("#current-selected-tests").hide();
            $("#view-patient-results-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            var quill =  new Quill('#view-patient-results-card-ward-during-admission #editor', {
                theme : 'snow',
                readOnly : true,
                modules : {
                    "toolbar": false
                }
            });
            quill.setContents(JSON.parse(comments));
            // $("#request-new-tests-btn").hide("fast");
            $("#view-patient-results-card-ward-during-admission").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsCardWardDuringAdmisssion (elem,evt) {
    $("#current-selected-tests").show();
    $("#request-new-tests-btn").show("fast");
    $("#view-patient-results-card-ward-during-admission").hide();
  }

  function viewTestResultImagesWardDuringAdmisssion (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-images-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsImagesCardWardDuringAdmission (elem,evt) {
    $("#current-selected-tests").show();
    $("#view-patient-results-images-card-ward-during-admission").hide();
    $("#request-new-tests-btn").show("fast");
  }

  

  function viewStandardTestResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_standard_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-card-ward-during-admission .card-body").html(messages);
            $("#view-patient-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward-during-admission #test-result-table").DataTable();
            $("#view-patient-results-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewTestResultImagesWardDuringAdmisssion (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_images_for_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-images-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            
            $("#view-patient-results-images-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewMiniTestResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition. '/' . $fourth_addition .'/view_result_for_mini_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#current-selected-tests").hide();
            $("#view-patient-results-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-patient-results-card-ward-during-admission #test-result-table").DataTable();
            $("#view-patient-results-card-ward-during-admission").show();
            $("#request-new-tests-btn").hide("fast");
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewPatientsResultsSubTestImagesCardWardDuringAdmission (elem,evt) {
    $("#view-sub-tests-results-card-ward-during-admission").show();
    $("#view-patient-results-sub-test-images-card-ward-during-admission").hide();
  }

  function goBackFromViewSubTestsResultsCardWardDuringAdmission (elem,evt) {
    $("#current-selected-tests").show();
    $("#view-sub-tests-results-card-ward-during-admission").hide();
    $("#request-new-tests-btn").show("fast");
  }

  function viewTestsSubTestsResultsWardDuringAdmission (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_test_sub_tests_ward_tracking') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&ward_record_id="+ward_record_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#request-new-tests-btn").hide("fast");
            $("#current-selected-tests").hide();
            $("#view-sub-tests-results-card-ward-during-admission .card-body").html(messages)
            $("#view-sub-tests-results-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Sub Tests For: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-card-ward-during-admission #sub-tests-table-table").DataTable();
            $("#view-sub-tests-results-card-ward-during-admission").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function viewSubTestResultWardDuringAdmission(elem,evt){
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");


    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_for_sub_test_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id +"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" && response.comments != ""){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var comments = response.comments;
            var facility_name = response.health_facility_name;

            $("#view-sub-tests-results-card-ward-during-admission").hide();
            $("#view-sub-tests-results-and-images-card-ward-during-admission .card-body").html(messages)
            $("#view-sub-tests-results-and-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em><br>Test Name: <em class='text-primary'>" +test_name + "</em>");
            $("#view-sub-tests-results-and-images-card-ward-during-admission #test-result-table").DataTable();
            $("#view-sub-tests-results-and-images-card-ward-during-admission").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }

  function goBackFromViewSubTestsResultsAndImagesCardWardDuringAdmission (elem,evt) {
    $("#view-sub-tests-results-card-ward-during-admission").show();
    $("#view-sub-tests-results-and-images-card-ward-during-admission").hide();
  }

  function viewSubTestResultImagesWardDuringAdmission (elem,evt) {
    elem = $(elem);
    var initiation_code = elem.attr("data-initiation-code");
    var main_test_id = elem.attr("data-main-test-id");
    var sub_test_id = elem.attr("data-sub-test-id");
    var facility_id = elem.attr("data-facility-id");

    if(initiation_code != "" && main_test_id != "" && sub_test_id != ""){
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_result_sub_test_images_for_tracking_clinic_doctor') ?>";

      $(".spinner-overlay").show();
      $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        responseType : "json",
        data : "health_facility_id="+facility_id+"&initiation_code="+initiation_code+"&main_test_id="+main_test_id+"&sub_test_id="+sub_test_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          console.log(response)
          if(response.success && response.messages != "" && response.patient_name != "" && response.test_name != "" ){
            var messages = response.messages;
            var patient_name = response.patient_name;
            var test_name = response.test_name;
            var facility_name = response.health_facility_name;
            

            $("#view-sub-tests-results-card-ward-during-admission").hide();
            $("#view-patient-results-sub-test-images-card-ward-during-admission .card-body").html(messages)
            $("#view-patient-results-sub-test-images-card-ward-during-admission .card-title").html("Facility Name: <em class='text-primary'>" +facility_name + "</em><br>Initiation Code: <em class='text-primary'>" +initiation_code + "</em>");
            
            $("#view-patient-results-sub-test-images-card-ward-during-admission").show();
            
          }else if(response.invalid_initiation_code){
            swal({
              title: 'Error',
              text: "This Initiation Code Is Invalid",
              type: 'error'             
            })
          }else{
            swal({
              title: 'Error',
              text: "Something Went Wrong.",
              type: 'error'             
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
           swal({
            title: 'Error',
            text: "Something Went Wrong. Please Check Your Internet Connection",
            type: 'error'             
          })
        }
      });
    }
  }


  function goBackFromViewPatientsResultsSubTestImagesCardWardDuringAdmission (elem,evt) {
    $("#view-sub-tests-results-card-ward-during-admission").show();
    $("#view-patient-results-sub-test-images-card-ward-during-admission").hide();
  }

  function viewVitalSigns (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=vital_signs&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#vital-signs-card .card-body").html(messages);
            $("#vital-signs-card").show();
            $("#vital-signs-card .table").DataTable();
            $("#add-vital-signs-btn").show("fast");
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else if(response.no_records){
            swal({
              title: 'Ooops',
              text: "Sorry No Records To Display",
              type: 'warning'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackVitalSignsInfo(elem,evt){
    $("#vital-signs-card").show();
   
    $("#add-vital-signs-btn").show("fast");
    $("#vital-signs-info-card").hide();
  }

  function goBackVitalSigns (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#vital-signs-card").hide();
    
    $("#add-vital-signs-btn").hide("fast");
  }

  function loadVitalSignsInfo(elem,evt,ward_id,date) {
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=vital_signs_info&id="+ward_id+"&date="+date,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#vital-signs-card").hide();
            $("#vital-signs-info-card .card-body").html(messages);
            $("#vital-signs-info-card .card-title").html("Vital Signs Entered On "+ date);
            $(".table-test").DataTable();
            $("#add-vital-signs-btn").hide("fast");
            $("#vital-signs-info-card").show();

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function writeReport(elem,evt){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=get_reports&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#report-card .card-body").html(messages);
            $("#report-card").show();
            
            $("#report-card .table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackReportCard (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#report-card").hide();
  }

  function goBackReportInfoCard (elem,evt) {
    $("#report-card").show();
    $("#report-info-card").hide();
  }

  function viewReportInfo(elem,evt,id) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=report_info&id="+ward_record_id+"&report_id="+id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#report-card").hide();
            $("#report-info-card .card-body").html(messages);
            $("#report-info-card .card-title").html("Report Info");
            
            $("#report-info-card").show();
            

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function inputOutputChart (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=input_output&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#input-output-card .card-body").html(messages);
            $("#input-output-card").show();
            $("#add-input-output-btn").show("fast");
            $("#input-output-card .table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackInputOutputInfo (elem,evt) {
    $("#input-output-card").show();
    $("#add-input-output-btn").show("fast");
    $("#input-output-info-card").hide(); 
  }

  function goBackInputOutput (elenm,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    
    $("#input-output-card").hide();
    $("#add-input-output-btn").hide("fast");
  }

  function loadInputOutputInfo(elem,evt,ward_id,date) {
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=input_output_info&id="+ward_id+"&date="+date,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#input-output-card").hide();
            $("#input-output-info-card .card-body").html(messages);
            $("#input-output-info-card .card-title").html("Data Entered On "+ date);
            $("#input-output-info-table").DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false
            });
            $("#add-input-output-btn").hide("fast");
            $("#input-output-info-card").show();

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function otherCharts (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_other_charts&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#view-other-charts-card .card-body").html(messages);
            $("#view-other-charts-card").show();
            
            $("#view-other-charts-card .table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }


  function goBackViewOtherCharts(elem,evt){
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#view-other-charts-card").hide();    
  }

  function goBackOtherChartsInfo (elem,event) {
    $("#view-other-charts-card").show();
    $("#other-charts-info-card").hide();
  }

  function loadOtherChartInfo(elem,event,other_chart_id){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=other_chart&id="+ward_record_id+"&other_chart_id="+other_chart_id,
        success : function (response) {
          console.log(response);
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            var chart_name = response.chart_name;
            $("#view-other-charts-card").hide();
            $("#other-charts-info-card .card-title").html(chart_name);
            $("#other-charts-info-card .card-body").html(messages);
            
            $("#other-charts-info-card").show();
            
            $("#other-charts-info-card .table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

    function loadOtherChartInfo1(elem,evt,other_chart_id,ward_id, date){
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=other_chart_info1&id="+ward_id+"&date="+date+"&other_chart_id="+other_chart_id,
        success : function (response) {
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#other-charts-info-card").hide();
            $("#other-charts-info-card1 .card-body").html(messages);
            $("#other-charts-info-card1 .card-title").html("Data Entered On "+ date);
            $("#other-charts-info-table1").DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false
            });
            
            $("#other-charts-info-card1").show();

          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Sorry This Record Does Not Exist",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackOtherChartsInfo1 (elem,evt) {
    $("#other-charts-info-card").show();
    $("#other-charts-info-card1").hide();
  }

  function viewClinicalNotes(elem,evt){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_clinical_notes&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html(messages);
            $("#clinical-notes-card").show();
            
            $("#clinical-notes-card .table-test").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
            $("#clinical-notes-card").show();
            $("#add-clinical-note-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackClinicalNotes (elem,evt) {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#clinical-notes-card").hide();
    $("#add-clinical-note-btn").hide("fast");
  }

  function goBackViewClinicalNote (elem,evt) {
    $("#clinical-notes-card").show();
    $("#view-clinical-note-card").hide();
  }

  function viewClinicalNote(elem,evt,id,ward_id){
    
    if(ward_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=view_clinical_note&id="+ward_id+"&clinical_note_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#clinical-notes-card").hide();
            $("#view-clinical-note-card .card-body").html(messages);
            $("#view-clinical-note-card").show();
            
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function requestServices(elem,evt){
   
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=request_services_doctor&id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#request-services-card .card-body").html(messages);
            $("#request-services-card").show();
            $("#request-services-card #ward-services-table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
            $("#clinical-notes-card").show();
            $("#add-clinical-note-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackRequestServices(elem,evt)  {
    $("#wards-patients-card").show();
    $("#choose-action-patient-modal").modal("show");
    $("#request-services-card").hide();
    
  }

  function requestWardService(elem,evt,id,name,type,price){
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();

      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_patient_ward_info'); ?>";

      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "view_records=true&type=requested_services_doctor&id="+ward_record_id+"&service_id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success && response.messages != ""){
            var messages = response.messages;
            $("#request-services-card").hide();
            $("#requested-services-card .card-body").html(messages);
            $("#requested-services-card").show();
            $("#requested-services-table").DataTable();
          }else if(response.invalid_id){
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'error'
            })
          }else if(response.no_records){
            
            $("#wards-patients-card").hide();
            $("#choose-action-patient-modal").modal("hide");
            $("#clinical-notes-card .card-body").html("<h5 class='text-warning'>No Records To Display Here</h5>");
            $("#clinical-notes-card").show();
            $("#add-clinical-note-btn").show("fast");
          }else{
            swal({
              title: 'Ooops',
              text: "Something Went Wrong",
              type: 'warning'
            })
          }
        },error : function () {
          $(".spinner-overlay").hide();
          swal({
            title: 'Ooops',
            text: "Sorry Something Went Wrong",
            type: 'error'
          })
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackRequestedServices (elem,evt) {
    $("#request-services-card").show();
    $("#requested-services-card").hide();
  }

  function viewMedicationChart (elem,evt) {
    $("#choose-action-patient-modal").modal("hide");
    $("#wards-patients-card").hide();
    $("#medication-chart-card").show();
  }

  function goBackMedicationChart (elem,evt) {
    $("#choose-action-patient-modal").modal("show");
    $("#wards-patients-card").show();
    $("#medication-chart-card").hide();
  }

  function onAdmissionDrugs (elem,evt) {
    if(ward_record_id != "" && consultation_id != ""){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_clinic'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "consultation_id="+consultation_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $("#on-admission-drugs-card .card-body").html(messages);
            $("#on-admission-drugs-card").show();
            $("#on-admission-drugs-card .selected-drugs-table").DataTable();
            $("#medication-chart-card").hide();
          }else{
             $.notify({
            message: "No Data To Display"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackOnAdmissionDrugs (elem,evt) {
    $("#on-admission-drugs-card").hide();
    $("#medication-chart-card").show();
  }

  function currentDrugs (elem,evt) {
    
    if(ward_record_id !== ""){
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_selected_drugs_ward'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "ward_record_id="+ward_record_id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $("#current-drugs-card .card-body").html(messages);
            $("#current-drugs-card").show();
            $("#current-drugs-card .selected-drugs-table").DataTable();
            $("#medication-chart-card").hide();
            $("#request-new-drugs-btn").show("fast");
          }else{
             $.notify({
            message: "No Data To Display"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function loadCurrentDrugsInfo (elem,evt) {
    var ward_record_id = $("#choose-action-patient-modal").attr("data-id");
    var record_id = $("#choose-action-patient-modal").attr("data-record-id");
    var initiation_code = $(elem).attr("data-initiation-code");
    if(initiation_code !== ""){
      $(".spinner-overlay").show();
        
       
      $(".spinner-overlay").show();
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_previously_drug_selection_by_init_code_ward'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "ward_record_id="+ward_record_id+"&initiation_code="+initiation_code,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true && response.messages !== ""){
            var messages = response.messages;
            $("#current-drugs-card-info .card-body").html(messages);
            $("#current-drugs-card-info").show();
            $("#current-drugs-card-info .selected-drugs-table").DataTable();
            $("#current-drugs-card").hide();
            $("#request-new-drugs-btn").hide("fast");
          }else{
             $.notify({
            message: "No Data To Display"
            },{
              type : "warning"  
            });
          }
        },error : function () {
          $(".spinner-overlay").hide();
          $.notify({
          message: "Sorry Something Went Wrong"
          },{
            type : "danger"  
          });
        } 
      });   
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackCurrentDrugsInfo (elem,evt) {
    $("#current-drugs-card-info").hide();
    $("#current-drugs-card").show();
    $("#request-new-drugs-btn").show("fast");
  }

  function goBackCurrentDrugs (elem,evt) {
    $("#current-drugs-card").hide();
    $("#medication-chart-card").show();
    $("#request-new-drugs-btn").hide("fast");
  }

  function loadMedicationChartForDrug (elem,evt,id) {
    var status  = $(elem).attr("data-status");
    if(status != ""){
      if(status == "dispatched"){
        $(".spinner-overlay").show();
        
        var initiation_code = $(elem).attr("data-initiation-code");
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/load_medication_chart_for_drug'); ?>";
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : "show_records=true&ward_record_id="+ward_record_id+"&drug_selected_id="+id+"&initiation_code="+initiation_code,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.messages !== ""){
              $("#current-drugs-card-info").hide();
              $("#medication-chart-for-drug-card .card-body").html(response.messages);
              $("#medication-chart-for-drug-card").show();
              $("#medication-chart-for-drug-card #medication-chart-table").DataTable({
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
              });
              
            }else{
              $.notify({
              message:"No Records To Display"
              },{
                type : "warning"  
              });
            }
          },error :function () {
            $(".spinner-overlay").hide();
            $.notify({
            message:"Please Check Your Internet Connection And Try Again"
            },{
              type : "danger"  
            });
          }
        });
      }else{
        swal({
          title: 'Warning',
          text: "This Drug Has To Be Dispensed And Dispatched By Pharmacy To View Medication Chart",
          type: 'error',
          
        })
      }
    } 
  }

  function goBackMedicationChartForDrug (elem,evt) {
    $("#current-drugs-card-info").show();
    $("#medication-chart-for-drug-card").hide();
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
          
         <h3 class="text-bold" style="margin-top: 30px;"><?php echo $title ?></h3>

         <h4>Hospital Number:  <em class="text-primary"><?php echo $patient_facility_info->registration_num ?></em></h4>
          <div class="row">
            <div class="col-sm-12">

              <div class="card" id="current-drugs-card-info" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentDrugsInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Selected Drugs</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="medication-chart-for-drug-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackMedicationChartForDrug(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="current-drugs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentDrugs(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Drugs Selected During Admission</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="on-admission-drugs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionDrugs(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Drugs Selected On Admission</h3>
                </div>
                <div class="card-body">
                 
                </div>
              </div>

              <div class="card" id="medication-chart-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackMedicationChart(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Choose Action</h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-primary" onclick="onAdmissionDrugs(this,event)">View Drugs Selected On Admission</button>
                  <button class="btn btn-info" onclick="currentDrugs(this,event)">View Drugs Selected During Admission</button>
                </div>
              </div>

              <div class="card" id="requested-services-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestedServices(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Requests Of Service</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="request-services-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestServices(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Services</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="clinical-notes-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackClinicalNotes(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Clinical Notes</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-clinical-note-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackViewClinicalNote(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Clinical Note</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="other-charts-info-card1" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOtherChartsInfo1(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-other-charts-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackViewOtherCharts(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Other Charts In This Facility</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="other-charts-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOtherChartsInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading"></h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>



              <div class="card" id="input-output-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackInputOutputInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Vital Signs For</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="input-output-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackInputOutput(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Input Output Chart</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="report-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReportCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Previous Reports</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="report-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReportInfoCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h2 class="card-title" id="welcome-heading"></h2>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="vital-signs-info-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackVitalSignsInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Vital Signs For</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="vital-signs-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackVitalSigns(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Vital Signs Entered</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>


              <div class="card" id="view-patient-results-sub-test-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-images-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardWardDuringAdmission(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-card-ward-during-admission" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCardWardDuringAdmisssion(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="current-selected-tests" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentSelectedTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Tests Selected During Admission</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="view-sub-tests-results-and-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewSubTestsResultsAndImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-patient-results-sub-test-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsSubTestImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="view-sub-tests-results-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;"></h3>
                  <button onclick="goBackFromViewSubTestsResultsCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>
              <div class="card" id="view-patient-results-images-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsImagesCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>


              <div class="card" id="view-patient-results-card-ward" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title"></h3>
                  <button onclick="goBackFromViewPatientsResultsCardWard(this,event)" class="btn btn-round btn-warning">Go Back</button>
                  
                </div>
                <div class="card-body">

                </div>
              </div>

              <div class="card" id="on-admission-selected-tests" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionSelectedTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Selected Tests On Admission</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="request-lab-tests-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestTests(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Choose Action</h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-primary" onclick="onAdmissionTests(this,event)">View Tests Selected On Admission</button>
                  <button class="btn btn-info" onclick="currentTests(this,event)">View Tests Selected During Admission</button>
                </div>
              </div>

              <div class="card" id="view-consultation-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackViewConsultation(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Consultations</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="drs-current-consultations" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackCurrentConsultations(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Dr's Consultations</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="patient-admission-data-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAdmissionInfo(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Patient On Admission Info</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="patient-bio-data-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackBioData(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Patient Bio Data</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="wards-patients-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Ward Admissions</h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackWardAdmissionsCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>
              


              <div class="card" id="clinic-consultations-details-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">Clinic Consultation Details</h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackClinicConsultationDetailsCard(this,event)">Go Back</button>
                </div>
                <div class="card-body">
                  
                                    
                </div> 
              </div>
              
              

              <div class="card" id="clinic-consultations-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" style="text-transform: capitalize;">All Clinic Consultations</h3>
                  <button  type="button" class="btn btn-round btn-warning" onclick="goBackClinicConsultationsCard(this,event)">Go Back</button>
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
                  <table class="table">
                    <tbody>

                      
                      

                      <tr >
                        <td>1</td>
                        <td><a href="#" onclick="viewClinicConsultations(this,event)">Clinic Consultations</a></td>
                      </tr>

                      <tr >
                        <td>2</td>
                        <td><a href="#" onclick="viewWardAdmissions(this,event)">Previous Ward Admissions</a></td>
                      </tr>

                      
                      
                      
                    </tbody>
                  </table>
                </div>
              </div>

             
              
            </div>
          </div>



         
          
          <!-- Modals -->

          <div class="modal fade" data-backdrop="static" id="choose-action-patient-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" style="text-transform: capitalize;"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <p id="expiry-info" style="font-size: 11px; text-transform: capitalize;" class="text-center"></p>
                    <div class="table-responsive">
      
                      <table class="table table-striped table-bordered  nowrap hover display" id="select-options-table-3" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Option</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td onclick="patientBioData(this,event)">View Patients Bio Data</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td onclick="patientAdmissionRecords(this,event)">View Consultation Records On Admission</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td onclick="viewWardConsultations(this,event)">View Dr's Consultations</td>
                          </tr>
                         
                          <tr>
                            <td>4</td>
                            <td onclick="requestLabTests(this,event)">View Tests Requested By Doctor</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td onclick="viewMedicationChart(this,event)">View Medication Chart</td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td onclick="viewVitalSigns(this,event)">View Vital Signs</td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td onclick="writeReport(this,event)">View Patient Reports</td>
                          </tr>
                          <tr>
                            <td>8</td>
                            <td onclick="inputOutputChart(this,event)">View Patient Input And Output Chart</td>
                          </tr>
                          <tr>
                            <td>9</td>
                            <td onclick="otherCharts(this,event)">View Other Patient Charts</td>
                          </tr>
                          <tr>
                            <td>10</td>
                            <td onclick="viewClinicalNotes(this,event)">View Patients Clinical Notes</td>
                          </tr>
                          <tr>
                            <td>11</td>
                            <td onclick="requestServices(this,event)">View Requested Services For Patient</td>
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
                          <td onclick="initiateConsultation(this,event)" class="text-primary">Initiate Consultation</td>
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

          <div id="go-back-from-consultation-details-btn" onclick="goBackClinicConsultationDetailsCard(this,event)" rel="tooltip" data-toggle="tooltip" title="Go Back" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
            <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
              <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_backward</i>

            </div>
          </div>

        </div>
      </div>
      <p id="hn" style="display: none;"></p>
      </div>
      <footer class="footer">
        
       
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {

    });

    

</script>
