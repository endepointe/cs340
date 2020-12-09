function filterEmployeesByProject() {
  //get the id of the selected homeworld from the filter dropdown
  var project_name = document.getElementById('project_filter').value
  //construct the URL and redirect to it
  window.location = `/employees/filter/${project_name}`;
  console.log('filtering by project' + project_name);
}
