function searchEmployeesByFname() {
  //get the first name 
  var fname = document.getElementById('first_name').value
  //construct the URL and redirect to it
  window.location = '/employees/search/' + encodeURI(fname)
}