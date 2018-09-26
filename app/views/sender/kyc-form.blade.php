<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div ng-controller="AddSenderCtrl"  class="head-weight">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- Page Title -->
				<div class="panel-heading">KYC Details</div>
				<!-- /Page Title -->
				<!-- Page Content -->
				
				<div class="tab-container ">
					<div class="tab-content">
						<div class="panel panel-default panel-default panel-border-color panel-border-color-primary">
							<div class="panel-body">
								<!-- Form -->
								<form name="KycFrm" method="post" style="border-radius: 0px;" class="group-border-dashed" ng-submit="kycForm(sender)" novalidate enctype="multipart/form-data"

>
								<input type="text" name="sender_id" ng-model="sender_id"  ng-init="sender_id = '<?php echo $data->rbl_remitter_code; ?>'"  class="hide" >
									<!-- col 1 start -->
									<div class="col-md-4">
										<div class="row">
											<div class="form-group">
												<label class="control-label" >Name <span>*(<sup></sup>Only letters allowed )</span></label>
												<input type="text" ng-model="name" name="name" class="form-control err" placeholder="Enter Name" required ischar ng-init="name = '<?php echo $data->name; ?>'" disabled >
												<p ng-show="KycFrm.$submitted && KycFrm.name.$invalid" class="err-mark">Please enter the name.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Mobile  <span>*(<sup></sup>Cannot change mobile number)</span></label>

												<input type="text" ng-model="mobile" name="mobile" ng-init="mobile = '<?php echo $data->phone_no; ?>'" class="form-control err" placeholder="Enter Mobile" required isphoneno disabled >
												<p ng-show="KycFrm.$submitted && KycFrm.mobile.$invalid" class="err-mark">Please enter the mobile.</p>

											</div>

											<div class="form-group">
												<label class="control-label" >Last Name <span>*(<sup></sup> Required)</span></label>
												<input type="text" ng-model="lname" name="lname" class="form-control err" placeholder="Enter Last Name" required ischar>
												<p ng-show="KycFrm.$submitted && KycFrm.lname.$invalid" class="err-mark">Please enter the last name.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Email ID <span>*(<sup></sup> Required)</span></label>
												<input type="text" ng-model="email" name="email" class="form-control err" placeholder="Enter Last Name" required ischar>
												<p ng-show="KycFrm.$submitted && KycFrm.email.$invalid" class="err-mark">Please enter the last name.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Religion <span>*(<sup></sup> Required)</span></label>

												<select  ng-model="religion" class="form-control" id="religion" name="religion" required>

													<option value="">SELECT OPTION</option>
													<option value="Hindu">Hindu</option>
													<option value="Muslim">Muslim</option>
													<option value="Sikh">Sikh</option>
													<option value="Christian">Christian</option>
													<option value="Other">Other</option>
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.religion.$invalid" class="err-mark">Please select relegion.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Education <span>*(<sup></sup> Required)</span></label>

												<select  ng-model="education" class="form-control" id="education" name="education" required>

													<option value="">SELECT OPTION</option>
													<option value="Under Graduate">Under Graduate</option>
													<option value="Graduate">Graduate</option>
													<option value="Post Graduate">Post Graduate</option>
													<option value="Professional">Professional</option>
													<option value="Illiterate">Illiterate</option>
													
               
               
               
               
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.education.$invalid" class="err-mark">Please select education.</p>
											</div>
											
											<div class="form-group">
												<label class="control-label" >PAN card number <span>*(<sup></sup> Required)</span></label>
												<input type="text" ng-model="pan" name="pan" class="form-control err" placeholder="Enter PAN card number" required ischar>
												<p ng-show="KycFrm.$submitted && KycFrm.pan.$invalid" class="err-mark">Please enter the pan card number.</p>
											</div>
											<div class="form-group">
											<label class="control-label" >Address Proof<span>*(<sup></sup> Required)</span></label>
											<select  ng-model="selectaddress_proof" class="form-control" id="selectaddress_proof" name="selectaddress_proof" required>
												<option value="">SELECT OPTION</option>
												<option value="Passport">Passport</option>
												<option value="Driving Licence">Driving Licence</option>
												<option value="Aadhar Card">Aadhar Card</option>
												<option value="Voter ID Card">Voter ID Card</option>
												<option value="NREGA Card">NREGA Card</option>
												<option value="Other">Other</option>
												 
               
               
               
               
               
											</select>

											<p ng-show="KycFrm.$submitted && KycFrm.selectaddress_proof.$invalid" class="err-mark">Please select address proof.</p>
										</div>

											<div class="form-group">
												<label class="control-label" >Relation with Nominee <span>*(<sup></sup> Required)</span></label>
												<input type="text" ng-model="nominee_relation" name="nominee_relation" class="form-control err" placeholder="Enter Relation with Nominee" required ischar>
												<p ng-show="KycFrm.$submitted && KycFrm.nominee_relation.$invalid" class="err-mark">Please enter the relation with nominee.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Customer status<span>*(<sup></sup> Required)</span></label>

												<select  ng-model="customer_status" class="form-control" id="customer_status" name="customer_status" required>

													<option value="">SELECT OPTION</option>
													<option value="1">Individual</option>
													<option value="2">Non Individual</option>
													                
               
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.customer_status.$invalid" class="err-mark">Please select customer status.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Annual Income<span>*(<sup></sup> Required)</span></label>

												<select  ng-model="annual_income" class="form-control" id="annual_income" name="annual_income" required>

													<option value="">SELECT OPTION</option>
													<option value="1">0 to 2 lacs</option>
													<option value="2">2 lacs to 5 lacs</option>
													<option value="3">5 lacs to 10 lacs</option>
													<option value="4">More than 10 lacs</option>
													
               
               
               
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.annual_income.$invalid" class="err-mark">Please select annual income.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Upload Address Proof<span>*(<sup></sup> Required)</span></label>
												<input type="file" name="address_proof" file-model="myfile1">
												<p ng-show="KycFrm.$submitted && KycFrm.address_proof.$invalid" class="err-mark">Please select upload address proof.</p>
											</div>
										</div>
									</div>
									<!-- col 1 end -->
									
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" >Pincode <span>*(<sup></sup> Required)</span></label>
											<input type="text" ng-model="pincode" name="pincode" ng-init="pincode = '<?php echo $data->pincode; ?>'" class="form-control err" placeholder="Enter Pincode" required isvalidpincode disabled >
											<p ng-show="KycFrm.$submitted && KycFrm.pincode.$invalid" class="err-mark">Please enter a pincode.</p>
										</div>
										<div class="form-group">
											<label class="control-label" >Address <span>*(<sup></sup> Minimum 10 characters, No special characters)</span></label>

											<input type="text" ng-model="address" name="address" class="form-control err" placeholder="Enter Address" required >
											<p ng-show="KycFrm.$submitted && KycFrm.address.$invalid" class="err-mark">Please enter a address.</p>

										</div>
										<div class="form-group">
											<label class="control-label" >Mother's Name <span>*(<sup></sup> Required)</span></label>
											<input type="text" ng-model="mother_name" name="mother_name" class="form-control err" placeholder="Enter Mother's Name" required >
											<p ng-show="KycFrm.$submitted && KycFrm.mother_name.$invalid" class="err-mark">Please enter mother's name.</p>
										</div>
										<div class="form-group">
											<label class="control-label" >Date of Birth <span>*(<sup></sup> Required)</span></label>
											<input type="text" ng-model="dob" name="dob" class="form-control err" placeholder="Enter Date of Birth" required >
											<p ng-show="KycFrm.$submitted && KycFrm.dob.$invalid" class="err-mark">Please enter date of birth.</p>
										</div>
										<div class="form-group">
											<label class="control-label" >Nationality <span>*(<sup></sup> Required)</span></label>
											<input type="text" ng-model="nationality" name="nationality" class="form-control err" placeholder="Enter Nationality" required >
											<p ng-show="KycFrm.$submitted && KycFrm.nationality.$invalid" class="err-mark">Please enter  nationality.</p>
										</div>
										<div class="form-group">
											<label class="control-label" >Marital Status <span>*(<sup></sup> Required)</span></label>

											<select  ng-model="marital_status" class="form-control" id="marital_status" name="marital_status" required>

												<option value="">SELECT OPTION</option>
												<option value="Married">Married</option>
												<option value="Unmarried">Unmarried</option>
												<option value="Others">Others</option>
												
											</select>
											<p ng-show="KycFrm.$submitted && KycFrm.marital_status.$invalid" class="err-mark">Please select marital status.</p>
										</div>

										

										<div class="form-group">
											<label class="control-label" >Age of Nominee <span>*(<sup></sup> Required)</span></label>
											<input type="text" ng-model="aon" name="aon" class="form-control err" placeholder="Enter Age of Nominee" required >
											<p ng-show="KycFrm.$submitted && KycFrm.aon.$invalid" class="err-mark">Please enter  age of nominee.</p>
										 </div>

                                        <div class="form-group">
											<label class="control-label" >DOB Nominee <span>*(<sup></sup> Required)</span></label>
											<input type="text" ng-model="dob_nominee" name="dob_nominee" class="form-control err" placeholder="Enter DOB of Nominee" required >
											<p ng-show="KycFrm.$submitted && KycFrm.dob_nominee.$invalid" class="err-mark">Please enter  age of nominee.</p>
										</div>


										<div class="form-group">
											<label class="control-label" >Customer Type <span>*(<sup></sup> Required)</span></label>
											<!-- <input type="text" ng-model="sender.customer_type" name="customer_type" class="form-control err" placeholder="Enter Customer Type" required > -->
                                            
                                             <select ng-model="customer_type" name="customer_type" class="form-control">
                                            <option value="">SELECT OPTION</option>
                                            <option value="1">Salaried</option>
					                        <option value="2">Self Employed including Professional</option>    
					                        <option value="3">Farmer</option>
					                        <option value="4">Housewife</option>
					                        <option value="5">Minor</option>
                                             </select>
											
											<p ng-show="KycFrm.$submitted && KycFrm.customer_type.$invalid" class="err-mark">Please enter  customer type.</p>
										</div>
										<div class="form-group">
											<label class="control-label" >Politically Exposed<span>*(<sup></sup> Required)</span></label>
											<!-- <input type="text" ng-model="sender.politically_exposed" name="politically_exposed" class="form-control err" placeholder="Enter Politically Exposed" required > -->
											<select ng-model="politically_exposed" class="form-control" id="politically_exposed" name="politically_exposed" required>
											<option value="">SELECT OPTION</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>

               
											</select>
											<p ng-show="KycFrm.$submitted && KycFrm.politically_exposed.$invalid" class="err-mark">Please enter  politically exposed.</p>
										</div>
										<div class="form-group">
												<label class="control-label" >Upload Remitter Registration Form<span>*(<sup></sup> Required)</span></label>
												<input type="file" name="remitter_registration_form" id="remitter_registration_form" file-model="myfile2">
												<p ng-show="KycFrm.$submitted && KycFrm.remitter_registration_form.$invalid" class="err-mark">Please select upload remitter registration form.</p>
											</div>
									</div>
									<!-- col 2 end -->

									<!-- col 3 start -->
									<div class="col-md-3">
										<div class="row">
											<div class="form-group">
												<label class="control-label" >City  <span>*(<sup></sup>required)</span></label>

												<input type="text" ng-model="city" ng-init="city = '<?php echo $data->city; ?>'" name="city" class="form-control err" placeholder="Enter city" required disabled>
												<p ng-show="KycFrm.$submitted && KycFrm.city.$invalid" class="err-mark">Please enter the city.</p>
											</div>
											<div class="form-group">
												<label class="control-label" >State  <span>*(<sup></sup>required)</span></label>
												<input type="text" ng-model="state"  name="state" class="form-control err" placeholder="Enter state" required  ng-init="state = '<?php echo $data->state; ?>'" disabled>
												<p ng-show="KycFrm.$submitted && KycFrm.state.$invalid" class="err-mark">Please enter the state.</p>

											</div>
											<div class="form-group">
												<label class="control-label" >Father or Spouse name  <span>*(<sup></sup>required)</span></label>
												<input type="text" ng-model="father_name" name="father_name" class="form-control err" placeholder="Enter Father or Spouse name" required>
												<p ng-show="KycFrm.$submitted && KycFrm.father_name.$invalid" class="err-mark">Please enter the father or spouse name.</p>
											</div>
											<div class="form-group">
												<label class="control-label" >Gender <span>*(<sup></sup> Required)</span></label>

												<select  ng-model="gender" class="form-control" id="gender" name="gender" required>

													<option value="">SELECT OPTION</option>
													<option value="Male">Male</option>
													<option value="Female">Female</option>
													
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.gender.$invalid" class="err-mark">Please select Gender.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Category <span>*(<sup></sup> Required)</span></label>

												<select  ng-model="category" class="form-control" id="category" name="category" required>

													<option value="">SELECT OPTION</option>
													<option value="Gen">Gen</option>
													<option value="OBC">OBC</option>
													<option value="SC">SC</option>
													<option value="ST">ST</option>
												
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.category.$invalid" class="err-mark">Please select Category.</p>
											</div>

											<!-- <div class="form-group">
												<label class="control-label" >Category <span>*(<sup></sup> Required)</span></label>
<<<<<<< HEAD
												<select  ng-model="sender.category" class="form-control" id="category" name="category" required>
=======
												<select ng-options="" ng-model="sender.category" class="form-control err" id="category" name="category" required>
>>>>>>> master
													<option value="">SELECT OPTION</option>
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.category.$invalid" class="err-mark">Please select Gender.</p>
											</div> -->
											<div class="form-group">
												<label class="control-label" >Residential Status <span>*(<sup></sup> Required)</span></label>

												<select  ng-model="residential_status" class="form-control" id="residential_status" name="residential_status" required>

													<option value="">SELECT OPTION</option>
													<option value="Resident Individual">Resident Individual</option>
													<option value="Non Resident Indian">Non Resident Indian</option>
													<option value="Foreign National">Foreign National</option>
												</select>
												<p ng-show="KycFrm.$submitted && KycFrm.residential_status.$invalid" class="err-mark">Please select Residential Status.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Name of Nominee<span>*(<sup></sup> Required)</span></label>
												<input type="text" ng-model="nominee_name" name="lname" class="form-control err" placeholder="Enter Last Name" required ischar>
												<p ng-show="KycFrm.$submitted && KycFrm.nominee_name.$invalid" class="err-mark">Please enter the last name.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Income Type<span>*(<sup></sup> Required)</span></label>
												<!-- <input type="text" ng-model="sender.income_type" name="income_type" class="form-control err" placeholder="Enter Income Type" required ischar> -->
                                                <select ng-model="income_type" name="income_type" class="form-control">
                                                <option value="">SELECT OPTION</option>
                                                <option value="1">Govt </option>
						                        <option value="2">Public sector </option>    
						                        <option value="3">Private Sector </option>
						                        <option value="4">Business </option>
						                        <option value="5">Agriculture </option>   
						                        <option value="6">Dependent </option> 
                                                </select>
												
												<p ng-show="KycFrm.$submitted && KycFrm.income_type.$invalid" class="err-mark">Please enter the income type.</p>
											</div>

											<div class="form-group">
												<label class="control-label" >Upload Pan Card<span>*(<sup></sup> Required)</span></label>
												<input type="file" name="upload_pancard" file-model="myfile">
												<p ng-show="KycFrm.$submitted && KycFrm.upload_pancard.$invalid" class="err-mark">Please select upload pan card.</p>
											</div>
										</div>
									</div>
									<!-- col 3 end -->
									<div class="clearfix"></div>
									<button type="submit" class="btn btn-primary btn-lg">Register</button>
								</form>
								<!-- /Form -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /Page Content -->
		</div>
	</div>
</div>
</div>
@stop
@section('javascript')
<script src="/js/ng-file-upload-shim.js"></script>	
	<script src="/js/ng-file-upload-shim.min.js"></script>	
	<script src="/js/ng-file-upload.min.js"></script>

<script>
	angular.module('DIPApp')
		.controller('AddSenderCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope, $http,Upload,fileUpload) {
			window.s = $scope
			$scope.kycForm=kycForm;

			function kycForm(sender)
			{
               console.log($scope.myfile);
				var file=$scope.myfile;
				var file1=$scope.myfile1;
			
				if ($scope.KycFrm.$valid)
				{

					   var filter1={
						sender_id:$scope.sender_id,
						last_name:$scope.name,
						mobile:$scope.mobile,
	                     file:$scope.myfile,
	                     lname:$scope.lname,
	                     pincode:$scope.pincode,
	                     city:$scope.city,
	                     mobile:$scope.mobile,
	                     address:$scope.address,
	                     state:$scope.state,
	                     mother_name:$scope.mother_name,
	                     father_name:$scope.father_name,
	                     email:$scope.email,
	                     dob:$scope.dob,
	                     gender:$scope.gender,
	                     religion:$scope.religion,
	                     nationality:$scope.nationality,
	                     category:$scope.category,
	                     education:$scope.education,
	                     marital_status:$scope.marital_status,
	                     residential_status:$scope.residential_status,
	                     pan:$scope.pan,
	                     selectaddress_proof:$scope.selectaddress_proof,
	                     nominee_name:$scope.nominee_name,
	                     nominee_relation:$scope.nominee_relation,
	                     aon:$scope.aon,
	                     income_type:$scope.income_type,
	                     customer_status:$scope.customer_status,
	                     dob_nominee:$scope.dob_nominee,
	                     annual_income:$scope.annual_income,
	                     customer_type:$scope.customer_type,
	                     politically_exposed:$scope.politically_exposed,
	                     file1:$scope.myfile1,
	                     file2:$scope.myfile2
                    
		                }
              

					   var kycForm=Upload.upload({
					   	url:'/postKyc',
					   	method:"post",
					   	data:filter1,
					   });
					   kycForm.then(function(response)
					   {
                          console.log(response);
                        if(response.status==200)
                        {
                          sweetAlert('Success', 'KYC Form Submitted Successfully!!.', 'success')
			                setTimeout(function () {
			                  location.reload();
			                }, 1500)

                        }else{
                        sweetAlert('Error', 'Something Is Wrong.', 'error')
                        
                        
                        }
                  });

					   
				}else
				{
					sweetAlert('Error', 'Something Is Wrong.', 'error')
				}
                       
			
			}
			function fail (err) {
				sweetAlert('Error', 'Something went wrong', 'error')
			}
	}])
</script>
@stop