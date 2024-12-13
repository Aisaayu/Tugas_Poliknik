// Function to show list of scheduled patients for Dokter
function showScheduledPatients() {
    var doctorId = document.getElementById('doctorId').value;
    
    fetch('get_patients.php', {
        method: 'POST',
        body: JSON.stringify({ doctor_id: doctorId })
    })
    .then(response => response.json())
    .then(data => {
        var pasienList = document.getElementById('pasienList');
        pasienList.innerHTML = '';
        data.patients.forEach(function(patient) {
            var li = document.createElement('li');
            li.textContent = patient.name + ' - ' + patient.appointment_time;
            pasienList.appendChild(li);
        });
    })
    .catch(error => console.log('Error:', error));
}
