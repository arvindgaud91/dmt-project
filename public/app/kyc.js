const app = require('./main')
const toastr = require('toastr')

app.controller('KYCCtrl', ['$scope', 'HTTP', 'Upload', ($scope, HTTP, Upload) => {
  window.s = $scope
  $scope.types = ['Identity document', 'Address proof']
  $scope.kyc = formatKYC(kyc)
  $scope.documents = documents.map(formatDocument)
  $scope.logs = logs.map(formatLog)
  $scope.updateKYCDetails = updateKYCDetails
  $scope.uploadKYCDocument = uploadKYCDocument
  $scope.remove = remove
  $scope.osvRequest = osvRequest

  function updateKYCDetails (kyc) {
    if ($scope.KYCDetailsFrm.$invalid) {
      toastr.error("Fill all details correctly.")
      return
    }
    HTTP.post('/users/'+{{Cookie::get('userid')}}+'/kycdata', kyc)
      .fork(fail, d => {
        toastr.success("Saved the KYC details")
      })
  }
  function uploadKYCDocument (ukd) {
    if ($scope.uploadKYCDocumentFrm.$invalid) {
      toastr.error("Fill all details correctly.")
      return
    }
    if (! ukd.file) {
      toastr.error("Forgot to choose a document to upload.")
      return
    }
    Upload.upload({
      url: '/users/'+{{Cookie::get('userid')}}+'/kyc-documents',
      data: ukd
    }).then(d => {
      $scope.documents.push(formatDocument(d.data))
      toastr.success("Document uploaded.")
      $('#documentUploadModal').modal('hide')
      $scope.ukd = {}
      $scope.uploadKYCDocumentFrm.$setPristine()
    }, fail)
  }
  function remove (doc) {
    
  }
  function osvRequest (doc) {
    HTTP.post('/users/'+{{Cookie::get('userid')}}+'/onsite-verification-requests', {notes: ''})
      .fork(e => {
        if (e.data && e.data.code == 1) {
          toastr.error(e.data.message)
          return
        }
        fail(e)
      }, d => {
        toastr.success("Request has been sent. Will respond shortly.")
      })
  }

  function formatKYC (kyc) {
    if (! kyc) return {}
    kyc.dob = new Date(kyc.dob)
    return kyc
  }
  function formatDocument (doc) {
    doc.created_at = new Date(doc.created_at)
    return doc
  }
  function formatLog (log) {
    log.created_at = new Date(log.created_at)
    return log    
  }

  function fail (err) {
    toastr.error("Something went wrong")
  }
}])