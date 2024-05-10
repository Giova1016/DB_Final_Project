window.onload = function() {
    // Fetch data from the database and populate the data container
    fetchData();

    document.getElementById('business-form').addEventListener('submit', addBusinesses);
}

function fetchData() {
    // Send an AJAX request to a PHP file that queries the database and retrieve the data
    // Then, populate the data container with the retrieved data
}

function addBusinesses(event) {
    event.preventDefault();

    // Get the values from the form inputs
    const businessName = document.getElementById('business-name').value;
    const ownerId = document.getElementById('owner-id').value;

    // Send an AJAX request to a PHP file that inserts the new business into the database
    // After successful insertion, update the data container with the new data

    // Reset the form inputs
    document.getElementById('business-form').reset();
}