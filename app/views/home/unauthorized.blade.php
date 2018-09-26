<title>No access</title>
  <div ng-controller="HomeCtrl" class="head-weight">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
          
          <div class="panel-body">
            <!-- Start row -->
            <div class="row">
              <div class="col-md-4" style="margin-top: 15%;margin-left: 25% ">
                <h4><img src="images/no-access.png"></h4>
              </div>
            </div>
            <!-- End row -->
          </div>
        </div>
      </div>
    </div>
  </div>


@section('javascript')
<script>
  angular.module('DIPApp')
    .controller('HomeCtrl', ['$scope', '$http', function ($scope, $http) {


      function fail (err) {
        sweetAlert('Error', 'Something went wrong', 'error')
      }
    }])
</script>
@stop
