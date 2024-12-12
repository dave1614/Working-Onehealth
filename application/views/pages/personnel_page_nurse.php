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
  var consultation_id = "";
  var patient_user_id = "";
  var current_card = "";

  function getTodayCurrentFullDate(){
    var date = new Date();

    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    let year = date.getFullYear();

    return `${year}-${month}-${day}`
  }

   function getYesterdayCurrentFullDate(){
    var date = new Date();
    date.setDate(date.getDate() - 1);

    // let day = date.getDate();
    // let month = date.getMonth() + 1;
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    let year = date.getFullYear();

    return `${year}-${month}-${day}`
  }

  function viewPreviousNotes (elem,evt) {
    
    
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_patients_medical_record/') ?>" + patient_user_id;
    // console.log(url)
    window.open(url, "_blank");
  }


  function goBackOffAppointmentPatientCard (elem) {
    $("#off-appointment-patient-card").hide();
    $("#off-appointment-patients-card").show();
  }

  function goBackOnAppointmentPatientCard (elem) {
    $("#on-appointment-patient-card").hide();
    $("#on-appointment-card").show(); 
  }

  function loadPatientBioOnApp (id) {
    $(".spinner-overlay").show();
    consultation_id = id;
    
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.patient_full_name != "" && response.registration_num != "" && response.patient_user_id != ""){
          var patient_full_name = response.patient_full_name;
          var registration_num = response.registration_num;
          patient_user_id = response.patient_user_id;
          $("#on-appointment-patient-card .card-title").html("Input Vital Signs <br>Patient Name: <em class='text-primary'>"+patient_full_name+"</em> <br>Registration Number: <em class='text-primary'>"+registration_num+"</em>")

          $("#on-appointment-patient-card .vital-signs-form input").val("");
          $("#on-appointment-card").hide();
          $("#on-appointment-patient-card").show();
         
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

  function loadPatientBioOffApp (id) {
    consultation_id = id;
    
    $(".spinner-overlay").show();
        
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.patient_full_name != "" && response.registration_num != "" && response.patient_user_id != ""){
          var patient_full_name = response.patient_full_name;
          var registration_num = response.registration_num;
          patient_user_id = response.patient_user_id;
          $("#off-appointment-patient-card .card-title").html("Input Vital Signs <br>Patient Name: <em class='text-primary'>"+patient_full_name+"</em> <br>Registration Number: <em class='text-primary'>"+registration_num+"</em>")

          $("#off-appointment-patient-card .vital-signs-form input").val("");
          
          $("#off-appointment-patients-card").hide();
          $("#off-appointment-patient-card").show();
          $("#off-appointment-patient-card #select-more-options-table").DataTable();
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

  function goBackPreviouslyRegistered(elem) {
    $("#off-appointment-patients-card").hide();
    $("#previously-registered-patients-card").hide();
    $("#choose-action-card").show();
  }

  function goDefault() {
    
    document.location.reload();
  }

  function goBackEditBio() {
    $("#edit-patient-bio-data-card").hide();
    $("#previously-registered-patients-card").show();
  }

  function loadPatientBioDataEdit(id, user_id) {
    $(".spinner-overlay").show();
    consultation_id = id
    patient_user_id = user_id;
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-edit'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.patient_full_name != "" && response.registration_num != "" && response.patient_user_id != ""){
          var patient_full_name = response.patient_full_name;
          var registration_num = response.registration_num;
          patient_user_id = response.patient_user_id;
          $("#edit-patient-bio-data-card .card-title").html("Input Vital Signs <br>Patient Name: <em class='text-primary'>"+patient_full_name+"</em> <br>Registration Number: <em class='text-primary'>"+registration_num+"</em>")
          $("#previously-registered-patients-card").hide();
          $("#edit-patient-bio-data-card").show();
          $("#edit-patient-bio-data-card #select-more-options-table").DataTable();
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

  function newPatients (elem,evt) {
    evt.preventDefault();
    
    current_card = "edit-patient-bio-data-card";
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view-registered-patients-records-paid'); ?>";
    
    

    $("#choose-action-card").hide();
    var html = `<div class="table-div material-datatables table-responsive" style=""><table class="table table-test table-striped table-bordered nowrap hover display" id="new-patients-table" cellspacing="0" width="100%" style="width:100%"><thead><tr><th>Id</th><th class="sort">#</th><th class="no-sort">Patient Name</th><th class="no-sort">Records Officer</th><th class="no-sort">Sex</th><th class="no-sort">Registration Number</th><th class="no-sort">Age</th><th class="no-sort">Date / Time</th></tr></thead></table></div>`;

   
    $("#previously-registered-patients-card .card-body").html(html);
    

    var table = $("#previously-registered-patients-card #new-patients-table").DataTable({
      
      initComplete : function() {
        var self = this.api();
        var filter_input = $('#previously-registered-patients-card .dataTables_filter input').unbind();
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

        $('#previously-registered-patients-card .dataTables_filter').append(search_button, clear_button);
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
        { data: 'patient_full_name' },
        
        { data: 'records_officer' },
        { data: 'sex' },
        { data: 'registration_num' },
        
        { data: 'age' },
        { data: 'date_time' },
        
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
    $('#previously-registered-patients-card tbody').on( 'click', 'tr', function () {
        // console.log( table.row( this ).data() );
        var data = table.row( this ).data();
        // var patient_name = data.title + " " + data.first_name + " " + data.last_name;
        loadPatientBioDataEdit(data.id)
        
    } );
    $("#previously-registered-patients-card").show("fast");

  }

  function selectTimeRangeOffAppointment(elem,event){
    elem = $(elem);
    var start_date = elem.parent().find('.start-date').val();
    var end_date = elem.parent().find('.end-date').val();
    

    console.log(start_date)
    console.log(end_date)
    
    $(".spinner-overlay").show();
        
    current_card = "off-appointment-patient-card";
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_off_appointments_clinic_nurse'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&start_date="+start_date+"&end_date="+end_date,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success == true && response.messages != ""){
          var messages = response.messages;
          $("#off-appointment-patients-card .card-body").html(response.messages);
          
          $("#off-appointment-patients-card #off-appointment-patients-table").DataTable();
        }
        else{
         $.notify({
          message:"No Record To Display"
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
  }

  function offAppointment (elem,evt) {
    evt.preventDefault();
    elem = $(elem);

    var start_date = getYesterdayCurrentFullDate();
    var end_date = getTodayCurrentFullDate();
    console.log(start_date + " " + end_date)
    $(".spinner-overlay").show();
          
    current_card = "off-appointment-patient-card";
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_off_appointments_clinic_nurse'); ?>";
    
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
          $("#off-appointment-patients-card .card-body").html(response.messages);
          $("#choose-action-card").hide();
          $("#off-appointment-patients-card").show();
          $("#off-appointment-patients-card #off-appointment-patients-table").DataTable();
        }
        else{
          $.notify({
          message: "No Data To Display"
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

    // current_card = "off-appointment-patient-card";
    // var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_off_appointments_clinic_nurse'); ?>";
    
    

    // $("#choose-action-card").hide();
    // var html = `<div class="table-div material-datatables table-responsive" style=""><table class="table table-test table-striped table-bordered nowrap hover display" id="off-appointment-patients-table" cellspacing="0" width="100%" style="width:100%"><thead><tr><th>Id</th><th class="sort">#</th><th class="no-sort">Patient Name</th><th class="no-sort">Records Officer</th><th class="no-sort">Sex</th><th class="no-sort">Registration Number</th><th class="no-sort">Age</th><th class="no-sort">Date / Time</th></tr></thead></table></div>`;

   
    // $("#off-appointment-patients-card .card-body").html(html);
    

    // var table = $("#off-appointment-patients-card #off-appointment-patients-table").DataTable({
      
    //   initComplete : function() {
    //     var self = this.api();
    //     var filter_input = $('#off-appointment-patients-card .dataTables_filter input').unbind();
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

    //     $('#off-appointment-patients-card .dataTables_filter').append(search_button, clear_button);
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
    //     { data: 'patient_full_name' },
        
    //     { data: 'records_officer' },
    //     { data: 'sex' },
    //     { data: 'registration_num' },
        
    //     { data: 'age' },
    //     { data: 'date_time' },
        
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
    //   order: [[1, 'desc']],
    //   pageLength: 300,
    // });
    // $('#off-appointment-patients-card tbody').on( 'click', 'tr', function () {
    //     // console.log( table.row( this ).data() );
    //     var data = table.row( this ).data();
    //     // var patient_name = data.title + " " + data.first_name + " " + data.last_name;
    //     loadPatientBioOffApp(data.id)
        
    // } );
    // $("#off-appointment-patients-card").show("fast");

  }

  function onAppointment (elem,evt) {
    evt.preventDefault();
    $(".spinner-overlay").show();
    
    current_card = "on-appointment-patient-card";
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_on_appointments_clinic_nurse'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success){
          $("#on-appointment-card .card-body").html(response.messages);
          $("#on-appointment-card #on-appoinment-table").DataTable();
          $("#choose-action-card").hide();
          $("#on-appointment-card").show();
          
        }
        else{
          $.notify({
          message: "Sorry Something Went Wrong"
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

  }

  
  function openPatientBioDataForm(elem,evt) {
    evt.preventDefault();
    $("#main-card").hide();
    $("#patient-bio-data").show();
  }

  function inputVitalSigns(elem,evt) {
    $("#main-card").hide();
    $("#choose-action-card").show();
  }

  function goBackReferralsCard (elem,evt) {
    $("#choose-action-card").show();
    $("#referrals-card").hide();
  }

  function goBackOnAppointmentCard (elem,evt) {
    $("#choose-action-card").show();
    $("#on-appointment-card").hide();
  }

  function viewReferralsOrConsults(elem,evt) {
    evt.preventDefault();
    swal({
      title: 'Choose Action',
      text: "Do You Want To: ",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'View Referrals',
      cancelButtonText: 'View Consults'
    }).then(function(){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_referrals_to_your_clinic_nurse'); ?>";
      
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
            $("#referrals-card .card-body").html(response.messages);
            $("#choose-action-card").hide();
            $("#referrals-card").show();
            $("#referrals-card #referrals-table").DataTable();
          }
          else{
           $.notify({
            message:"No Record To Display"
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
        $(".spinner-overlay").show();
            
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_consult_to_your_clinic_nurse'); ?>";
        
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
              $("#referrals-card .card-body").html(response.messages);
              $("#choose-action-card").hide();
              $("#referrals-card").show();
              $("#referrals-card #referrals-table").DataTable();
            }
            else{
             $.notify({
              message:"No Record To Display"
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
      }
    });
  }

  function viewReferralInfo (elem,evt,id) {
    if(id != ""){
      $(".spinner-overlay").show();
          
      var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_referral_info_nurse'); ?>";
      
      $.ajax({
        url : url,
        type : "POST",
        responseType : "json",
        dataType : "json",
        data : "show_records=true&id="+id,
        success : function (response) {
          console.log(response)
          $(".spinner-overlay").hide();
          if(response.success == true){
            $("#referral-card .card-body").html(response.messages);
            $("#referrals-card").hide();
            $("#referral-card").show();
            $("#proceed-referral-to-doctor").attr("data-id",id);
            $("#proceed-referral-to-doctor").show("fast");
          }
          else{
           $.notify({
            message:"No Record To Display"
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
    }
  }

  function goBackReferralCard (elem,evt) {
    $("#referrals-card").show();
    $("#referral-card").hide();
    $("#proceed-referral-to-doctor").hide("fast");
  }

  function proceedReferralToDoctor (elem,evt) {

    var id = $(elem).attr("data-id");
    if(id != ""){
      $("#input-vital-signs-referral-modal").modal("show");
      $("#input-vital-signs-referral-form").attr("data-id",id);
    }
  }

  function bmiCalc(weight, height){

    var bmiResult = weight / Math.pow(height, 2);
    var roundedResult = Math.floor(bmiResult);
    // console.log(roundedResult)
    return roundedResult;

    // var height_squ = height * height;
    // var bmi = weight / height_squ;
    // return bmi.toFixed(2);
  }

  

  function recalculateBmi(type){
    
    var card = type == "off" ? 'off-appointment-patient-card' : type == 'on' ? 'on-appointment-patient-card' : 'edit-patient-bio-data-card';
    // console.log('.' + card + ' #medical_weight')
    var weight = $('#' + card + ' #medical_weight').val();
    var height = $('#' + card + ' #medical_height').val();

    // console.log(weight + ' ' + height)
    var bmi = "";
    if(weight != "" && height != ""){
      weight = Number(weight);
      height = Number(height);

      bmi = bmiCalc(weight, height / 100);
      
      
    }
    // console.log(bmi)
    $('#' + card + ' #bmi_disp').html(bmi)
  }

  function loadAllServices(elem,evt){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_all_services_clinic_nurse'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true",
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#"+ current_card).hide();
          
          $("#all-services-card .card-body").html(messages);
          $("#all-services-card #all-services-table").DataTable();
          $("#all-services-card").show();
          
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
  }

  function goBackAllServicesCard(){
    $("#"+ current_card).show();
          
    
    $("#all-services-card").hide();
    window.scrollTo(0,document.body.scrollHeight);
  }

  function requestWardAndClinicService(elem,evt,id,name,type,price){
    
    if(consultation_id !== ""){
     
      swal({
        title: 'Proceed?',
        text: "Are You Sure You Want To Proceed? ",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Proceed',
        cancelButtonText : "Cancel"
      }).then(function(){
        $("#all-services-card").hide();
        $("#request-service-card .card-title").html("Request "+name+" Service");
        $("#request-service-form #amount-form-group").show();
        if(type == "rate"){
          $("#request-service-form").attr("data-type","rate");
          $("#request-service-form #quantity-form-group").show();
          $("#request-service-form #quantity").attr("onkeyup","amountKeyUp(this,event,"+price+")");
          $("#request-service-form").attr("data-id",id);
          $("#request-service-card").show();
        }else{
          var url = $("#request-service-form").attr("action");
          
          var service_id = id;
          var form_data = [];

          form_data = form_data.concat({
            "name" : "consultation_id",
            "value" : consultation_id
          })

          form_data = form_data.concat({
            "name" : "service_id",
            "value" : service_id
          })

          form_data = form_data.concat({
            "name" : "type",
            "value" : "fixed"
          })
          // console.log(url)
          // console.log(form_data)
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
              if(response.success && response.user_type != ""){
                var user_type = response.user_type;

                if(user_type == "nfp"){
                  var text = "Service Successfully Requested";
                }else{
                  var text = "Service Successfully Requested. Please Direct Patient To Hospital Teller For Payment.";
                }

                $.notify({
                message: text
                },{
                  type : "success"  
                });
                $("#request-service-card").hide();
                loadAllServices(this,event);
              }else if(response.invalid_id){
                swal({
                  title: 'Ooops',
                  text: "Something Went Wrong",
                  type: 'warning'
                })
              }else{
                $.each(response.messages, function (key,value) {

                var element = $('#request-service-form #'+key);
                
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
      });
    
    }else{
      swal({
        title: 'Ooops',
        text: "Sorry Something Went Wrong",
        type: 'error'
      })
    }
  }

  function goBackRequestService(elem,evt)  {
    $("#all-services-card").show();
    $("#request-service-form #quantity-form-group").hide();
    $("#request-service-form #amount-form-group").hide();
    $("#request-service-form #quantity").val("");
    $("#request-service-form #amount").val("");
    $("#request-service-form #quantity").attr("onkeyup","");
    $("#request-service-card").hide();
  }

  function amountKeyUp(elem,evt,price){
    elem = $(elem);
    var val = elem.val();
    if(val !== "" && price !== ""){
      var final_price = val * price;
      $("#request-service-form #amount").val(final_price);
    }
    
  }

  function viewPreviouslyRequestedServices(elem,evt){
    $(".spinner-overlay").show();
          
    var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/view_previously_requested_services_clinic_nurse'); ?>";
    
    $.ajax({
      url : url,
      type : "POST",
      responseType : "json",
      dataType : "json",
      data : "show_records=true&consultation_id="+consultation_id,
      success : function (response) {
        console.log(response)
        $(".spinner-overlay").hide();
        if(response.success && response.messages != ""){
          var messages = response.messages;
          $("#"+ current_card).hide();
          
          $("#previous-selected-services-card .card-body").html(messages);
          $("#previous-selected-services-card #previous-selected-services-table").DataTable();
          $("#previous-selected-services-card").show();
          
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
  }

  function goBackPreviousSelectedServicesCard(elem, evt){
    $("#previous-selected-services-card").hide();
    $("#"+ current_card).show();
    window.scrollTo(0,document.body.scrollHeight);
    
    
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
            <div class="col-sm-12">

              <div class="card" id="request-service-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackRequestService(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Request This Service</h3>
                </div>
                <div class="card-body">
                  <?php  
                    $attr = array('id' => 'request-service-form');
                    echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/request_new_service_clinic_nurse',$attr);
                  ?>
                    
                    <div class="wrap">
                      <h4 id="price-info"></h4>
                      <div class="form-row">
                        <div class="form-group col-sm-12" style="display: none;" id="quantity-form-group">
                          <label for="quantity" class="label-control"><span class="form-error1">* </span> Enter Quantity: </label>
                          <input name="quantity" id="quantity" class="form-control" onkeyup="" type="number" />
                          <span class="form-error"></span>
                        </div>
                      </div>
                      <input type="submit" class="btn btn-primary">
                    </div>
                  </form>

                </div>
              </div>
              

              <div class="card" id="previous-selected-services-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviousSelectedServicesCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">Previously Selected Services</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="all-services-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackAllServicesCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title">All Services</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="main-card">
                <div class="card-header">
                  <h3 class="card-title">Choose Action: </h4>
                </div>
                <div class="card-body" style="padding-top: 60px;">
                  
                  <button onclick="inputVitalSigns(this,event)" class="btn btn-primary">Perform Functions</button>
                </div>
              </div>

              <div class="card" id="choose-action-card" style="display: none;">
                <div class="card-header">
                  
                </div>
                <div class="card-body">
                  <h4 style="margin-bottom: 40px;" id="quest">Choose Action: </h4>
                  <button onclick="newPatients(this,event)" class="btn btn-primary">New Patients</button>
                  <button onclick="onAppointment(this,event)" class="btn btn-info">Patients On Appointments</button>
                  <button onclick="offAppointment(this,event)" class="btn btn-success">Patients Off Appointments</button>
                  <button onclick="viewReferralsOrConsults(this,event)" class="btn btn-warning">View Referrals Or Consults</button>
                </div>
              </div>

              <div class="card" id="referrals-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralsCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">All Referrals /  Consults</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="on-appointment-card" style="display: none;">
                <div class="card-header">
                  
                  <button class="btn btn-warning btn-round" onclick="goBackOnAppointmentCard(this,event)">Go Back</button>
                  <h4 class="card-title">All Patients With Appointments Today</h4>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="referral-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackReferralCard(this,event)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" id="welcome-heading">Referral Info</h3>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="on-appointments-bio-data-card" style="display: none;">
                <div class="card-header">
                  <h3 class="card-title" id="welcome-heading">Welcome <?php echo $logged_in_user_name; ?></h3>
                </div>
                <div class="card-body">
                  <button class="btn btn-warning" onclick="goDefault()">Go Back</button>
                  
                  <h4 style="margin-bottom: 40px;" id="quest">Verify Patient Bio Data. Submit To Forward To The Next Officer </h4>
                  <?php $attr = array('id' => 'on-appointments-bio-data-form') ?>
                  <?php echo form_open('',$attr); ?>
                  <span class="form-error text-right">* </span>: required
                  
                  <h4 class="form-sub-heading">Personal Information</h4>
                  <div class="wrap">
                    <div class="form-row">             
                      <input type="hidden" name="hospital_number" class="hospital_number" value="">
                      <input type="hidden" name="record_id" class="record_id" value="">
                      
                      <div class="form-group col-sm-6">
                        <label for="first_name" class="label-control"><span class="form-error1">*</span>  FirstName: </label>
                        <input type="text" class="form-control" id="first_name" name="first_name">
                        <span class="form-error"></span>
                      </div>
                      <div class="form-group col-sm-6">
                        <label for="last_name" class="label-control"><span class="form-error1">*</span> LastName: </label>
                        <input type="text" class="form-control" id="last_name" name="last_name">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="dob" class="label-control"><span class="form-error1">*</span> Date Of Birth: </label>
                        <input type="date" class="form-control" name="dob" id="dob">
                        <span class="form-error"></span>
                      </div>
                      
                      <div class="form-group col-sm-2">
                        <label for="age" class="label-control"><span class="form-error1">*</span> Age: </label>
                        <input type="number" class="form-control" name="age" id="age">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-2">
                        <div id="age_unit">
                          <label for="age_unit" class="label-control"><span class="form-error1">*</span> Age Unit: </label>
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

                      <div class="form-group col-sm-5">
                        <p class="label"><span class="form-error1">*</span> Gender: </p>
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

                      <div class="form-group col-sm-2">
                        <label for="race" class="label-control"><span class="form-error1">*</span> Race/Tribe: </label>
                        <input type="text" class="form-control" id="race" name="race">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="mobile" class="label-control"><span class="form-error1">*</span> Mobile No: </label>
                        <input type="number" class="form-control" id="mobile" name="mobile">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-5">
                        <label for="email" class="label-control"><span class="form-error1">*</span> Email: </label>
                        <input type="email" class="form-control" id="email" name="email">
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>

                  <div class="wrap">
                    <h4 class="form-sub-heading">Medical Information</h4>
                    <div class="form-row">
                     
                      <div class="form-group col-sm-4">
                        <p class="label"><span class="form-error1">*</span> Fasting?</p>
                        <div id="fasting">
                          <div class="form-check form-check-radio form-check-inline" id="fasting">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting" value="yes" > Yes
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-radio form-check-inline disabled">
                            <label class="form-check-label">
                              <input class="form-check-input" type="radio" name="fasting"  value="no" checked> No
                              <span class="circle">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="present_medications" class="label-control">Present Medications: </label>
                        <input type="text" class="form-control" id="present_medications" name="present_medications">
                        <span class="form-error"></span>
                      </div>                      
                                       
                      <div class="form-group col-sm-6">
                        <label for="address" class="label-control"><span class="form-error1">*</span> Address: </label>
                        <textarea name="address" id="address" cols="10" rows="10" class="form-control"></textarea>
                        <span class="form-error"></span>
                      </div>

                    </div>
                  </div>
                  <input type="hidden" name="random_bytes" value='<?php echo bin2hex($this->encryption->create_key(16)); ?>' readonly>
                  <input type="submit" class="btn btn-primary">
                </form>


                </div>
              </div>

              <div class="card" id="off-appointment-patient-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOffAppointmentPatientCard(this)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Input Vital Signs</h3>
                </div>
                <div class="card-body">

                  <?php 
                  $attr = array('id' => 'vital-signs-form');
                  echo form_open('',$attr);
                  ?>
                    <div class="form-row">
                      
                      <div class="form-group col-sm-3">
                        <label for="pr" class="label-control"><span class="form-error1">*</span> Pulse Rate (b/min): </label>
                        <input type="number" class="form-control" id="pr" name="pr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="rr" class="label-control"><span class="form-error1">*</span> Respiratory Rate (c/min): </label>
                        <input type="number" class="form-control" id="rr" name="rr" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="bp" class="label-control"><span class="form-error1">*</span> Blood Pressure (mmHg): </label>
                        <input type="text" class="form-control" id="bp" name="bp" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-3">
                        <label for="temperature" class="label-control"><span class="form-error1">*</span>Temperature (&deg; C): </label>
                        <input type="number" step="any" class="form-control" id="temperature" name="temperature" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="waist_circumference" class="label-control"><span class="form-error1">*</span>Waist Circumference (cm): </label>
                        <input type="number" class="form-control" id="waist_circumference" name="waist_circumference" value="">
                        <span class="form-error"></span>
                      </div> 

                      <div class="form-group col-sm-6">
                        <label for="hip_circumference" class="label-control"><span class="form-error1">*</span>Hip Circumference (cm): </label>
                        <input type="number" class="form-control" id="hip_circumference" name="hip_circumference" value="">
                        <span class="form-error"></span>
                      </div>                      
                    </div>

                    <h3 class="my-4 text-center">Adult vital signs</h3>
                    <div class="form-row">
                      <div class="form-group col-sm-6">
                        <label for="adult_spo2" class="label-control">SPO2 (%): </label>
                        <input type="number" class="form-control" id="adult_spo2" name="adult_spo2" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-6">
                        <label for="adult_pain_score" class="label-control"> Pain Score: </label>
                        <input type="number" class="form-control" id="adult_pain_score" name="adult_pain_score" value="">
                        <span class="form-error"></span>
                      </div>


                    </div>

                    <h3 class="my-4 text-center">Pediatrics Vital Signs</h3>
                    <div class="form-row">
                      <div class="form-group col-sm-4">
                        <label for="pediatrics_weight" class="label-control">Weight (kg): </label>
                        <input type="number" class="form-control" id="pediatrics_weight" name="pediatrics_weight" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-4">
                        <label for="pediatrics_spo2" class="label-control">SPO2 (%): </label>
                        <input type="number" class="form-control" id="pediatrics_spo2" name="pediatrics_spo2" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-4">
                        <label for="pediatrics_pain_score" class="label-control"> Pain Score: </label>
                        <input type="number" class="form-control" id="pediatrics_pain_score" name="pediatrics_pain_score" value="">
                        <span class="form-error"></span>
                      </div>
                    </div>

                    <h3  class="my-4 text-center">Medical Vital Signs</h3>
                    <div class="form-row">
                      <div class="form-group col-sm-4">
                        <label for="medical_weight" class="label-control">Weight (kg): </label>
                        <input onkeyup="recalculateBmi('off')" type="number" class="form-control" id="medical_weight" name="medical_weight" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-4">
                        <label for="medical_height" class="label-control">Height (cm): </label>
                        <input onkeyup="recalculateBmi('off')" type="number" class="form-control" id="medical_height" name="medical_height" value="">
                        <span class="form-error"></span>
                      </div>

                      <div class="form-group col-sm-4">
                        <label class="label-control">Body Mass Index (Kg/M²) : </label>
                        <p id="bmi_disp" class="text-bold text-primary"></p>
                      </div>
                    </div>

                    

                    <input type="submit" class="btn btn-primary">
                  </form>


    
                </div>

                <div style="margin-top: 20px; margin-bottom: 20px; border-top: 1px solid darkgrey; padding: 20px;">
                  <h4>More Options</h4>

                  <div class="table-responsive">
      
                    <table class="table table-test table-striped table-bordered  nowrap hover display" id="select-more-options-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="viewPreviousNotes(this,event)">View patients previous medical records</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="loadAllServices(this,event)">Request Service</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="viewPreviouslyRequestedServices(this,event)">View Previously Requested Services</td>
                        </tr>
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>



              <div class="card" id="on-appointment-patient-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackOnAppointmentPatientCard(this)" class="btn btn-warning">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Input Vital Signs</h3>
                </div>
                <div class="card-body">
                 
                  <?php 
                  $attr = array('id' => 'on-appointment-vital-signs-form');
                  echo form_open('',$attr);
                  ?>
                  <div class="form-row">
                    
                    <div class="form-group col-sm-3">
                      <label for="pr" class="label-control"><span class="form-error1">*</span> Pulse Rate (b/min): </label>
                      <input type="number" class="form-control" id="pr" name="pr" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-3">
                      <label for="rr" class="label-control"><span class="form-error1">*</span> Respiratory Rate (c/min): </label>
                      <input type="number" class="form-control" id="rr" name="rr" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-3">
                      <label for="bp" class="label-control"><span class="form-error1">*</span> Blood Pressure (mmHg): </label>
                      <input type="text" class="form-control" id="bp" name="bp" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-3">
                      <label for="temperature" class="label-control"><span class="form-error1">*</span>Temperature (&deg; C): </label>
                      <input type="number" step="any" class="form-control" id="temperature" name="temperature" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="waist_circumference" class="label-control"><span class="form-error1">*</span>Waist Circumference (cm): </label>
                      <input type="number" class="form-control" id="waist_circumference" name="waist_circumference" value="">
                      <span class="form-error"></span>
                    </div> 

                    <div class="form-group col-sm-6">
                      <label for="hip_circumference" class="label-control"><span class="form-error1">*</span>Hip Circumference (cm): </label>
                      <input type="number" class="form-control" id="hip_circumference" name="hip_circumference" value="">
                      <span class="form-error"></span>
                    </div>                      
                  </div>
                  <h3 class="my-4 text-center">Adult vital signs</h3>
                  <div class="form-row">
                    <div class="form-group col-sm-6">
                      <label for="adult_spo2" class="label-control">SPO2 (%): </label>
                      <input type="number" class="form-control" id="adult_spo2" name="adult_spo2" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="adult_pain_score" class="label-control"> Pain Score: </label>
                      <input type="number" class="form-control" id="adult_pain_score" name="adult_pain_score" value="">
                      <span class="form-error"></span>
                    </div>


                  </div>

                  <h3 class="my-4 text-center">Pediatrics Vital Signs</h3>
                  <div class="form-row">
                    <div class="form-group col-sm-4">
                      <label for="pediatrics_weight" class="label-control">Weight (kg): </label>
                      <input type="number" class="form-control" id="pediatrics_weight" name="pediatrics_weight" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="pediatrics_spo2" class="label-control">SPO2 (%): </label>
                      <input type="number" class="form-control" id="pediatrics_spo2" name="pediatrics_spo2" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="pediatrics_pain_score" class="label-control"> Pain Score: </label>
                      <input type="number" class="form-control" id="pediatrics_pain_score" name="pediatrics_pain_score" value="">
                      <span class="form-error"></span>
                    </div>
                  </div>

                  <h3  class="my-4 text-center">Medical Vital Signs</h3>
                  <div class="form-row">
                    <div class="form-group col-sm-4">
                      <label for="medical_weight" class="label-control">Weight (kg): </label>
                      <input onkeyup="recalculateBmi('on')" type="number" class="form-control" id="medical_weight" name="medical_weight" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="medical_height" class="label-control">Height (cm): </label>
                      <input onkeyup="recalculateBmi('on')" type="number" class="form-control" id="medical_height" name="medical_height" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label class="label-control">Body Mass Index (Kg/M²) : </label>
                      <p id="bmi_disp" class="text-bold text-primary"></p>
                    </div>
                  </div>
                  
                  <input type="submit" class="btn btn-primary">
                  </form>

                </div>

                <div style="margin-top: 20px; margin-bottom: 20px; border-top: 1px solid darkgrey; padding: 20px;">
                  <h4>More Options</h4>

                  <div class="table-responsive">
      
                    <table class="table table-test table-striped table-bordered  nowrap hover display" id="select-more-options-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="viewPreviousNotes(this,event)">View patients previous medical records</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="loadAllServices(this,event)">Request Service</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="viewPreviouslyRequestedServices(this,event)">View Previously Requested Services</td>
                        </tr>
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


              <div class="card" id="previously-registered-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviouslyRegistered(this)" class="btn btn-warning">Go Back</button>
                  <h4 class="card-title">New Patients</h4>
                  <em class="text-primary">Click Patient To Enter Vital Signs</em>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              <div class="card" id="off-appointment-patients-card" style="display: none;">
                <div class="card-header">
                  <button onclick="goBackPreviouslyRegistered(this)" class="btn btn-warning">Go Back</button>
                  <h4 style="margin-bottom: 40px;" id="quest">All Patients Off Appointments</h4>
                  <p>Click To Input Vital Signs</p>
                </div>
                <div class="card-body">
                  
                </div>
              </div>

              
              <div class="card" id="edit-patient-bio-data-card" style="display: none;">
                <div class="card-header">
                  <button class="btn btn-warning btn-round" onclick="goBackEditBio()">Go Back</button>
                  <h3 class="card-title" style="text-transform: capitalize;">Input Vital Signs</h3>
                </div>
                <div class="card-body">
                  <?php 
                  $attr = array('id' => 'new-patient-vital-signs-form');
                  echo form_open('',$attr);
                  ?>
                  <div class="form-row">
                    
                    <div class="form-group col-sm-3">
                      <label for="pr" class="label-control"><span class="form-error1">*</span> Pulse Rate (b/min): </label>
                      <input type="number" class="form-control" id="pr" name="pr" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-3">
                      <label for="rr" class="label-control"><span class="form-error1">*</span> Respiratory Rate (c/min): </label>
                      <input type="number" class="form-control" id="rr" name="rr" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-3">
                      <label for="bp" class="label-control"><span class="form-error1">*</span> Blood Pressure (mmHg): </label>
                      <input type="text" class="form-control" id="bp" name="bp" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-3">
                      <label for="temperature" class="label-control"><span class="form-error1">*</span>Temperature (&deg; C): </label>
                      <input type="number" step="any" class="form-control" id="temperature" name="temperature" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="waist_circumference" class="label-control"><span class="form-error1">*</span>Waist Circumference (cm): </label>
                      <input type="number" class="form-control" id="waist_circumference" name="waist_circumference" value="">
                      <span class="form-error"></span>
                    </div> 

                    <div class="form-group col-sm-6">
                      <label for="hip_circumference" class="label-control"><span class="form-error1">*</span>Hip Circumference (cm): </label>
                      <input type="number" class="form-control" id="hip_circumference" name="hip_circumference" value="">
                      <span class="form-error"></span>
                    </div>                      
                  </div>

                  <h3 class="my-4 text-center">Adult vital signs</h3>
                  <div class="form-row">
                    <div class="form-group col-sm-6">
                      <label for="adult_spo2" class="label-control">SPO2 (%): </label>
                      <input type="number" class="form-control" id="adult_spo2" name="adult_spo2" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="adult_pain_score" class="label-control"> Pain Score: </label>
                      <input type="number" class="form-control" id="adult_pain_score" name="adult_pain_score" value="">
                      <span class="form-error"></span>
                    </div>


                  </div>

                  <h3 class="my-4 text-center">Pediatrics Vital Signs</h3>
                  <div class="form-row">
                    <div class="form-group col-sm-4">
                      <label for="pediatrics_weight" class="label-control">Weight (kg): </label>
                      <input type="number" class="form-control" id="pediatrics_weight" name="pediatrics_weight" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="pediatrics_spo2" class="label-control">SPO2 (%): </label>
                      <input type="number" class="form-control" id="pediatrics_spo2" name="pediatrics_spo2" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="pediatrics_pain_score" class="label-control"> Pain Score: </label>
                      <input type="number" class="form-control" id="pediatrics_pain_score" name="pediatrics_pain_score" value="">
                      <span class="form-error"></span>
                    </div>
                  </div>

                  <h3  class="my-4 text-center">Medical Vital Signs</h3>
                  <div class="form-row">
                    <div class="form-group col-sm-4">
                      <label for="medical_weight" class="label-control">Weight (kg): </label>
                      <input onkeyup="recalculateBmi('new')" type="number" class="form-control" id="medical_weight" name="medical_weight" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label for="medical_height" class="label-control">Height (cm): </label>
                      <input onkeyup="recalculateBmi('new')" type="number" class="form-control" id="medical_height" name="medical_height" value="">
                      <span class="form-error"></span>
                    </div>

                    <div class="form-group col-sm-4">
                      <label class="label-control">Body Mass Index (Kg/M²) : </label>
                      <p id="bmi_disp" class="text-bold text-primary"></p>
                    </div>
                  </div>
                  
                  <input type="submit" class="btn btn-primary">
                  </form>
                </div>

                <div style="margin-top: 20px; margin-bottom: 20px; border-top: 1px solid darkgrey; padding: 20px;">
                  <h4>More Options</h4>

                  <div class="table-responsive">
      
                    <table class="table table-test table-striped table-bordered  nowrap hover display" id="select-more-options-table" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td onclick="viewPreviousNotes(this,event)">View patients previous medical records</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td onclick="loadAllServices(this,event)">Request Service</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td onclick="viewPreviouslyRequestedServices(this,event)">View Previously Requested Services</td>
                        </tr>
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


              <div class="modal fade" data-backdrop="static" id="input-vital-signs-referral-modal" data-focus="true" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Input Vital Signs For This Referral / Consult Patient</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>


                    <div class="modal-body" id="modal-body">
                      <?php
                        $attr = array('id' => 'input-vital-signs-referral-form');
                       echo form_open('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_vital_signs_form_referral',$attr);
                      ?>
                        <div class="form-group">
                          <label for="referral_pr">Pulse Rate (b / min): </label>
                          <input type="number" name="referral_pr" id="referral_pr" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_rr">Respiratory Rate (c / min): </label>
                          <input type="number" name="referral_rr" id="referral_rr" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_bp">Blood Pressure (mmHg): </label>
                          <input type="number" name="referral_bp" id="referral_bp" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_temperature">Temperature (&deg; C): </label>
                          <input type="number" step="any" name="referral_temperature" id="referral_temperature" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_waist_circumference">Waist Circumference (cm): </label>
                          <input type="number" name="referral_waist_circumference" id="referral_waist_circumference" class="form-control">
                          <span class="form-error"></span>
                        </div>

                        <div class="form-group">
                          <label for="referral_hip_circumference">Hip Circumference (cm): </label>
                          <input type="number" name="referral_hip_circumference" id="referral_hip_circumference" class="form-control">
                          <span class="form-error"></span>
                        </div>
                        <input type="submit" class="btn btn-success" value="PROCEED">
          
                      </form>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                    </div>
                  </div>
                </div>
              </div>  




              <div id="proceed-referral-to-doctor" onclick="proceedReferralToDoctor(this,event)" rel="tooltip" data-toggle="tooltip" title="Enter Vital Signs" style="background: #9c27b0; cursor: pointer; position: fixed; bottom: 0; right: 0;  border-radius: 50%; cursor: pointer; display: none; fill: #fff; height: 56px; outline: none; overflow: hidden; margin-bottom: 24px; margin-right: 24px; text-align: center; width: 56px; z-index: 4000;box-shadow: 0 8px 10px 1px rgba(0,0,0,0.14), 0 3px 14px 2px rgba(0,0,0,0.12), 0 5px 5px -3px rgba(0,0,0,0.2);">
                <div class="" style="display: inline-block; height: 24px; position: absolute; top: 16px; left: 16px; width: 24px;">
                  <i class="material-icons" style="font-size: 25px; font-weight: normal; color: #fff;" aria-hidden="true">arrow_forward</i>
                </div>
              </div>
              
            </div>
          </div>
          

        </div>
      </div>
      
      </div>
      <footer class="footer">
        <div class="container-fluid">
           <footer>&copy; <?php echo date("Y"); ?> Copyright (OneHealth Issues Global Limited). All Rights Reserved</footer>
        </div>
       
      </footer>
  </div>
  
  
</body>
<script>
    $(document).ready(function() {

      $("#request-service-form").submit(function (evt) {
        
        evt.preventDefault();
        
        var me  = $(this);
        
        var url = me.attr("action");
        var form_data = me.serializeArray();
        
        var service_id = me.attr("data-id");
        var type = me.attr("data-type");

        form_data = form_data.concat({
          "name" : "consultation_id",
          "value" : consultation_id
        })

        form_data = form_data.concat({
          "name" : "service_id",
          "value" : service_id
        })

        form_data = form_data.concat({
          "name" : "type",
          "value" : type
        })
        console.log(url)
        console.log(form_data)
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
            if(response.success && response.user_type != ""){
              var user_type = response.user_type;

              if(user_type == "nfp"){
                var text = "Service Successfully Requested";
              }else{
                var text = "Service Successfully Requested. Please Direct Patient To Hospital Teller For Payment.";
              }

              $.notify({
              message: text
              },{
                type : "success"  
              });
              $("#request-service-card").hide();
              loadAllServices(this,event);
            }else if(response.invalid_id){
              swal({
                title: 'Ooops',
                text: "Something Went Wrong",
                type: 'warning'
              })
            }else{
              $.each(response.messages, function (key,value) {

              var element = $('#request-service-form #'+key);
              
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

      $("#vital-signs-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        
        
        var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_vital_signs_clinic_nurse'); ?>";
        var values = me.serializeArray();

        values = values.concat({
          "name" : "consultation_id",
          "value" : consultation_id
        })
        
        console.log(values)
        $(".spinner-overlay").show();
        $.ajax({
          url : url,
          type : "POST",
          responseType : "json",
          dataType : "json",
          data : values,
          success : function (response) {
            $(".spinner-overlay").hide();
            if(response.success){
              $.notify({
              message:"Patient Vital Signs Inputed Successfully"
              },{
                type : "success"  
              });
              $(".form-error").html("");
              $("#off-appointment-patient-card").hide();
              $("#off-appointment-patient-card input").val("");
              offAppointment(this,event);
            }
            else if(response.messages != {}){
             $.each(response.messages, function (key,value) {

              var element = me.find('#'+key);
              
              element.closest('div.form-group')
                      
                      .find('.form-error').remove();
              element.after(value);
              
             });
              $.notify({
              message:"Some Values Where Not Valid. Please Enter Valid Values"
              },{
                type : "warning"  
              });
            }else{
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
              message:"Sorry Something Went Wrong"
              },{
                type : "danger"  
              });
            $(".form-error").html();
          }
        });   
      })

       $("#new-patient-vital-signs-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
         
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_vital_signs_clinic_nurse'); ?>";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "consultation_id",
            "value" : consultation_id
          })
          
          console.log(values)
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : values,
            success : function (response) {
              
              $(".spinner-overlay").hide();
              if(response.success){
                $.notify({
                message:"Patient Vital Signs Inputed Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                $("#edit-patient-bio-data-card").hide();
                $("#edit-patient-bio-data-card input").val("");
                newPatients(this,event);
              }
              else if(response.messages != {}){
               $.each(response.messages, function (key,value) {

                var element = me.find('#'+key);
                
                element.closest('div.form-group')
                        
                        .find('.form-error').remove();
                element.after(value);
                
               });
                $.notify({
                message:"Some Values Where Not Valid. Please Enter Valid Values"
                },{
                  type : "warning"  
                });
              }else{
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
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              $(".form-error").html();
            }
          });  
      })

      $("#on-appointment-vital-signs-form").submit(function (evt) {
          evt.preventDefault();
          var me = $(this);
         
          
          var url = "<?php echo site_url('onehealth/index/'.$addition.'/'.$second_addition.'/'.$third_addition.'/'.$fourth_addition.'/submit_vital_signs_clinic_nurse'); ?>";
          var values = me.serializeArray();
          values = values.concat({
            "name" : "consultation_id",
            "value" : consultation_id
          })
          console.log(values)
          $(".spinner-overlay").show();
          $.ajax({
            url : url,
            type : "POST",
            responseType : "json",
            dataType : "json",
            data : values,
            success : function (response) {
              $(".spinner-overlay").hide();
              if(response.success){
                $.notify({
                message:"Patient Vital Signs Inputed Successfully"
                },{
                  type : "success"  
                });
                $(".form-error").html("");
                $("#on-appointment-patient-card").hide();
                $("#on-appointment-patient-card input").val("");
                onAppointment(this,event);
              }
              else if(response.messages != {}){
               $.each(response.messages, function (key,value) {

                var element = me.find('#'+key);
                
                element.closest('div.form-group')
                        
                        .find('.form-error').remove();
                element.after(value);
                
               });
                $.notify({
                message:"Some Values Where Not Valid. Please Enter Valid Values"
                },{
                  type : "warning"  
                });
              }else{
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
                message:"Sorry Something Went Wrong"
                },{
                  type : "danger"  
                });
              $(".form-error").html();
            }
          });  
      })

      $("#input-vital-signs-referral-form").submit(function (evt) {
        evt.preventDefault();
        var me = $(this);
        var url = me.attr("action");
        var form_data = me.serializeArray();
        var id = me.attr("data-id");
        form_data = form_data.concat({
          "name" : "id",
          "value" : id
        })

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
            if(response.success == true){
              $("#input-vital-signs-referral-modal").modal("hide");
              $.notify({
              message:"Referral Successfully Moved To Doctor's Platform"
              },{
                type : "success"  
              });
              setTimeout(function () {
                document.location.reload();
              }, 1500);
            }
            else{
             $.each(response.messages, function (key,value) {

              var element = $('#input-vital-signs-referral-form #'+key);
              
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
      })
      
    });



</script>
